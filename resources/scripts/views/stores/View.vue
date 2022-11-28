<template>
  <BasePage class="xl:pl-96"> 
    <BasePageHeader :title="pageTitle">
      <template #actions>
        <router-link
          v-if="userStore.hasAbilities(abilities.EDIT_STORE)"
          :to="`/admin/stores/${route.params.id}/edit`"
        >
          <BaseButton
            class="mr-3"
            variant="primary-outline"
            :content-loading="isLoading"
          >
            {{ $t('general.edit') }}
          </BaseButton>
        </router-link>

        <CustomerDropdown
          v-if="hasAtleastOneAbility()"
          :class="{
            'ml-3': isLoading,
          }"
          :row="supplierStore.selectedViewSupplier"
          :load-data="refreshData"
        />
      </template>
    </BasePageHeader>

    <!-- Customer View Sidebar -->
    <StoreViewSidebar />

    <!-- Chart -->
    <StoreChart />
  </BasePage>
</template>

<script setup>
import StoreViewSidebar from './partials/StoreViewSidebar.vue'
import StoreChart from './partials/StoreChart.vue'
import { ref, computed, inject } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useStore } from '@/scripts/stores/store'
import { useDialogStore } from '@/scripts/stores/dialog'
import { useUserStore } from '@/scripts/stores/user'
import CustomerDropdown from '@/scripts/components/dropdowns/StoreIndexDropdown.vue'
import abilities from '@/scripts/stub/abilities'

const utils = inject('utils')
const dialogStore = useDialogStore()
const supplierStore = useStore()
const userStore = useUserStore()
const { t } = useI18n()

const router = useRouter()
const route = useRoute()
const customer = ref(null)

const pageTitle = computed(() => {
  return supplierStore.selectedViewSupplier.supplier
    ? supplierStore.selectedViewSupplier.supplier.name
    : ''
})

let isLoading = computed(() => {
  return supplierStore.isFetchingViewData
})

function canCreateTransaction() {
  return userStore.hasAbilities([
    abilities.CREATE_ESTIMATE,
    abilities.CREATE_INVOICE,
    abilities.CREATE_PAYMENT,
    abilities.CREATE_EXPENSE,
  ])
}

function hasAtleastOneAbility() {
  return userStore.hasAbilities([
    abilities.DELETE_CUSTOMER,
    abilities.EDIT_CUSTOMER,
  ])
}

function refreshData() {
  router.push('/admin/stores')
}
</script>
