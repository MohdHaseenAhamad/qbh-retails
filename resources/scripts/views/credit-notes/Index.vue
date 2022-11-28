<template>
  <BasePage>
    <SendInvoiceModal />
    <BasePageHeader :title="$t('creditnote.title')">
      <BaseBreadcrumb>
        <BaseBreadcrumbItem :title="$t('general.home')" to="dashboard" />
        <BaseBreadcrumbItem :title="$tc('creditnote.creditnotes', 2)" to="#" active />
      </BaseBreadcrumb>

      <template #actions>

        <BaseButton
          variant="primary-outline"
          @click="toggleFilter"
        >
          {{ $t('creditnote.search') }}

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
          to="credit-note/create"
        >
          <BaseButton variant="primary" class="ml-4">
            <template #left="slotProps">
              <BaseIcon name="PlusIcon" :class="slotProps.class" />
            </template>
            {{ $t('creditnote.create') }}
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

      <BaseInputGroup :label="$t('credits.number')">
        <BaseInput v-model="filters.credit_number">
          <template #left="slotProps">
            <BaseIcon name="HashtagIcon" :class="slotProps.class" />
          </template>
        </BaseInput>
      </BaseInputGroup>

      <div class="pt-6">
        <router-link
          v-if="filteredDataCount"
          :to="`/filtered-credits-list?selectedCompany=${companyStore.selectedCompany.id}&customer_id=${filters.customer_id}&status=${filters.status}&from_date=${filters.from_date}&to_date=${filters.to_date}&invoice_number=${filters.credit_number}`"
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

        <BaseTab :title="$t('general.allcreditnotes')" filter="" />
        <BaseTab :title="$t('general.sent')" filter="SENT" />
      </BaseTabGroup>

    </div>

    <BaseEmptyPlaceholder
      v-show="showEmptyScreen"
      :title="$t('credits.no_credits')"
      :description="$t('credits.list_of_credit_notes')"
    >
      <MoonwalkerIcon class="mt-5 mb-4" />
     
    </BaseEmptyPlaceholder>

    <div v-show="!showEmptyScreen" class="relative table-container">

      <BaseTable
        ref="table"
        :data="fetchData"
        :columns="creditNoteColumns"
        :placeholder-count="creditStore.invoiceTotalCount >= 20 ? 10 : 5"
        class="mt-10">
        

        <template #cell-name="{ row }">
          <BaseText :text="row.data.customer.name" :length="30" />
        </template>

        <!-- Invoice Number  -->
        <template #cell-credit_number="{ row }">
          <router-link
            :to="{ path: `credit-note/${row.data.id}/view` }"
            class="font-medium text-primary-500"
          >
            {{ row.data.credit_number }}
          </router-link>
        </template>

        <!-- Invoice date  -->
        <template #cell-credit_date="{ row }">
          {{ row.data.formatted_credit_date }}
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
          <CreditNoteIndexDropdown :row="row.data" :table="table" />
        </template>
      </BaseTable>

    </div>

  </BasePage>
</template>

<script setup>
import { computed, onUnmounted, reactive, ref, watch, inject } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useCreditStore } from '@/scripts/stores/credit'
import { useInvoiceStore } from '@/scripts/stores/invoice'
import { useNotificationStore } from '@/scripts/stores/notification'
import { useDialogStore } from '@/scripts/stores/dialog'
import { useUserStore } from '@/scripts/stores/user'
import abilities from '@/scripts/stub/abilities'
import { debouncedWatch } from '@vueuse/core'

import MoonwalkerIcon from '@/scripts/components/icons/empty/MoonwalkerIcon.vue'
import CreditNoteIndexDropdown from '@/scripts/components/dropdowns/CreditNoteIndexDropdown.vue'
import SendInvoiceModal from '@/scripts/components/modal-components/SendInvoiceModal.vue'
import { useCompanyStore } from '../../stores/company'

// Stores
const creditStore = useCreditStore()
const invoiceStore = useInvoiceStore()
const dialogStore = useDialogStore()
const notificationStore = useNotificationStore()
const companyStore = useCompanyStore()

const { t } = useI18n()

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
const filteredDataCount = ref(true)
const activeTab = ref('general.draft')
const router = useRouter()
const userStore = useUserStore()

let filters = reactive({
  customer_id: '',
  status: '',
  from_date: '',
  to_date: '',
  credit_number: '',
})

const showEmptyScreen = computed(
  () => !creditStore.creditTotalCount && !isRequestOngoing.value
)

const selectField = computed({
  get: () => creditStore.selectedInvoices,
  set: (value) => {
    return creditStore.selectInvoice(value)
  },
})

const creditNoteColumns = computed(() => {
  return [
    {
      key: 'credit_date',
      label: t('invoices.date'),
      thClass: 'extra',
      tdClass: 'font-medium',
    },
    { key: 'credit_number', label: t('invoices.number') },
    { key: 'name', label: t('invoices.customer') },
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
  if (creditStore.selectAllField) {
    creditStore.selectAllInvoices()
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

  let response = await creditStore.fetchCreditNotes(data)

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

async function fetchInvoiceData({ page, filter, sort }) {
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

  let response = await creditStore.fetchInvoices(data)
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
  creditStore.selectedTab = val.dataValue
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
  creditStore.$patch((state) => {
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
        await creditStore.deleteMultipleInvoices().then((res) => {
          if (res.data.success) {
            refreshTable()

            creditStore.$patch((state) => {
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
