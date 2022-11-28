<template>
  <div class="grid grid-cols-12 gap-8 mt-6 mb-8">

    <BaseInputGrid class="col-span-12 sm:grid-cols-4 md:grid-cols-4">
      <BaseInputGroup
          :label="$t('invoices.client')"
          :content-loading="isLoading"
          required
        >

        <BaseCustomerSelectPopup
          v-model="estimateStore.newEstimate.customer"
          :valid="v.customer_id"
          :content-loading="isLoading"
          type="estimate"
          class="col-span-5 pr-0"
        />
      </BaseInputGroup>
      <BaseInputGroup
        :label="$t('reports.estimates.estimate_date')"
        :content-loading="isLoading"
        required
        :error="v.estimate_date.$error && v.estimate_date.$errors[0].$message"
      >
        <BaseDatePicker
          v-model="estimateStore.newEstimate.estimate_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('estimates.expiry_date')"
        :content-loading="isLoading"
        required
        :error="v.expiry_date.$error && v.expiry_date.$errors[0].$message"
      >
        <BaseDatePicker
          v-model="estimateStore.newEstimate.expiry_date"
          :content-loading="isLoading"
          :calendar-button="true"
          calendar-button-icon="calendar"
        />
      </BaseInputGroup>

      <BaseInputGroup
        :label="$t('estimates.estimate_number')"
        :content-loading="isLoading"
        required
        :error="v.estimate_number.$error && v.estimate_number.$errors[0].$message"
      >
        <BaseInput
          v-model="estimateStore.newEstimate.estimate_number"
          :content-loading="isLoading"
          disabled
        >
        </BaseInput>
      </BaseInputGroup>

      <!-- <BaseInputGroup
        :label="$t('estimates.ref_number')"
        :content-loading="isLoading"
        :error="
          v.reference_number.$error && v.reference_number.$errors[0].$message
        "
      >
        <BaseInput
          v-model="estimateStore.newEstimate.reference_number"
          :content-loading="isLoading"
          @input="v.reference_number.$touch()"
        >
          <template #left="slotProps">
            <BaseIcon name="HashtagIcon" :class="slotProps.class" />
          </template>
        </BaseInput>
      </BaseInputGroup> -->
      <BaseInputGroup
        :label="$t('estimates.prepared_by')"
        :content-loading="isLoading"
      >
        <BasePreparedBySelect
          type="Estimate"
          :store-prop="newInvoice"
          :store="estimateStore"
          :content-loading="isLoading"
          @select="onSelectPreparedBy"
          :item="estimateStore.newEstimate.prepared_by_id"
        />
      </BaseInputGroup>
      <BaseInputGroup
              :label="$t('invoices.bank_Detail')"
              :content-loading="isLoading"
            >
            <BaseBankDetailSelect
              type="Estimate"
              :store-prop="newEstimate"
              :store="estimateStore"
              :content-loading="isLoading"
              @select="onSelectBankDetail"
              :item="estimateStore.newEstimate.bank_detail_id"
            />
          </BaseInputGroup>
              <BaseInputGroup
              :label="$t('estimates.upper_margin')"
              :content-loading="isLoadingContent"
            >
              <BaseInput
                v-model="estimateStore.newEstimate.upper_margin"
                :content-loading="isLoadingContent"
              />
            </BaseInputGroup>
            <BaseInputGroup
              :label="$t('estimates.lower_margin')"
              :content-loading="isLoadingContent"
            >
              <BaseInput
                v-model="estimateStore.newEstimate.lower_margin"
                :content-loading="isLoadingContent"
              />
            </BaseInputGroup>
            
      <ExchangeRateConverter
        :store="estimateStore"
        store-prop="newEstimate"
        :v="v"
        :is-loading="isLoading"
        :is-edit="isEdit"
        :customer-currency="estimateStore.newEstimate.currency_id"
      />
    </BaseInputGrid>
  </div>
</template>

<script setup>
import { useEstimateStore } from '@/scripts/stores/estimate'
import ExchangeRateConverter from '@/scripts/components/estimate-invoice-common/ExchangeRateConverter.vue'

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

const estimateStore = useEstimateStore()
function onSelectPreparedBy(itm) {
  estimateStore.newEstimate.prepared_by_id = itm
}
function onSelectBankDetail(itm) {
  estimateStore.newEstimate.bank_detail_id = itm
}
</script>
