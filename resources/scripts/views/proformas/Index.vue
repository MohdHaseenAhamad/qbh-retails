<template>
  <BasePage>
    <SendProformaModal />
    <BasePageHeader :title="$t('proformas.title')">
      <BaseBreadcrumb>
        <BaseBreadcrumbItem :title="$t('general.home')" to="dashboard" />
        <BaseBreadcrumbItem :title="$tc('proformas.proforma', 2)" to="#" active />
      </BaseBreadcrumb>

      <template #actions>
        <BaseButton
          v-show="proformaStore.proformaTotalCount"
          variant="primary-outline"
          @click="toggleFilter"
        >
          {{ $t('general.filter') }}
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
          v-if="userStore.hasAbilities(abilities.CREATE_INVOICE)"
          to="proformas/create"
        >
          <BaseButton variant="primary" class="ml-4">
            <template #left="slotProps">
              <BaseIcon name="PlusIcon" :class="slotProps.class" />
            </template>
            {{ $t('proformas.new_proforma') }}
          </BaseButton>
        </router-link>
      </template>
    </BasePageHeader>

    <BaseFilterWrapper
      v-show="showFilters"
      :row-on-xl="true"
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

      <BaseInputGroup :label="$t('proformas.status')">
        <BaseMultiselect
          v-model="filters.status"
          :groups="true"
          :options="status"
          searchable
          :placeholder="$t('general.select_a_status')"
          @update:modelValue="setActiveTab"
          @remove="clearStatusSearch()"
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

      <BaseInputGroup :label="$t('proformas.proforma_number')">
        <BaseInput v-model="filters.proforma_number">
          <template #left="slotProps">
            <BaseIcon name="HashtagIcon" :class="slotProps.class" />
          </template>
        </BaseInput>
      </BaseInputGroup>
      <div class="pt-6">
      <router-link
        v-if="filteredDataCount"
        :to="`/filtered-proformas-list?selectedCompany=${companyStore.selectedCompany.id}&$customer_id=${filters.customer_id}&status=${filters.status}&from_date=${filters.from_date}&to_date=${filters.to_date}&proforma_number=${filters.proforma_number}`"
        target="_blank"
      >
        <BaseButton class="mr-3" variant="primary-outline" type="button">
          <span class="flex">
            {{ $t('Download') }}
          </span>
        </BaseButton>
      </router-link>
      </div>
    </BaseFilterWrapper>

    <BaseEmptyPlaceholder
      v-show="showEmptyScreen"
      :title="$t('proformas.no_proformas')"
      :description="$t('proformas.list_of_proformas')"
    >
      <MoonwalkerIcon class="mt-5 mb-4" />
      <template
        v-if="userStore.hasAbilities(abilities.CREATE_INVOICE)"
        #actions
      >
        <BaseButton
          variant="primary-outline"
          @click="$router.push('/admin/proformas/create')"
        >
          <template #left="slotProps">
            <BaseIcon name="PlusIcon" :class="slotProps.class" />
          </template>
          {{ $t('proformas.add_new_proforma') }}
        </BaseButton>
      </template>
    </BaseEmptyPlaceholder>

    <div v-show="!showEmptyScreen" class="relative table-container">
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
          <BaseTab :title="$t('general.draft')" filter="DRAFT" />
          <BaseTab :title="$t('general.due')" filter="DUE" />
          <BaseTab :title="$t('general.sent')" filter="SENT" />
          <BaseTab :title="$t('general.all')" filter="" />
        </BaseTabGroup>

        <BaseDropdown
          v-if="
            proformaStore.selectedProformas.length &&
            userStore.hasAbilities(abilities.DELETE_INVOICE)
          "
          class="absolute float-right"
        >
          <template #activator>
            <span
              class="
                flex
                text-sm
                font-medium
                cursor-pointer
                select-none
                text-primary-400
              "
            >
              {{ $t('general.actions') }}
              <BaseIcon name="ChevronDownIcon" />
            </span>
          </template>

          <BaseDropdownItem @click="removeMultipleProformas">
            <BaseIcon name="TrashIcon" class="mr-3 text-gray-600" />
            {{ $t('general.delete') }}
          </BaseDropdownItem>
        </BaseDropdown>
      </div>

      <BaseTable
        ref="table"
        :data="fetchData"
        :columns="proformaColumns"
        :placeholder-count="proformaStore.proformaTotalCount >= 20 ? 10 : 5"
        class="mt-10"
      >
        <!-- Select All Checkbox -->
        <template #header>
          <div class="absolute items-center left-6 top-2.5 select-none">
            <BaseCheckbox
              v-model="proformaStore.selectAllField"
              variant="primary"
              @change="proformaStore.selectAllProformas"
            />
          </div>
        </template>

        <template #cell-checkbox="{ row }">
          <div class="relative block">
            <BaseCheckbox
              :id="row.id"
              v-model="selectField"
              :value="row.data.id"
            />
          </div>
        </template>

        <template #cell-name="{ row }">
          <BaseText :text="row.data.customer.name" :length="30" />
        </template>

        <!-- Proforma Number  -->
        <template #cell-proforma_number="{ row }">
          <router-link
            :to="{ path: `proformas/${row.data.id}/view` }"
            class="font-medium text-primary-500"
          >
            {{ row.data.proforma_number }}
          </router-link>
        </template>

        <!-- Proforma date  -->
        <template #cell-proforma_date="{ row }">
          {{ row.data.formatted_proforma_date }}
        </template>

        <!-- Proforma Total  -->
        <template #cell-total="{ row }">
          <BaseFormatMoney
            :amount="row.data.total"
            :currency="row.data.customer.currency"
          />
        </template>

        <!-- Proforma status  -->
        <template #cell-status="{ row }">
          <BaseProformaStatusBadge :status="row.data.status" class="px-3 py-1">
            {{ row.data.status }}
          </BaseProformaStatusBadge>
        </template>

        <!-- Due Amount + Paid Status  -->
        <template #cell-due_amount="{ row }">
          <div class="flex justify-between">
            <BaseFormatMoney
              :amount="row.data.due_amount"
              :currency="row.data.currency"
            />

            <BasePaidStatusBadge
              :status="row.data.paid_status"
              class="px-1 py-0.5 ml-2"
            >
              {{ row.data.paid_status }}
            </BasePaidStatusBadge>
          </div>
        </template>

        <!-- Actions -->
        <template v-if="hasAtleastOneAbility()" #cell-actions="{ row }">
          <ProformaDropdown :row="row.data" :table="table" />
        </template>
      </BaseTable>
    </div>
  </BasePage>
</template>

<script setup>
import { computed, onUnmounted, reactive, ref, watch, inject } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useProformaStore } from '@/scripts/stores/proforma'
import { useNotificationStore } from '@/scripts/stores/notification'
import { useDialogStore } from '@/scripts/stores/dialog'
import { useUserStore } from '@/scripts/stores/user'
import abilities from '@/scripts/stub/abilities'
import { debouncedWatch } from '@vueuse/core'

import MoonwalkerIcon from '@/scripts/components/icons/empty/MoonwalkerIcon.vue'
import ProformaDropdown from '@/scripts/components/dropdowns/ProformaIndexDropdown.vue'
import SendProformaModal from '@/scripts/components/modal-components/SendProformaModal.vue'
import { useCompanyStore } from '../../stores/company'
// Stores
const proformaStore = useProformaStore()
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
    options: ['ALL', 'DRAFT', 'DUE', 'SENT', 'VIEWED', 'OVERDUE', 'COMPLETED'],
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
  status: 'DRAFT',
  from_date: '',
  to_date: '',
  proforma_number: '',
})

const showEmptyScreen = computed(
  () => !proformaStore.proformaTotalCount && !isRequestOngoing.value
)

const selectField = computed({
  get: () => proformaStore.selectedProformas,
  set: (value) => {
    return proformaStore.selectProforma(value)
  },
})

const proformaColumns = computed(() => {
  return [
    {
      key: 'checkbox',
      thClass: 'extra w-10',
      tdClass: 'font-medium text-gray-900',
      placeholderClass: 'w-10',
      sortable: false,
    },
    {
      key: 'proforma_date',
      label: t('proformas.date'),
      thClass: 'extra',
      tdClass: 'font-medium',
    },
    { key: 'proforma_number', label: t('proformas.number') },
    { key: 'name', label: t('proformas.customer') },
    { key: 'status', label: t('proformas.status') },
    {
      key: 'due_amount',
      label: t('dashboard.recent_invoices_card.amount_due'),
    },
    {
      key: 'total',
      label: t('proformas.total'),
      tdClass: 'font-medium text-gray-900',
    },

    {
      key: 'actions',
      label: t('proformas.action'),
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
  if (proformaStore.selectAllField) {
    proformaStore.selectAllProformas()
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
    proforma_number: filters.proforma_number,
    orderByField: sort.fieldName || 'created_at',
    orderBy: sort.order || 'desc',
    page,
  }

  isRequestOngoing.value = true

  let response = await proformaStore.fetchProformas(data)

  filteredDataCount.value = response.data.data.length > 0 ? true : false
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
  proformaStore.$patch((state) => {
    state.selectedProformas = []
    state.selectAllField = false
  })

  refreshTable()
}

function clearFilter() {
  filters.customer_id = ''
  filters.status = ''
  filters.from_date = ''
  filters.to_date = ''
  filters.proforma_number = ''

  activeTab.value = t('general.all')
}

async function removeMultipleProformas() {
  dialogStore
    .openDialog({
      title: t('general.are_you_sure'),
      message: t('proformas.confirm_delete'),
      yesLabel: t('general.ok'),
      noLabel: t('general.cancel'),
      variant: 'danger',
      hideNoButton: false,
      size: 'lg',
    })
    .then(async (res) => {
      if (res) {
        await proformaStore.deleteMultipleProformas().then((res) => {
          if (res.data.success) {
            refreshTable()

            proformaStore.$patch((state) => {
              state.selectedProformas = []
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
    case 'ALL':
    activeTab.value = t('general.all')
    break
    case 'SENT':
      activeTab.value = t('general.sent')
      break

    case 'DUE':
      activeTab.value = t('general.due')
      break

    case 'COMPLETED':
      activeTab.value = t('proformas.completed')
      break

    case 'PAID':
      activeTab.value = t('proformas.paid')
      break

    case 'UNPAID':
      activeTab.value = t('proformas.unpaid')
      break

    case 'PARTIALLY_PAID':
      activeTab.value = t('proformas.partially_paid')
      break

    case 'VIEWED':
      activeTab.value = t('proformas.viewed')
      break

    case 'OVERDUE':
      activeTab.value = t('proformas.overdue')
      break

    default:
      activeTab.value = t('general.all')
      break
  }
}
</script>
