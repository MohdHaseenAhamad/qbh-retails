<template>
  <NumberCustomizer
    type="credit"
    :type-store="creditStore"
    default-series="CREDIT"
  />
</template>

<script setup>
import { useCreditStore } from '@/scripts/stores/credit'
import NumberCustomizer from '../NumberCustomizer.vue'

const creditStore = useCreditStore()
</script>

