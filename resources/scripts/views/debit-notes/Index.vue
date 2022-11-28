<template>
  <BasePage>
    <SendInvoiceModal />
    <BasePageHeader :title="$t('debitnote.title')">
      <BaseBreadcrumb>
        <BaseBreadcrumbItem :title="$t('general.home')" to="dashboard" />
        <BaseBreadcrumbItem :title="$tc('debitnote.debitnotes', 2)" to="#" active />
      </BaseBreadcrumb>

      <template #actions>

        <BaseButton
          variant="primary-outline"
          @click="toggleFilter"
        >
          {{ $t('debitnote.search') }}

          <template #right="slotProps">
            <BaseIcon
              v-if="!showFilters"
              name="FilterIcon"
              :class="slotProps.class"
            />
            <BaseIcon v-else name="XIcon" :class="slotProps.class" />
          </template>
        </BaseButton>

        <router-link
          to="debit-note/create"
        >
          <BaseButton variant="primary" class="ml-4">
            <template #left="slotProps">
              <BaseIcon name="PlusIcon" :class="slotProps.class" />
            </template>
            {{ $t('debitnote.create') }}
          </BaseButton>
        </router-link>

      </template>
    </BasePageHeader>

    <BaseFilterWrapper
      v-show="showFilters"
      @clear="clearFilter"
    >
      <BaseInputGroup :label="$tc('customers.customer', 1)">
        <BaseCustomerSelectInput
          v-model="filters.customer_id"
          :placeholder="$t('customers.type_or_click')"
          value-prop="id"
          label="name"
        />
      </BaseInputGroup>

      

      <BaseInputGroup :label="$t('general.from')">
        <BaseDatePicker
          v-model="filters.from_date"
          :calendar-button="true"
          calendar-button-icon="calendar"
        />
      </BaseInputGroup>

      <div
        class="hidden w-8 h-0 mx-4 border border-gray-400 border-solid xl:block"
        style="margin-top: 1.5rem"
      />

      <BaseInputGroup :label="$t('general.to')" class="mt-2">
        <BaseDatePicker
          v-model="filters.to_date"
          :calendar-button="true"
          calendar-button-icon="calendar"
        />
      </BaseInputGroup>

      <BaseInputGroup :label="$t('debits.number')">
        <BaseInput v-model="filters.debit_number">
          <template #left="slotProps">
            <BaseIcon name="HashtagIcon" :class="slotProps.class" />
          </template>
        </BaseInput>
      </BaseInputGroup>

      <div class="pt-6">
        <router-link
          v-if="filteredDataCount"
          :to="`/filtered-debits-list?selectedCompany=${companyStore.selectedCompany.id}&customer_id=${filters.customer_id}&status=${filters.status}&from_date=${filters.from_date}&to_date=${filters.to_date}&debit_number=${filters.debit_number}`"
          target="_blank">
          <BaseButton class="mr-3" variant="primary-outline" type="button">
            <span class="flex">
              {{ $t('Download') }}
            </span>
          </BaseButton>
        </router-link>
      </div>

    </BaseFilterWrapper>


    <div
      class="
        relative
        flex
        items-center
        justify-between
        h-10
        mt-5
        list-none
        border-b-2 border-gray-200 border-solid
      "
    >
      <!-- Tabs -->
      <BaseTabGroup class="-mb-5" @change="setStatusFilter">

        <BaseTab :title="$t('general.alldebitnotes')" filter="" />

          <BaseTab :title="$t('general.sent')" filter="SENT" />
      
      </BaseTabGroup>

    </div>

    <BaseEmptyPlaceholder
      v-show="showEmptyScreen"
      :title="$t('debits.no_debits')"
      :description="$t('debits.list_of_debits')"
    >
      <MoonwalkerIcon class="mt-5 mb-4" />
     
    </BaseEmptyPlaceholder>

    <div v-show="!showEmptyScreen" class="relative table-container">

      <BaseTable
        ref="table"
        :data="fetchData"
        :columns="debitNoteColumns"
        :placeholder-count="debitStore.invoiceTotalCount >= 20 ? 10 : 5"
        class="mt-10">
        

        <template #cell-name="{ row }">
          <BaseText :text="row.data.customer.name" :length="30" />
        </template>

        <!-- Invoice Number  -->
        <template #cell-debit_number="{ row }">
          <router-link
            :to="{ path: `debit-note/${row.data.id}/view` }"
            class="font-medium text-primary-500"
          >
            {{ row.data.debit_number }}
          </router-link>
        </template>

        <!-- Invoice date  -->
        <template #cell-debit_date="{ row }">
          {{ row.data.formatted_debit_date }}
        </template>

        <!-- Invoice Total  -->
        <template #cell-total="{ row }">
          <BaseFormatMoney
            :amount="row.data.total"
            :currency="row.data.customer.currency"
          />
        </template>

        <!-- Invoice status  -->
        <template #cell-status="{ row }">
          <BaseInvoiceStatusBadge :status="row.data.status" class="px-3 py-1">
            {{ row.data.status }}
          </BaseInvoiceStatusBadge>
        </template>

        <!-- Actions -->
        <template v-if="hasAtleastOneAbility()" #cell-actions="{ row }">
          <DebitNoteIndexDropdown :row="row.data" :table="table" />
        </template>
      </BaseTable>

    </div>

  </BasePage>
</template>

<script setup>
import { computed, onUnmounted, reactive, ref, watch, inject } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useDebitStore } from '@/scripts/stores/debit'
import { usePurchaseStore } from '@/scripts/stores/purchase'
import { useNotificationStore } from '@/scripts/stores/notification'
import { useDialogStore } from '@/scripts/stores/dialog'
import { useUserStore } from '@/scripts/stores/user'
import abilities from '@/scripts/stub/abilities'
import { debouncedWatch } from '@vueuse/core'

import MoonwalkerIcon from '@/scripts/components/icons/empty/MoonwalkerIcon.vue'
import DebitNoteIndexDropdown from '@/scripts/components/dropdowns/DebitNoteIndexDropdown.vue'
import SendInvoiceModal from '@/scripts/components/modal-components/SendInvoiceModal.vue'
import { useCompanyStore } from '../../stores/company'

// Stores
const debitStore = useDebitStore()
const purchaseStore = usePurchaseStore()
const dialogStore = useDialogStore()
const notificationStore = useNotificationStore()
const companyStore = useCompanyStore()

const { t } = useI18n()
const filteredDataCount = ref(true)

// Local State
const utils = inject('$utils')
const table = ref(null)
const showFilters = ref(false)

const status = ref([
  {
    label: 'Status',
    options: ['DRAFT', 'DUE', 'SENT', 'VIEWED', 'OVERDUE', 'COMPLETED'],
  },
  {
    label: 'Paid Status',
    options: ['UNPAID', 'PAID', 'PARTIALLY_PAID'],
  },
  ,
])
const isRequestOngoing = ref(true)
const activeTab = ref('general.draft')
const router = useRouter()
const userStore = useUserStore()

let filters = reactive({
  customer_id: '',
  status: '',
  from_date: '',
  to_date: '',
  debit_number: '',
})

const showEmptyScreen = computed(
  () => !debitStore.debitTotalCount && !isRequestOngoing.value
)

const selectField = computed({
  get: () => debitStore.selectedInvoices,
  set: (value) => {
    return debitStore.selectInvoice(value)
  },
})

const debitNoteColumns = computed(() => {
  return [
    {
      key: 'debit_date',
      label: t('invoices.date'),
      thClass: 'extra',
      tdClass: 'font-medium',
    },
    { key: 'debit_number', label: t('invoices.number') },
    { key: 'name', label: t('purchases.supplier') },
    { key: 'status', label: t('invoices.status') },
    {
      key: 'total',
      label: t('invoices.total'),
      tdClass: 'font-medium text-gray-900',
    },
    {
      key: 'actions',
      label: t('invoices.action'),
      tdClass: 'text-right text-sm font-medium',
      thClass: 'text-right',
      sortable: false,
    },
  ]
})

debouncedWatch(
  filters,
  () => {
    setFilters()
  },
  { debounce: 500 }
)

onUnmounted(() => {
  if (debitStore.selectAllField) {
    debitStore.selectAllInvoices()
  }
})

function hasAtleastOneAbility() {
  return userStore.hasAbilities([
    abilities.DELETE_INVOICE,
    abilities.EDIT_INVOICE,
    abilities.VIEW_INVOICE,
    abilities.SEND_INVOICE,
  ])
}

async function clearStatusSearch(removedOption, id) {
  filters.status = ''
  refreshTable()
}

function refreshTable() {
  table.value && table.value.refresh()
}

async function fetchData({ page, filter, sort }) {
  let data = {
    customer_id: filters.customer_id,
    status: filters.status,
    from_date: filters.from_date,
    to_date: filters.to_date,
    invoice_number: filters.invoice_number,
    orderByField: sort.fieldName || 'created_at',
    orderBy: sort.order || 'desc',
    page,
  }

  isRequestOngoing.value = true

  let response = await debitStore.fetchDebitNotes(data)
  // let response = await debitStore.fetchInvoices(data)
  // let response = await invoiceStore.fetchInvoices(data)

  filteredDataCount.value = response.data.data.length > 0 ? true : false

  isRequestOngoing.value = false
  return {
    data: response.data.data,
    pagination: {
      totalPages: response.data.meta.last_page,
      currentPage: page,
      totalCount: response.data.meta.total,
      limit: 10,
    },
  }
}

async function fetchPurchaseData({ page, filter, sort }) {
  let data = {
    customer_id: filters.customer_id,
    status: filters.status,
    from_date: filters.from_date,
    to_date: filters.to_date,
    invoice_number: filters.invoice_number,
    orderByField: sort.fieldName || 'created_at',
    orderBy: sort.order || 'desc',
    page,
  }

  isRequestOngoing.value = true

  let response = await debitStore.fetchPurchases(data)
  console.log(response.data.data)

  isRequestOngoing.value = false

  return {
    data: response.data.data,
    pagination: {
      totalPages: response.data.meta.last_page,
      currentPage: page,
      totalCount: response.data.meta.total,
      limit: 10,
    },
  }
}

function changeDataSource(val){
  debitStore.selectedTab = val.dataValue
  // current_tab = val.dataValue
  // setStatusFilter(val)
}

function setStatusFilter(val) {
  if (activeTab.value == val.title) {
    return true
  }

  activeTab.value = val.title

  switch (val.title) {
    case t('general.draft'):
      filters.status = 'DRAFT'
      break
    case t('general.sent'):
      filters.status = 'SENT'
      break

    case t('general.due'):
      filters.status = 'DUE'
      break

    default:
      filters.status = ''
      break
  }
}

function setFilters() {
  debitStore.$patch((state) => {
    state.selectedInvoices = []
    state.selectAllField = false
  })

  refreshTable()
}

function clearFilter() {
  filters.customer_id = ''
  filters.from_date = ''
  filters.to_date = ''
  filters.invoice_number = ''
  filters.status = ''

  activeTab.value = t('general.all')
}

async function removeMultipleInvoices() {
  dialogStore
    .openDialog({
      title: t('general.are_you_sure'),
      message: t('invoices.confirm_delete'),
      yesLabel: t('general.ok'),
      noLabel: t('general.cancel'),
      variant: 'danger',
      hideNoButton: false,
      size: 'lg',
    })
    .then(async (res) => {
      if (res) {
        await debitStore.deleteMultipleInvoices().then((res) => {
          if (res.data.success) {
            refreshTable()

            debitStore.$patch((state) => {
              state.selectedInvoices = []
              state.selectAllField = false
            })
          }
        })
      }
    })
}

function toggleFilter() {
  if (showFilters.value) {
    clearFilter()
  }
  showFilters.value = !showFilters.value
}

function setActiveTab(val) {
  switch (val) {
    case 'DRAFT':
      activeTab.value = t('general.draft')
      break
    case 'SENT':
      activeTab.value = t('general.sent')
      break

    case 'DUE':
      activeTab.value = t('general.due')
      break

    case 'COMPLETED':
      activeTab.value = t('invoices.completed')
      break

    case 'PAID':
      activeTab.value = t('invoices.paid')
      break

    case 'UNPAID':
      activeTab.value = t('invoices.unpaid')
      break

    case 'PARTIALLY_PAID':
      activeTab.value = t('invoices.partially_paid')
      break

    case 'VIEWED':
      activeTab.value = t('invoices.viewed')
      break

    case 'OVERDUE':
      activeTab.value = t('invoices.overdue')
      break

    default:
      activeTab.value = t('general.all')
      break
  }
}
</script>
