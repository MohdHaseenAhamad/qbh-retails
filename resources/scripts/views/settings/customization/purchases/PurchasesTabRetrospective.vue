<template>
  <h6 class="text-gray-900 text-lg font-medium">
    {{ $tc('settings.customization.purchases.retrospective_edits') }}
  </h6>
  <p class="mt-1 text-sm text-gray-500">
    {{ $t('settings.customization.purchases.retrospective_edits_description') }}
  </p>

  <BaseInputGroup required>
    <BaseRadio
      id="allow"
      v-model="settingsForm.retrospective_edits"
      :label="$t('settings.customization.purchases.allow')"
      size="sm"
      name="filter"
      value="allow"
      class="mt-2"
      @update:modelValue="submitForm"
    />

    <BaseRadio
      id="disable_on_purchases_partial_paid"
      v-model="settingsForm.retrospective_edits"
      :label="
        $t('settings.customization.purchases.disable_on_purchases_partial_paid')
      "
      size="sm"
      name="filter"
      value="disable_on_purchases_partial_paid"
      class="mt-2"
      @update:modelValue="submitForm"
    />
    <BaseRadio
      id="disable_on_purchases_paid"
      v-model="settingsForm.retrospective_edits"
      :label="$t('settings.customization.purchases.disable_on_purchases_paid')"
      size="sm"
      name="filter"
      value="disable_on_purchases_paid"
      class="my-2"
      @update:modelValue="submitForm"
    />
    <BaseRadio
      id="disable_on_purchases_sent"
      v-model="settingsForm.retrospective_edits"
      :label="$t('settings.customization.purchases.disable_on_purchases_sent')"
      size="sm"
      name="filter"
      value="disable_on_purchases_sent"
      @update:modelValue="submitForm"
    />
  </BaseInputGroup>
</template>

<script setup>
import { reactive, computed, ref, inject } from 'vue'
import { useCompanyStore } from '@/scripts/stores/company'
import { useI18n } from 'vue-i18n'
import { useGlobalStore } from '@/scripts/stores/global'

const { t, tm } = useI18n()
const companyStore = useCompanyStore()
const globalStore = useGlobalStore()
const utils = inject('utils')

const settingsForm = reactive({ retrospective_edits: null })

utils.mergeSettings(settingsForm, {
  ...companyStore.selectedCompanySettings,
})

const retrospectiveEditOptions = computed(() => {
  return globalStore.config.retrospective_edits.map((option) => {
    option.title = t(option.key)
    return option
  })
})

async function submitForm() {
  let data = {
    settings: {
      ...settingsForm,
    },
  }

  await companyStore.updateCompanySettings({
    data,
    message: 'settings.customization.purchases.purchases_settings_updated',
  })

  return true
}
</script>
