<template>
  <NumberCustomizer
    type="purchase"
    :type-store="purchaseStore"
    default-series="PUR"
  />
</template>

<script setup>
import { usePurchaseStore } from '@/scripts/stores/purchase'
import NumberCustomizer from '../NumberCustomizer.vue'

const purchaseStore = usePurchaseStore()
</script>

