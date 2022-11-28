<template>
  <NumberCustomizer
    type="debit"
    :type-store="debitStore"
    default-series="DEBIT"
  />
</template>

<script setup>
import { useDebitStore } from '@/scripts/stores/debit'
import NumberCustomizer from '../NumberCustomizer.vue'

const debitStore = useDebitStore()
</script>

