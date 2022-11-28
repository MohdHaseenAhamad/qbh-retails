<template>
  <div class="grid grid-cols-12 gap-8 mt-6 mb-8">

    <BaseInputGrid class="col-span-12 sm:grid-cols-4 md:grid-cols-4">

      <BaseInputGroup
          :label="$t('debits.supplier')"
          :content-loading="isLoading"
          required
        >
        <BaseCustomerSelectPopup
          v-model="purchaseStore.newPurchase.customer"
          :valid="v.customer_id"
          :content-loading="isLoading"
          type="purchase"
          class="col-span-12 lg:col-span-5 pr-0"
        />
      </BaseInputGroup>
      <BaseInputGroup
        :label="$t('purchases.number')"
        :content-loading="isLoading"
        :error="v.purchase_no.$error && v.purchase_no.$errors[0].$message"
        required
      >
        <BaseInput
        disabled
          v-model="purchaseStore.newPurchase.purchase_no"
          :content-loading="isLoading"
          @input="v.purchase_no.$touch()"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('purchases.date')"
        :content-loading="isLoading"
        required
        :error="v.purchase_date.$error && v.purchase_date.$errors[0].$message"
      >
        <BaseDatePicker
          v-model="purchaseStore.newPurchase.purchase_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('purchases.invoice_no')"
        :content-loading="isLoading"
        :error="v.invoice_no.$error && v.invoice_no.$errors[0].$message"
        required
      >
        <BaseInput
          v-model="purchaseStore.newPurchase.invoice_no"
          :content-loading="isLoading"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('purchases.invoice_date')"
        :content-loading="isLoading"
        required
        :error="v.invoice_date.$error && v.invoice_date.$errors[0].$message"
      >
        <BaseDatePicker
          v-model="purchaseStore.newPurchase.invoice_date"
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
          v-model="purchaseStore.newPurchase.order_no"
          :content-loading="isLoading"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('purchases.order_date')"
        :content-loading="isLoading"
      >
        <BaseDatePicker
          v-model="purchaseStore.newPurchase.order_date"
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
          v-model="purchaseStore.newPurchase.material_receipt"
          :content-loading="isLoading"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('purchases.supply_date')"
        :content-loading="isLoading"
      >
        <BaseDatePicker
          v-model="purchaseStore.newPurchase.supply_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('purchases.reference_number')"
        :content-loading="isLoading"
      >
        <BaseInput
          v-model="purchaseStore.newPurchase.reference_number"
          :content-loading="isLoading"
        />
      </BaseInputGroup>

      <ExchangeRateConverter
        :store="purchaseStore"
        store-prop="newPurchase"
        :v="v"
        :is-loading="isLoading"
        :is-edit="isEdit"
        :customer-currency="purchaseStore.newPurchase.currency_id"
      />

    </BaseInputGrid>
  </div>
</template>

<script setup>
import ExchangeRateConverter from '@/scripts/components/estimate-invoice-common/ExchangeRateConverter.vue'
import { usePurchaseStore } from '@/scripts/stores/purchase'

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

const purchaseStore = usePurchaseStore()

function onSelectPreparedBy(itm) {
  purchaseStore.newPurchase.prepared_by_id = itm.id
}
</script>
