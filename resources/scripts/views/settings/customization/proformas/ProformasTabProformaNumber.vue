<template>
  <NumberCustomizer
    type="proforma"
    :type-store="proformaStore"
    default-series="PRO"
  />
</template>

<script setup>
import { useProformaStore } from '@/scripts/stores/proforma'
import NumberCustomizer from '../NumberCustomizer.vue'

const proformaStore = useProformaStore()
</script>

