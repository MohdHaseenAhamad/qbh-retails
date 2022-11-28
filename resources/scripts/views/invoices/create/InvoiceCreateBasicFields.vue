<template>
  <div class="grid grid-cols-12 gap-8 mt-6 mb-8">

    <BaseInputGrid class="col-span-12 sm:grid-cols-4 md:grid-cols-4">
      <BaseInputGroup
          :label="$t('invoices.client')"
          :content-loading="isLoading"
          required
        >
        <BaseCustomerSelectPopup
          v-model="invoiceStore.newInvoice.customer"
          :valid="v.customer_id"
          :content-loading="isLoading"
          type="invoice"
        />
      </BaseInputGroup>
      <BaseInputGroup
        :label="$t('invoices.invoice_date')"
        :content-loading="isLoading"
        required
        :error="v.invoice_date.$error && v.invoice_date.$errors[0].$message"
      >
        <BaseDatePicker
          v-model="invoiceStore.newInvoice.invoice_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
          disabled
          enableTime="true"
          time24hr="true"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('invoices.due_date')"
        :content-loading="isLoading"
        required
        :error="v.due_date.$error && v.due_date.$errors[0].$message"
      >
        <BaseDatePicker
          v-model="invoiceStore.newInvoice.due_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
          :disabled="(invoiceStore.newInvoice.is_edit ? (invoiceStore.newInvoice.is_edit == '1' ? false : true) : false)"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('invoices.invoice_number')"
        :content-loading="isLoading"
        :error="v.invoice_number.$error && v.invoice_number.$errors[0].$message"
        required
      >
        <BaseInput
        disabled
          v-model="invoiceStore.newInvoice.invoice_number"
          :content-loading="isLoading"
          @input="v.invoice_number.$touch()"
        />
      </BaseInputGroup>
      <BaseInputGroup
        :class="(invoiceStore.newInvoice.is_edit ? (invoiceStore.newInvoice.is_edit == '1' ? '' : 'disable') : '')"
        :label="$t('invoices.prepared_by')"
        :content-loading="isLoading"
      >
        <BasePreparedBySelect
          type="Invoice"
          :store-prop="newInvoice"
          :store="invoiceStore"
          :content-loading="isLoading"
          @select="onSelectPreparedBy"
          :item="invoiceStore.newInvoice.prepared_by_id"
        />
      </BaseInputGroup>
      <BaseInputGroup
          :label="$t('invoices.bank_Detail')"
          :content-loading="isLoading"
          :class="(invoiceStore.newInvoice.is_edit ? (invoiceStore.newInvoice.is_edit == '1' ? '' : 'disable') : '')"
        >
        <BaseBankDetailSelect
          type="Invoice"
          :store-prop="newInvoice"
          :store="invoiceStore"
          :content-loading="isLoading"
          @select="onSelectBankDetail"
          :item="invoiceStore.newInvoice.bank_detail_id"
        />
      </BaseInputGroup>
      <BaseInputGroup
        :label="$t('invoices.upper_margin')"
        :content-loading="isLoading"
      >
        <BaseInput
          v-model="invoiceStore.newInvoice.upper_margin"
          :content-loading="isLoading"
        />
      </BaseInputGroup>
      <BaseInputGroup
        :label="$t('invoices.lower_margin')"
        :content-loading="isLoading"
      >
        <BaseInput
          v-model="invoiceStore.newInvoice.lower_margin"
          :content-loading="isLoading"
        />
      </BaseInputGroup>
      <ExchangeRateConverter
      :store="invoiceStore"
      store-prop="newInvoice"
      :v="v"
      :is-loading="isLoading"
      :is-edit="isEdit"
      :customer-currency="invoiceStore.newInvoice.currency_id"
      />
    </BaseInputGrid>
  </div>
</template>

<script setup>
import ExchangeRateConverter from '@/scripts/components/estimate-invoice-common/ExchangeRateConverter.vue'
import { useInvoiceStore } from '@/scripts/stores/invoice'

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

const invoiceStore = useInvoiceStore()

function onSelectPreparedBy(itm) {
  invoiceStore.newInvoice.prepared_by_id = itm
}
function onSelectBankDetail(itm) {
  invoiceStore.newInvoice.bank_detail_id = itm
}
</script>
<style scope>
.disable{
  pointer-events: none;
}
</style>
