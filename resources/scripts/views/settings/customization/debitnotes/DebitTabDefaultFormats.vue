<template>
  <form @submit.prevent="submitForm">
    <h6 class="text-gray-900 text-lg font-medium">
      {{ $t('settings.customization.debits.default_formats') }}
    </h6>
    <p class="mt-1 text-sm text-gray-500 mb-2">
      {{ $t('settings.customization.debits.default_formats_description') }}
    </p>

    <BaseInputGroup
      :label="$t('settings.customization.debits.default_credit_email_body')"
      class="mt-6 mb-4"
    >
      <BaseCustomInput
        v-model="formatSettings.invoice_mail_body"
        :fields="invoiceMailFields"
      />
    </BaseInputGroup>

    <BaseButton
      :loading="isSaving"
      :disabled="isSaving"
      variant="primary"
      type="submit"
      class="mt-4"
    >
      <template #left="slotProps">
        <BaseIcon v-if="!isSaving" :class="slotProps.class" name="SaveIcon" />
      </template>
      {{ $t('settings.customization.save') }}
    </BaseButton>
  </form>
</template>

<script setup>
import { ref, reactive, inject } from 'vue'
import { useCompanyStore } from '@/scripts/stores/company'

const companyStore = useCompanyStore()
const utils = inject('utils')

const invoiceMailFields = ref([
  'customer',
  'customerCustom',
  'invoice',
  'invoiceCustom',
  'company',
])

const billingFields = ref([
  'billing',
  'customer',
  'customerCustom',
  'invoiceCustom',
])

const shippingFields = ref([
  'shipping',
  'customer',
  'customerCustom',
  'invoiceCustom',
])

const companyFields = ref(['company', 'invoiceCustom'])

let isSaving = ref(false)

const formatSettings = reactive({
  invoice_mail_body: null,
  invoice_company_address_format: null,
  invoice_shipping_address_format: null,
  invoice_billing_address_format: null,
})

utils.mergeSettings(formatSettings, {
  ...companyStore.selectedCompanySettings,
})

async function submitForm() {
  isSaving.value = true

  let data = {
    settings: {
      ...formatSettings,
    },
  }

  await companyStore.updateCompanySettings({
    data,
    message: 'settings.customization.debits.debit_settings_updated',
  })

  isSaving.value = false

  return true
}
</script>
