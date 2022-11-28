<template>
  <div class="grid grid-cols-12 gap-8 mt-6 mb-8">
    <BaseInputGrid class="col-span-12 sm:grid-cols-4 md:grid-cols-4">
      <BaseInputGroup
          :label="$t('invoices.client')"
          :content-loading="isLoading"
          required
        >
          <BaseCustomerSelectPopup
            v-model="proformaStore.newProforma.customer"
            :valid="v.customer_id"
            :content-loading="isLoading"
            type="proforma"
            class="col-span-12 lg:col-span-5 pr-0"
          />
        </BaseInputGroup>

      <BaseInputGroup
        :label="$t('proformas.proforma_date')"
        :content-loading="isLoading"
        required
        :error="v.proforma_date.$error && v.proforma_date.$errors[0].$message"
      >
        <BaseDatePicker
          v-model="proformaStore.newProforma.proforma_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
          disabled
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('proformas.due_date')"
        :content-loading="isLoading"
        required
        :error="v.due_date.$error && v.due_date.$errors[0].$message"
      >
        <BaseDatePicker
          v-model="proformaStore.newProforma.due_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('proformas.proforma_number')"
        :content-loading="isLoading"
        :error="v.proforma_number.$error && v.proforma_number.$errors[0].$message"
        required
      >
        <BaseInput
        disabled
          v-model="proformaStore.newProforma.proforma_number"
          :content-loading="isLoading"
          @input="v.proforma_number.$touch()"
        />
      </BaseInputGroup>
      <BaseInputGroup
        :label="$t('proformas.prepared_by')"
        :content-loading="isLoading"
      >
        <BasePreparedBySelect
          type="Proforma"
          :store-prop="newProforma"
          :store="proformaStore"
          :content-loading="isLoading"
          @select="onSelectPreparedBy"
          :item="proformaStore.newProforma.prepared_by_id"
        />
      </BaseInputGroup>
      <BaseInputGroup
        :label="$t('invoices.bank_Detail')"
        :content-loading="isLoading"
      >
            <BaseBankDetailSelect
              type="Proforma"
              :store-prop="newProforma"
              :store="proformaStore"
              :content-loading="isLoading"
              @select="onSelectBankDetail"
              :item="proformaStore.newProforma.bank_detail_id"
            />
          </BaseInputGroup>

          <BaseInputGroup
            :label="$t('proformas.upper_margin')"
            :content-loading="isLoadingContent"
          >
            <BaseInput
              v-model="proformaStore.newProforma.upper_margin"
              :content-loading="isLoadingContent"
            />
          </BaseInputGroup>
          <BaseInputGroup
            :label="$t('proformas.lower_margin')"
            :content-loading="isLoadingContent"
          >
            <BaseInput
              v-model="proformaStore.newProforma.lower_margin"
              :content-loading="isLoadingContent"
            />
          </BaseInputGroup>
      <ExchangeRateConverter
        :store="proformaStore"
        store-prop="newProforma"
        :v="v"
        :is-loading="isLoading"
        :is-edit="isEdit"
        :customer-currency="proformaStore.newProforma.currency_id"
      />
    </BaseInputGrid>
  </div>
</template>

<script setup>
import ExchangeRateConverter from '@/scripts/components/estimate-invoice-common/ExchangeRateConverter.vue'
import { useProformaStore } from '@/scripts/stores/proforma'

const props = defineProps({
  v: {
    type: Object,
    default: null,
  },
  isLoading: {
    type: Boolean,
    default: false,
  },
  isEdit: {
    type: Boolean,
    default: false,
  },
})

const proformaStore = useProformaStore()

function onSelectPreparedBy(itm) {
  proformaStore.newProforma.prepared_by_id = itm
}
function onSelectBankDetail(itm) {
  proformaStore.newProforma.bank_detail_id = itm
}
</script>
