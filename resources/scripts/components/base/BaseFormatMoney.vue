<template>
  <span style="font-family: sans-serif">{{ formattedAmount }}</span>
</template>

<script setup>
import { useCompanyStore } from '@/scripts/stores/company'
import { inject, computed } from 'vue'

const props = defineProps({
  amount: {
    type: [Number, String],
    required: true,
  },
  currency: {
    type: [Object, String],
    default: () => {
      return null
    },
  },
})

const utils = inject('utils')

const companyStore = useCompanyStore()

const formattedAmount = computed(() => {
  return utils.formatMoney(
    props.amount,
    props.currency == false ? (props.currency || companyStore.selectedCompanyCurrency) : 0
  )
})
</script>
