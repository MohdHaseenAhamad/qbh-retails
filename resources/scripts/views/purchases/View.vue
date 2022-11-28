<script setup>
import { useI18n } from 'vue-i18n'
import { computed, reactive, ref, watch, inject } from 'vue'
import PurchaseDropdown from '@/scripts/components/dropdowns/PurchaseIndexDropdown.vue'
import { useRoute, useRouter } from 'vue-router'
import { debounce } from 'lodash'
import { usePurchaseStore } from '@/scripts/stores/purchase'
import { useModalStore } from '@/scripts/stores/modal'
import { useNotificationStore } from '@/scripts/stores/notification'
import { useUserStore } from '@/scripts/stores/user'
import { useDialogStore } from '@/scripts/stores/dialog'
import SendInvoiceModal from '@/scripts/components/modal-components/SendInvoiceModal.vue'
import LoadingIcon from '@/scripts/components/icons/LoadingIcon.vue'
import abilities from '@/scripts/stub/abilities'

const modalStore = useModalStore()
const purchaseStore = usePurchaseStore()
const notificationStore = useNotificationStore()
const userStore = useUserStore()
const dialogStore = useDialogStore()

const { t } = useI18n()
const utils = inject('$utils')
const id = ref(null)
const count = ref(null)
const purchaseData = ref(null)
const currency = ref(null)
const route = useRoute()
const router = useRouter()
const status = ref([
  'DRAFT',
  'SENT',
  'VIEWED',
  'EXPIRED',
  'ACCEPTED',
  'REJECTED',
])
const isMarkAsSent = ref(false)
const isSendingEmail = ref(false)
const isRequestOnGoing = ref(false)
const isSearching = ref(false)
const isLoading = ref(false)

const searchData = reactive({
  orderBy: null,
  orderByField: null,
  searchText: null,
})

const pageTitle = computed(() => purchaseData.value.purchase_no)

const getOrderBy = computed(() => {
  if (searchData.orderBy === 'asc' || searchData.orderBy == null) {
    return true
  }
  return false
})

const getOrderName = computed(() => {
  if (getOrderBy.value) {
    return t('general.ascending')
  }
  return t('general.descending')
})

const shareableLink = computed(() => {
  console.log(`/purchases/pdf/${purchaseData.value.unique_hash}`)
  return `/purchases/pdf/${purchaseData.value.unique_hash}`
})

const getCurrentPurchaseId = computed(() => {
  if (purchaseData.value && purchaseData.value.id) {
    return purchase.value.id
  }
  return null
})

watch(route, (to, from) => {
  if (to.name === 'purchases.view') {
    loadPurchase()
  }
})

async function onMarkAsSent() {
  dialogStore
    .openDialog({
      title: t('general.are_you_sure'),
      message: t('purchases.purchase_mark_as_sent'),
      yesLabel: t('general.ok'),
      noLabel: t('general.cancel'),
      variant: 'primary',
      hideNoButton: false,
      size: 'lg',
    })
    .then(async (response) => {
      isMarkAsSent.value = false
      if (response) {
        await purchaseStore.markAsSent({
          id: purchaseData.value.id,
          status: 'SENT',
        })
        purchaseData.value.status = 'SENT'
        isMarkAsSent.value = true
      }
    })
}

async function onSendPurchase(id) {
  modalStore.openModal({
    title: t('purchases.send_purchase'),
    componentName: 'SendInvoiceModal',
    id: purchaseData.value.id,
    data: purchaseData.value,
  })
}

function hasActiveUrl(id) {
  return route.params.id == id
}

async function loadPurchases() {
  isLoading.value = true
  await purchaseStore.fetchPurchases()
  isLoading.value = false

  setTimeout(() => {
    scrollToPurchase()
  }, 500)
}

function scrollToPurchase() {
  const el = document.getElementById(`purchase-${route.params.id}`)
  if (el) {
    el.scrollIntoView({ behavior: 'smooth' })
    el.classList.add('shake')
  }
}

async function loadPurchase() {
  let response = await purchaseStore.fetchPurchase(route.params.id)
  if (response.data) {
    purchaseData.value = { ...response.data.data }
  }
}

async function onSearched() {
  let data = ''
  if (
    searchData.searchText !== '' &&
    searchData.searchText !== null &&
    searchData.searchText !== undefined
  ) {
    data += `search=${searchData.searchText}&`
  }

  if (searchData.orderBy !== null && searchData.orderBy !== undefined) {
    data += `orderBy=${searchData.orderBy}&`
  }
  if (
    searchData.orderByField !== null &&
    searchData.orderByField !== undefined
  ) {
    data += `orderByField=${searchData.orderByField}`
  }
  isSearching.value = true
  let response = await purchaseStore.searchPurchase(data)
  isSearching.value = false
  if (response.data) {
    purchaseStore.purchases = response.data.data
  }
}

function sortData() {
  if (searchData.orderBy === 'asc') {
    searchData.orderBy = 'desc'
    onSearched()
    return true
  }
  searchData.orderBy = 'asc'
  onSearched()
  return true
}

loadPurchases()
loadPurchase()
onSearched = debounce(onSearched, 500)
</script>

<template>
  <SendInvoiceModal />
  <BasePage v-if="purchaseData" class="xl:pl-96 xl:ml-8">
    <BasePageHeader :title="pageTitle">
      <template #actions>
      
        <!-- Invoice Dropdown  -->
        <PurchaseDropdown
          class="ml-3"
          :row="purchaseData"
          :load-data="loadPurchases"
        />
      </template>
    </BasePageHeader>

    <!-- sidebar -->
    <div
      class="
        fixed
        top-0
        left-0
        hidden
        h-full
        pt-16
        pb-4
        ml-56
        bg-white
        xl:ml-64
        w-88
        xl:block
      "
    >
      <div
        class="
          flex
          items-center
          justify-between
          px-4
          pt-8
          pb-2
          border border-gray-200 border-solid
          height-full
        "
      >
        <div class="mb-6">
          <BaseInput
            v-model="searchData.searchText"
            :placeholder="$t('general.search')"
            type="text"
            variant="gray"
            @input="onSearched()"
          >
            <template #right>
              <BaseIcon name="SearchIcon" class="h-5 text-gray-400" />
            </template>
          </BaseInput>
        </div>

        <div class="flex mb-6 ml-3" role="group" aria-label="First group">
          <BaseDropdown class="ml-3" position="bottom-start">
            <template #activator>
              <BaseButton size="md" variant="gray">
                <BaseIcon name="FilterIcon" />
              </BaseButton>
            </template>
            <div
              class="
                px-2
                py-1
                pb-2
                mb-1 mb-2
                text-sm
                border-b border-gray-200 border-solid
              "
            >
              {{ $t('general.sort_by') }}
            </div>

            <BaseDropdownItem class="flex px-1 py-2 cursor-pointer">
              <BaseInputGroup class="-mt-3 font-normal">
                <BaseRadio
                  id="filter_invoice_date"
                  v-model="searchData.orderByField"
                  :label="$t('purchases.purchase_date')"
                  size="sm"
                  name="filter"
                  value="purchase_date"
                  @update:modelValue="onSearched"
                />
              </BaseInputGroup>
            </BaseDropdownItem>

            <BaseDropdownItem class="flex px-1 py-2 cursor-pointer">
              <BaseInputGroup class="-mt-3 font-normal">
                <BaseRadio
                  id="filter_purchase_no"
                  v-model="searchData.orderByField"
                  :label="$t('purchases.number')"
                  value="purchase_no"
                  size="sm"
                  name="filter"
                  @update:modelValue="onSearched"
                />
              </BaseInputGroup>
            </BaseDropdownItem>
          </BaseDropdown>

          <BaseButton class="ml-1" size="md" variant="gray" @click="sortData">
            <BaseIcon v-if="getOrderBy" name="SortAscendingIcon" />
            <BaseIcon v-else name="SortDescendingIcon" />
          </BaseButton>
        </div>
      </div>

      <div
        v-if="purchaseStore && purchaseStore.purchases"
        class="
          h-full
          pb-32
          overflow-y-scroll
          border-l border-gray-200 border-solid
          base-scroll
        "
      >
        <div v-for="(purchase, index) in purchaseStore.purchases" :key="index">
          <router-link
            v-if="purchase && !isLoading"
            :id="'purchase-' + purchase.id"
            :to="`/admin/purchases/${purchase.id}/view`"
            :class="[
              'flex justify-between side-invoice p-4 cursor-pointer hover:bg-gray-100 items-center border-l-4 border-transparent',
              {
                'bg-gray-100 border-l-4 border-primary-500 border-solid':
                  hasActiveUrl(purchase.id),
              },
            ]"
            style="border-bottom: 1px solid rgba(185, 193, 209, 0.41)"
          >
            <div class="flex-2">
              <BaseText
                :text="purchase.supplier.name"
                :length="30"
                class="
                  pr-2
                  mb-2
                  text-sm
                  not-italic
                  font-normal
                  leading-5
                  text-black
                  capitalize
                  truncate
                "
              />

              <div
                class="
                  mt-1
                  mb-2
                  text-xs
                  not-italic
                  font-medium
                  leading-5
                  text-gray-600
                "
              >
                {{ purchase.purchase_no }}
              </div>
              <BaseEstimateStatusBadge
                :status="purchase.status"
                class="px-1 text-xs"
              >
                {{ purchase.status }}
              </BaseEstimateStatusBadge>
            </div>

            <div class="flex-1 whitespace-nowrap right">
              <BaseFormatMoney
                class="
                  mb-2
                  text-xl
                  not-italic
                  font-semibold
                  leading-8
                  text-right text-gray-900
                  block
                "
                :amount="purchase.total"
                :currency="purchase.supplier.currency"
              />
              <div
                class="
                  text-sm
                  not-italic
                  font-normal
                  leading-5
                  text-right text-gray-600
                  est-date
                "
              >
                {{ purchase.formatted_purchase_date }}
              </div>
            </div>
          </router-link>
        </div>
        <div class="flex justify-center p-4 items-center">
          <LoadingIcon
            v-if="isLoading"
            class="h-6 m-1 animate-spin text-primary-400"
          />
        </div>
        <p
          v-if="!purchaseStore.purchases.length && !isLoading"
          class="flex justify-center px-4 mt-5 text-sm text-gray-600"
        >
          {{ $t('purchases.no_matching_purchases') }}
        </p>
      </div>
    </div>

    <div
      class="flex flex-col min-h-0 mt-8 overflow-hidden"
      style="height: 75vh"
    >
      <iframe
        :src="`${shareableLink}`"
        class="
          flex-1
          border border-gray-400 border-solid
          bg-white
          rounded-md
          frame-style
        "
      />
    </div>
  </BasePage>
</template>
