<template>
  <ProformasTabProformaNumber />

  <BaseDivider class="my-8" />

  <ProformasTabDefaultFormats />

  <BaseDivider class="mt-6 mb-2" />

  <ul class="divide-y divide-gray-200">
    <BaseSwitchSection
      v-model="sendAsAttachmentField"
      :title="$t('settings.customization.proformas.proforma_email_attachment')"
      :description="
        $t(
          'settings.customization.proformas.proforma_email_attachment_setting_description'
        )
      "
    />
  </ul>
</template>

<script setup>
import { computed, reactive, inject } from 'vue'
import { useCompanyStore } from '@/scripts/stores/company'
import ProformasTabProformaNumber from './ProformasTabProformaNumber.vue'
import ProformasTabDefaultFormats from './ProformasTabDefaultFormats.vue'

const utils = inject('utils')
const companyStore = useCompanyStore()

const proformaSettings = reactive({
  proforma_email_attachment: null,
})

utils.mergeSettings(proformaSettings, {
  ...companyStore.selectedCompanySettings,
})

const sendAsAttachmentField = computed({
  get: () => {
    return proformaSettings.proforma_email_attachment === 'YES'
  },
  set: async (newValue) => {
    const value = newValue ? 'YES' : 'NO'

    let data = {
      settings: {
        proforma_email_attachment: value,
      },
    }

    proformaSettings.proforma_email_attachment = value

    await companyStore.updateCompanySettings({
      data,
      message: 'general.setting_updated',
    })
  },
})
</script>
