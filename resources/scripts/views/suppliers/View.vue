<template>
  <BasePage class="xl:pl-96"> 
    <BasePageHeader :title="pageTitle">
      <template #actions>
        <router-link
          v-if="userStore.hasAbilities(abilities.EDIT_CUSTOMER)"
          :to="`/admin/suppliers/${route.params.id}/edit`"
        >
          <BaseButton
            class="mr-3"
            variant="primary-outline"
            :content-loading="isLoading"
          >
            {{ $t('general.edit') }}
          </BaseButton>
        </router-link>

        <BaseDropdown v-show="false"
          v-if="canCreateTransaction()"
          position="bottom-end"
          :content-loading="isLoading"
        >
          <template #activator>
            <BaseButton
              class="mr-3"
              variant="primary"
              :content-loading="isLoading"
            >
              {{ $t('customers.new_transaction') }}
            </BaseButton>
          </template>

          <router-link
            v-if="userStore.hasAbilities(abilities.CREATE_ESTIMATE)"
            :to="`/admin/estimates/create?supplier=${$route.params.id}`"
          >
            <BaseDropdownItem class="">
              <BaseIcon name="DocumentIcon" class="mr-3 text-gray-600" />
              {{ $t('estimates.new_estimate') }}
            </BaseDropdownItem>
          </router-link>

          <router-link
            v-if="userStore.hasAbilities(abilities.CREATE_INVOICE)"
            :to="`/admin/invoices/create?supplier=${$route.params.id}`"
          >
            <BaseDropdownItem>
              <BaseIcon name="DocumentTextIcon" class="mr-3 text-gray-600" />
              {{ $t('invoices.new_invoice') }}
            </BaseDropdownItem>
          </router-link>

          <router-link
            v-if="userStore.hasAbilities(abilities.CREATE_PAYMENT)"
            :to="`/admin/payments/create?supplier=${$route.params.id}`"
          >
            <BaseDropdownItem>
              <BaseIcon name="CreditCardIcon" class="mr-3 text-gray-600" />
              {{ $t('payments.new_payment') }}
            </BaseDropdownItem>
          </router-link>

          <router-link
            v-if="userStore.hasAbilities(abilities.CREATE_EXPENSE)"
            :to="`/admin/expenses/create?supplier=${$route.params.id}`"
          >
            <BaseDropdownItem>
              <BaseIcon name="CalculatorIcon" class="mr-3 text-gray-600" />
              {{ $t('expenses.new_expense') }}
            </BaseDropdownItem>
          </router-link>
        </BaseDropdown>

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
    <CustomerViewSidebar />

    <!-- Chart -->
    <CustomerChart />
  </BasePage>
</template>

<script setup>
import CustomerViewSidebar from './partials/CustomerViewSidebar.vue'
import CustomerChart from './partials/CustomerChart.vue'
import { ref, computed, inject } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useSupplierStore } from '@/scripts/stores/supplier'
import { useDialogStore } from '@/scripts/stores/dialog'
import { useUserStore } from '@/scripts/stores/user'
import CustomerDropdown from '@/scripts/components/dropdowns/SupplierIndexDropdown.vue'
import abilities from '@/scripts/stub/abilities'

const utils = inject('utils')
const dialogStore = useDialogStore()
const supplierStore = useSupplierStore()
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
  router.push('/admin/suppliers')
}
</script>
