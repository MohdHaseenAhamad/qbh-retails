<template>
  <div class="grid grid-cols-12 gap-8 mt-6 mb-8">

    <BaseInputGrid class="col-span-12 sm:grid-cols-4 md:grid-cols-4">
      <BaseInputGroup
            :label="$t('invoices.client')"
            :content-loading="isLoading"
            required
          >
        <BaseCustomerSelectPopup
          v-model="creditStore.newInvoice.customer"
          :valid="v.customer_id"
          :content-loading="isLoading"
          type="credit"
          class="col-span-12 lg:col-span-5 pr-0"
        />
      </BaseInputGroup>
      <BaseInputGroup
        :label="$t('invoices.invoice_date')"
        :content-loading="isLoading"
      >
        <BaseDatePicker
          disabled
          v-model="creditStore.newInvoice.invoice_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
          enableTime="true"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('credits.credit_note_date')"
        :content-loading="isLoading"
        required
        :error="v.credit_date.$error && v.credit_date.$errors[0].$message"
      >
        <BaseDatePicker
          disabled
          v-model="creditStore.newInvoice.credit_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
          enableTime="true"
          time24hr="true"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('invoices.invoice_number')"
        :content-loading="isLoading"
      >
        <BaseInput
          disabled
          v-model="creditStore.newInvoice.invoice_number"
          :content-loading="isLoading"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('credits.credit_note_number')"
        :content-loading="isLoading"
        :error="v.credit_number.$error && v.credit_number.$errors[0].$message"
        required
      >
        <BaseInput
          disabled
          v-model="creditStore.newInvoice.credit_number"
          :content-loading="isLoading"
          @input="v.credit_number.$touch()"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('invoices.prepared_by')"
        :content-loading="isLoading"
      >
        <BasePreparedBySelect
          type="Invoice"
          :store-prop="newInvoice"
          :store="creditStore"
          :content-loading="isLoading"
          @select="onSelectPreparedBy"
          :item="creditStore.newInvoice.prepared_by_id"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('creditnote.reason')"
        :content-loading="isLoading"
      >
        <BaseInput
          v-model="creditStore.newInvoice.reason"
          :content-loading="isLoading"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('Reference')"
        :content-loading="isLoading"
      >
        <BaseInput
          v-model="creditStore.newInvoice.reference_number"
          :content-loading="isLoading"
        />
      </BaseInputGroup>

      <ExchangeRateConverter
        :store="creditStore"
        store-prop="newInvoice"
        :v="v"
        :is-loading="isLoading"
        :is-edit="isEdit"
        :customer-currency="creditStore.newInvoice.currency_id"
      />
    </BaseInputGrid>
  </div>
</template>

<script setup>
import ExchangeRateConverter from '@/scripts/components/estimate-invoice-common/ExchangeRateConverter.vue'
import { useCreditStore } from '@/scripts/stores/credit'

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

const creditStore = useCreditStore()

function onSelectPreparedBy(itm) {
  creditStore.newInvoice.prepared_by_id = itm
}
</script>
<style scope>
.disable{
  pointer-events: none;
}
</style>
