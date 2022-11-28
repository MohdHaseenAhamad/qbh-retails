<template>
  <div class="grid grid-cols-12 gap-8 mt-6 mb-8">

    <BaseInputGrid  class="col-span-12 sm:grid-cols-4 md:grid-cols-4">
    <BaseInputGroup
          :label="$t('debits.supplier')"
          :content-loading="isLoading"
          required
        >
      <BaseCustomerSelectPopup
        v-model="debitStore.newPurchase.customer"
        :valid="v.customer_id"
        :content-loading="isLoading"
        type="debit"
        class="col-span-12 lg:col-span-5 pr-0"
      />
      </BaseInputGroup>
      <BaseInputGroup
        :label="$t('debits.number')"
        :content-loading="isLoading"
        :error="v.debit_number.$error && v.debit_number.$errors[0].$message"
        required
      >
        <BaseInput
          disabled
          v-model="debitStore.newPurchase.debit_number"
          :content-loading="isLoading"
          @input="v.debit_number.$touch()"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('debits.date')"
        :content-loading="isLoading"
        required
        :error="v.debit_date.$error && v.debit_date.$errors[0].$message"
      >
        <BaseDatePicker
          disabled
          v-model="debitStore.newPurchase.debit_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
          enableTime="true"
          time24hr="true"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('purchases.reference_number')"
        :content-loading="isLoading"
      >
        <BaseInput
          v-model="debitStore.newPurchase.reference_number"
          :content-loading="isLoading"
          :disabled="(debitStore.newPurchase.is_edit ? (debitStore.newPurchase.is_edit == '1' ? false : true) : false)"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('debits.reason')"
        :content-loading="isLoading"
      >
        <BaseInput
          v-model="debitStore.newPurchase.reason"
          :content-loading="isLoading"
          :disabled="(debitStore.newPurchase.is_edit ? (debitStore.newPurchase.is_edit == '1' ? false : true) : false)"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('purchases.number')"
        :content-loading="isLoading"
        required
      >
        <BaseInput
        disabled
          v-model="debitStore.newPurchase.purchase_no"
          :content-loading="isLoading"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('purchases.date')"
        :content-loading="isLoading"
        required
      >
        <BaseDatePicker
          disabled
          v-model="debitStore.newPurchase.purchase_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('purchases.invoice_no')"
        :content-loading="isLoading"
        required
      >
        <BaseInput
          disabled
          v-model="debitStore.newPurchase.invoice_no"
          :content-loading="isLoading"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('purchases.invoice_date')"
        :content-loading="isLoading"
        required
      >
        <BaseDatePicker
          disabled
          v-model="debitStore.newPurchase.invoice_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('purchases.order_no')"
        :content-loading="isLoading"
      >
        <BaseInput
          disabled
          v-model="debitStore.newPurchase.order_no"
          :content-loading="isLoading"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('purchases.order_date')"
        :content-loading="isLoading"
      >
        <BaseDatePicker
          disabled
          v-model="debitStore.newPurchase.order_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('purchases.material_receipt_no')"
        :content-loading="isLoading"
      >
        <BaseInput
          disabled
          v-model="debitStore.newPurchase.material_receipt"
          :content-loading="isLoading"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('purchases.supply_date')"
        :content-loading="isLoading"
      >
        <BaseDatePicker
          disabled
          v-model="debitStore.newPurchase.supply_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
        />
      </BaseInputGroup>

      <ExchangeRateConverter
        :store="debitStore"
        store-prop="newPurchase"
        :v="v"
        :is-loading="isLoading"
        :is-edit="isEdit"
        :customer-currency="debitStore.newPurchase.currency_id"
      />

    </BaseInputGrid>
  </div>
</template>

<script setup>
import ExchangeRateConverter from '@/scripts/components/estimate-invoice-common/ExchangeRateConverter.vue'
import { useDebitStore } from '@/scripts/stores/debit'

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

const debitStore = useDebitStore()

function onSelectPreparedBy(itm) {
  debitStore.newPurchase.prepared_by_id = itm
}
</script>
<style scope>
.disable{
  pointer-events: none;
}
</style>
