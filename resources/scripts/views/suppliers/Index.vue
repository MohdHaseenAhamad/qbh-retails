<template>
  <BasePage>
    <!-- Page Header Section -->
    <BasePageHeader :title="$t('suppliers.title')">
      <BaseBreadcrumb>
        <BaseBreadcrumbItem :title="$t('general.home')" to="dashboard" />
        <BaseBreadcrumbItem
          :title="$tc('suppliers.supplier', 2)"
          to="#"
          active
        /> 
      </BaseBreadcrumb>

      <template #actions>
        <div class="flex items-center justify-end space-x-5">
          <BaseButton
            v-show="supplierStore.totalSuppliers"
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

          <BaseButton
            v-if="userStore.hasAbilities(abilities.CREATE_CUSTOMER)"
            @click="$router.push('suppliers/create')"
          >
            <template #left="slotProps">
              <BaseIcon name="PlusIcon" :class="slotProps.class" />
            </template>
            {{ $t('suppliers.add_new_supplier') }}
          </BaseButton>
        </div>
      </template>
    </BasePageHeader>

    <BaseFilterWrapper :show="showFilters" class="mt-5" @clear="clearFilter">
      <BaseInputGroup :label="$t('customers.contact_name')" class="text-left  w-1/2">
        <BaseInput
          v-model="filters.display_name"
          type="text"
          name="address_name"
          autocomplete="off"
        />
      </BaseInputGroup>

      <div class="pt-6">
        <router-link
          v-if="filteredDataCount"
          :to="`/filtered-suppliers-list?display_name=${filters.display_name}`"
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
      :title="$t('suppliers.no_suppliers')"
      :description="$t('suppliers.list_of_suppliers')"
    >
      <AstronautIcon class="mt-5 mb-4" />

      <template #actions>
        <BaseButton
          v-if="userStore.hasAbilities(abilities.CREATE_CUSTOMER)"
          variant="primary-outline"
          @click="$router.push('/admin/suppliers/create')"
        >
          <template #left="slotProps">
            <BaseIcon name="PlusIcon" :class="slotProps.class" />
          </template>
          {{ $t('suppliers.add_new_supplier') }}
        </BaseButton>
      </template>
    </BaseEmptyPlaceholder>

    <!-- Total no of Customers in Table -->
    <div v-show="!showEmptyScreen" class="relative table-container">
      <div class="relative flex items-center justify-end h-5">
        <BaseDropdown v-if="supplierStore.selectedSuppliers.length">
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
          <BaseDropdownItem @click="removeMultipleSuppliers">
            <BaseIcon name="TrashIcon" class="mr-3 text-gray-600" />
            {{ $t('general.delete') }}
          </BaseDropdownItem>
        </BaseDropdown>
      </div>

      <!-- Table Section -->
      <BaseTable
        ref="tableComponent"
        class="mt-3"
        :data="fetchData"
        :columns="supplierColumns"
      >
        <!-- Select All Checkbox -->
        <template #header>
          <div class="absolute z-10 items-center left-6 top-2.5 select-none">
            <BaseCheckbox
              v-model="selectAllFieldStatus"
              variant="primary"
              @change="supplierStore.selectAllSuppliers"
            />
          </div>
        </template>

        <template #cell-status="{ row }">
          <div class="relative block">
            <BaseCheckbox
              :id="row.data.id"
              v-model="selectField"
              :value="row.data.id"
              variant="primary"
            />
          </div>
        </template>

        <template #cell-name="{ row }">
          <router-link :to="{ path: `suppliers/${row.data.id}/view` }">
            <BaseText
              :text="row.data.name"
              :length="30"
              tag="span"
              class="font-medium text-primary-500 flex flex-col"
            />
            <BaseText
              :text="row.data.contact_name ? row.data.contact_name : ''"
              :length="30"
              tag="span"
              class="text-xs text-gray-400"
            />
          </router-link>
        </template>

        <template #cell-category_name="{ row }">
          <span>
            {{ row.data.category ? row.data.category.name+" ("+row.data.category.code+")" : '-' }}
          </span>
        </template>

        <template #cell-phone="{ row }">
          <span>
            {{ row.data.phone ? row.data.phone : '-' }}
          </span>
        </template>

        <template #cell-due_amount="{ row }">
          <BaseFormatMoney
            :amount="row.data.due_amount || 0"
            :currency="row.data.currency"
          />
        </template>

        <template #cell-created_at="{ row }">
          <span>{{ row.data.formatted_created_at }}</span>
        </template>

        <template v-if="hasAtleastOneAbility()" #cell-actions="{ row }">
          <CustomerDropdown
            :row="row.data"
            :table="tableComponent"
            :load-data="refreshTable"
          />
        </template>
      </BaseTable>
    </div>
  </BasePage>
</template>

<script setup>
import { debouncedWatch } from '@vueuse/core'
import moment from 'moment'
import { reactive, ref, inject, computed, onUnmounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useSupplierStore } from '@/scripts/stores/supplier'
import { useDialogStore } from '@/scripts/stores/dialog'
import { useCompanyStore } from '@/scripts/stores/company'
import { useUserStore } from '@/scripts/stores/user'

import abilities from '@/scripts/stub/abilities'

import CustomerDropdown from '@/scripts/components/dropdowns/SupplierIndexDropdown.vue'
import AstronautIcon from '@/scripts/components/icons/empty/AstronautIcon.vue'

const companyStore = useCompanyStore()
const dialogStore = useDialogStore()
const supplierStore = useSupplierStore()
const userStore = useUserStore()


let tableComponent = ref(null)
const filteredDataCount = ref(true)
let showFilters = ref(false)
let isFetchingInitialData = ref(true)
const { t } = useI18n()

let filters = reactive({
  display_name: '',
  contact_name: '',
  phone: '',
})

const showEmptyScreen = computed(
  () => !supplierStore.totalSuppliers && !isFetchingInitialData.value
)

const selectField = computed({
  get: () => supplierStore.selectedSuppliers,
  set: (value) => {
    return supplierStore.selectedSupplier(value)
  },
})

const selectAllFieldStatus = computed({
  get: () => supplierStore.selectAllField,
  set: (value) => {
    return supplierStore.setSelectAllState(value)
  },
})

const supplierColumns = computed(() => {
  return [
    {
      key: 'status',
      thClass: 'extra w-10 pr-0',
      sortable: false,
      tdClass: 'font-medium text-gray-900 pr-0',
    },
    {
      key: 'name',
      label: t('customers.name'),
      thClass: 'extra',
      tdClass: 'font-medium text-gray-900',
    },
    {
      key: 'category_name',
      label: t('categories.name'),
      thClass: 'extra',
      tdClass: 'font-medium text-gray-900',
    },
    { key: 'phone', label: t('customers.phone') },
    { key: 'email', label: t('customers.email') },
    {
      key: 'created_at',
      label: t('items.added_on'),
    },
    {
      key: 'actions',
      tdClass: 'text-right text-sm font-medium pl-0',
      thClass: 'pl-0',
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
  if (supplierStore.selectAllField) {
    supplierStore.selectAllSuppliers()
  }
})

function refreshTable() {
  tableComponent.value.refresh()
}

function setFilters() {
  refreshTable()
}

function hasAtleastOneAbility() {
  return userStore.hasAbilities([
    abilities.DELETE_CUSTOMER,
    abilities.EDIT_CUSTOMER,
    abilities.VIEW_CUSTOMER,
  ])
}

async function fetchData({ page, filter, sort }) {
  let data = {
    display_name: filters.display_name,
    contact_name: filters.contact_name,
    phone: filters.phone,
    orderByField: sort.fieldName || 'created_at',
    orderBy: sort.order || 'desc',
    page,
  }

  isFetchingInitialData.value = true
  let response = await supplierStore.fetchSuppliers(data)
  isFetchingInitialData.value = false
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

function clearFilter() {
  filters.display_name = ''
  filters.contact_name = ''
  filters.phone = ''
}

function toggleFilter() {
  if (showFilters.value) {
    clearFilter()
  }

  showFilters.value = !showFilters.value
}

let date = ref(new Date())

date.value = moment(date).format('YYYY-MM-DD')

function removeMultipleSuppliers() {
  dialogStore
    .openDialog({
      title: t('general.are_you_sure'),
      message: t('customers.confirm_delete', 2),
      yesLabel: t('general.ok'),
      noLabel: t('general.cancel'),
      variant: 'danger',
      hideNoButton: false,
      size: 'lg',
    })
    .then((res) => {
      if (res) {
        supplierStore.deleteMultipleSuppliers().then((response) => {
          if (response.data) {
            refreshTable()
          }
        })
      }
    })
}
</script>
