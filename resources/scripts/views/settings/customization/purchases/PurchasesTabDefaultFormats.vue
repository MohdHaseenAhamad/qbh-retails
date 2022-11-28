<template>
  <form @submit.prevent="submitForm">
    <h6 class="text-gray-900 text-lg font-medium">
      {{ $t('settings.customization.purchases.default_formats') }}
    </h6>
    <p class="mt-1 text-sm text-gray-500 mb-2">
      {{ $t('settings.customization.purchases.default_formats_description') }}
    </p>

    <BaseInputGroup
      :label="$t('settings.customization.purchases.default_purchases_email_body')"
      class="mt-6 mb-4"
    >
      <BaseCustomInput
        v-model="formatSettings.purchases_mail_body"
        :fields="purchasesMailFields"
      />
    </BaseInputGroup>

    <BaseInputGroup
      :label="$t('settings.customization.purchases.company_address_format')"
      class="mt-6 mb-4"
    >
      <BaseCustomInput
        v-model="formatSettings.purchases_company_address_format"
        :fields="companyFields"
      />
    </BaseInputGroup>

    <BaseInputGroup
      :label="$t('settings.customization.purchases.shipping_address_format')"
      class="mt-6 mb-4"
    >
      <BaseCustomInput
        v-model="formatSettings.purchases_shipping_address_format"
        :fields="shippingFields"
      />
    </BaseInputGroup>

    <BaseInputGroup
      :label="$t('settings.customization.purchases.billing_address_format')"
      class="mt-6 mb-4"
    >
      <BaseCustomInput
        v-model="formatSettings.purchases_billing_address_format"
        :fields="billingFields"
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

const purchasesMailFields = ref([
  'customer',
  'customerCustom',
  'purchases',
  'purchasesCustom',
  'company',
])

const billingFields = ref([
  'billing',
  'customer',
  'customerCustom',
  'purchasesCustom',
])

const shippingFields = ref([
  'shipping',
  'customer',
  'customerCustom',
  'purchasesCustom',
])

const companyFields = ref(['company', 'purchasesCustom'])

let isSaving = ref(false)

const formatSettings = reactive({
  purchases_mail_body: null,
  purchases_company_address_format: null,
  purchases_shipping_address_format: null,
  purchases_billing_address_format: null,
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
    message: 'settings.customization.purchases.purchases_settings_updated',
  })

  isSaving.value = false

  return true
}
</script>
