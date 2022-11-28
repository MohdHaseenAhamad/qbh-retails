<template>
  <form @submit.prevent="submitForm">
    <h6 class="text-gray-900 text-lg font-medium">
      {{ $t('settings.customization.proformas.default_formats') }}
    </h6>
    <p class="mt-1 text-sm text-gray-500 mb-2">
      {{ $t('settings.customization.proformas.default_formats_description') }}
    </p>

    <BaseInputGroup
      :label="$t('settings.customization.proformas.default_proforma_email_body')"
      class="mt-6 mb-4"
    >
      <BaseCustomInput
        v-model="formatSettings.proforma_mail_body"
        :fields="proformaMailFields"
      />
    </BaseInputGroup>

    <BaseInputGroup
      :label="$t('settings.customization.proformas.company_address_format')"
      class="mt-6 mb-4"
    >
      <BaseCustomInput
        v-model="formatSettings.proforma_company_address_format"
        :fields="companyFields"
      />
    </BaseInputGroup>

    <BaseInputGroup
      :label="$t('settings.customization.proformas.shipping_address_format')"
      class="mt-6 mb-4"
    >
      <BaseCustomInput
        v-model="formatSettings.proforma_shipping_address_format"
        :fields="shippingFields"
      />
    </BaseInputGroup>

    <BaseInputGroup
      :label="$t('settings.customization.proformas.billing_address_format')"
      class="mt-6 mb-4"
    >
      <BaseCustomInput
        v-model="formatSettings.proforma_billing_address_format"
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

const proformaMailFields = ref([
  'customer',
  'customerCustom',
  'proforma',
  'proformaCustom',
  'company',
])

const billingFields = ref([
  'billing',
  'customer',
  'customerCustom',
  'proformaCustom',
])

const shippingFields = ref([
  'shipping',
  'customer',
  'customerCustom',
  'proformaCustom',
])

const companyFields = ref(['company', 'proformaCustom'])

let isSaving = ref(false)

const formatSettings = reactive({
  proforma_mail_body: null,
  proforma_company_address_format: null,
  proforma_shipping_address_format: null,
  proforma_billing_address_format: null,
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
    message: 'settings.customization.proformas.proforma_settings_updated',
  })

  isSaving.value = false

  return true
}
</script>
