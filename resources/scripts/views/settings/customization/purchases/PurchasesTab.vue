<template>
  <PurchasesTabPurchaseNumber />  
</template>

<script setup>
import { computed, reactive, inject } from 'vue'
import { useCompanyStore } from '@/scripts/stores/company'
import PurchasesTabPurchaseNumber from './PurchasesTabPurchaseNumber.vue'
import PurchasesTabRetrospective from './PurchasesTabRetrospective.vue'
import PurchasesTabDueDate from './PurchasesTabDueDate.vue'
import PurchasesTabDefaultFormats from './PurchasesTabDefaultFormats.vue'

const utils = inject('utils')
const companyStore = useCompanyStore()

const purchasesSettings = reactive({
  purchases_email_attachment: null,
})

utils.mergeSettings(purchasesSettings, {
  ...companyStore.selectedCompanySettings,
})

const sendAsAttachmentField = computed({
  get: () => {
    return purchasesSettings.purchases_email_attachment === 'YES'
  },
  set: async (newValue) => {
    const value = newValue ? 'YES' : 'NO'

    let data = {
      settings: {
        purchases_email_attachment: value,
      },
    }

    purchasesSettings.purchases_email_attachment = value

    await companyStore.updateCompanySettings({
      data,
      message: 'general.setting_updated',
    })
  },
})
</script>
