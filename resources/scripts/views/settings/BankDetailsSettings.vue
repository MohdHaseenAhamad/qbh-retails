<template>
  <BaseSettingCard
    :title="$t('settings.bankdetail.title')"
  >
  <BankDetailModal />

    <template v-if="userStore.hasAbilities(abilities.CREATE_TAX_TYPE)" #action>
      <BaseButton type="submit" variant="primary-outline" @click="openBankDetailModal">
        <template #left="slotProps">
          <BaseIcon :class="slotProps.class" name="PlusIcon" />
        </template>
        {{ $t('settings.bankdetail.add_new_bank_detail') }}
      </BaseButton>
    </template>

    <BaseTable
      ref="table"
      class="mt-16"
      :data="fetchData"
      :columns="bankDetailColumns"
    >
      <template #cell-account_name="{ row }"> {{ row.data.account_name }}</template>
      <template #cell-account_no="{ row }"> {{ row.data.account_no }}</template>
      <template #cell-bank_name="{ row }"> {{ row.data.bank_name }}</template>
      <template v-if="hasAtleastOneAbility()" #cell-actions="{ row }">
        <BankDetailDropdown
          :row="row.data"
          :table="table"
          :load-data="refreshTable"
        />
      </template>
    </BaseTable>
  </BaseSettingCard>
</template>

<script setup>
import { useBankDetailStore } from '@/scripts/stores/bank-detail'
import { useModalStore } from '@/scripts/stores/modal'
import { computed, reactive, ref, inject } from 'vue'
import { useI18n } from 'vue-i18n'
import { useCompanyStore } from '@/scripts/stores/company'
import { useUserStore } from '@/scripts/stores/user'
import BankDetailModal from '@/scripts/components/modal-components/BankDetailModal.vue'
import abilities from '@/scripts/stub/abilities'
import BankDetailDropdown from '@/scripts/components/dropdowns/BankDetailIndexDropdown.vue'

const { t } = useI18n()
const utils = inject('utils')

const companyStore = useCompanyStore()
const bankDetailStore = useBankDetailStore()
const modalStore = useModalStore()
const userStore = useUserStore()

const table = ref(null)
const taxPerItemSetting = ref(companyStore.selectedCompanySettings.tax_per_item)

const bankDetailColumns = computed(() => {
  return [
    {
      key: 'account_name',
      label: t('settings.bankdetail.account_name'),
      thClass: 'extra',
      tdClass: 'font-medium text-gray-900',
    },
    {
      key: 'account_no',
      label: t('settings.bankdetail.account_no'),
      tdClass: 'font-medium text-gray-900',
    },
    {
      key: 'bank_name',
      label: t('settings.bankdetail.bank_name'),
      thClass: 'extra',
      tdClass: 'font-medium text-gray-900',
    },
    {
      key: 'actions',
      label: '',
      tdClass: 'text-right text-sm font-medium',
      sortable: false,
    },
  ]
})

const taxPerItemField = computed({
  get: () => {
    return taxPerItemSetting.value === 'YES'
  },
  set: async (newValue) => {
    const value = newValue ? 'YES' : 'NO'

    let data = {
      settings: {
        tax_per_item: value,
      },
    }

    taxPerItemSetting.value = value

    await companyStore.updateCompanySettings({
      data,
      message: 'general.setting_updated',
    })
  },
})

function hasAtleastOneAbility() {
  return userStore.hasAbilities([
    abilities.DELETE_TAX_TYPE,
    abilities.EDIT_TAX_TYPE,
  ])
}

async function fetchData({ page, filter, sort }) {
  let data = {
    orderByField: sort.fieldName || 'created_at',
    page,
  }

  let response = await bankDetailStore.fetchBankDetails(data)

  return {
    data: response.data.data,
    pagination: {
      totalPages: response.data.meta.last_page,
      currentPage: page,
      totalCount: response.data.meta.total,
      limit: 5,
    },
  }
}

async function refreshTable() {
  table.value && table.value.refresh()
}

function openBankDetailModal() {
  modalStore.openModal({
    title: t('settings.bankdetail.add_new_bank_detail'),
    componentName: 'BankDetailModal',
    size: 'sm',
    refreshData: table.value && table.value.refresh,
  })
}
</script>
