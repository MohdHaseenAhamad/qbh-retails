<template>
  <SelectTemplateModal />
  <ItemModal />
  <PreparedByModal />
  <TaxTypeModal />

  <BasePage class="relative invoice-create-page">
    <form @submit.prevent="submitForm">
      <BasePageHeader :title="pageTitle">
        <BaseBreadcrumb>
          <BaseBreadcrumbItem
            :title="$t('general.home')"
            to="/admin/dashboard"
          />
          <BaseBreadcrumbItem
            :title="$tc('purchases.purchase', 2)"
            to="/admin/purchases"
          />
          <BaseBreadcrumbItem
            v-if="$route.name === 'purchases.edit'"
            :title="$t('purchases.edit_purchase')"
            to="#"
            active
          />
          <BaseBreadcrumbItem
            v-else
            :title="$t('purchases.new_purchase')"
            to="#"
            active
          />
        </BaseBreadcrumb>

        <template #actions>
          <router-link
            v-if="$route.name === 'purchase.edit'"
            :to="`/purchases/pdf/${purchaseStore.newPurchase.unique_hash}`"
            target="_blank"
          >
            <BaseButton class="mr-3" variant="primary-outline" type="button">
              <span class="flex">
                {{ $t('general.view_pdf') }}
              </span>
            </BaseButton>
          </router-link>

          <BaseButton
            :loading="isSaving"
            :disabled="isSaving"
            variant="primary"
            type="submit"
          >
            <template #left="slotProps">
              <BaseIcon
                v-if="!isSaving"
                name="SaveIcon"
                :class="slotProps.class"
              />
            </template>
            {{ $t('purchases.save_purchase') }}
          </BaseButton>
        </template>
        
      </BasePageHeader>

      <!-- Select Customer & Basic Fields  -->
      <PurchaseBasicFields
        :v="v$"
        :is-loading="isLoadingContent"
        :is-edit="isEdit"
      />

      <BaseScrollPane>
        <!-- Invoice Items -->
        <InvoiceItems
          :currency="purchaseStore.newPurchase.selectedCurrency"
          :is-loading="isLoadingContent"
          :item-validation-scope="purchaseValidationScope"
          :store="purchaseStore"
          store-prop="newPurchase"
          
        />

        <!-- Invoice Footer Section -->
        <div
          class="
            block
            mt-10
            invoice-foot
            lg:flex lg:justify-between lg:items-start
          "
        >
          <div class="relative w-full lg:w-1/2 lg:mr-4">
            <!-- Invoice Custom Notes -->
            <NoteFields
              :store="purchaseStore"
              store-prop="newPurchase"
              :fields="invoiceNoteFieldList"
              type="Purchase"
            />
          </div>

          <InvoiceTotal
            :currency="purchaseStore.newPurchase.selectedCurrency"
            :is-loading="isLoadingContent"
            :store="purchaseStore"
            store-prop="newPurchase"
            tax-popup-type="invoice"
          />
        </div>
        <BaseInputGrid class="col-span-12">
          
          <!-- Invoice Template Button-->
          <!-- <SelectTemplate
            :store="purchaseStore"
            store-prop="newPurchase"
            component-name="InvoiceTemplate"
          /> -->
          <BaseInputGroup
            :label="$t('invoices.upper_margin')"
            :content-loading="isLoadingContent"
          >
            <BaseInput
              v-model="purchaseStore.newPurchase.upper_margin"
              :content-loading="isLoadingContent"
            />
          </BaseInputGroup>
          <BaseInputGroup
            :label="$t('invoices.lower_margin')"
            :content-loading="isLoadingContent"
          >
            <BaseInput
              v-model="purchaseStore.newPurchase.lower_margin"
              :content-loading="isLoadingContent"
            />
          </BaseInputGroup>
        </BaseInputGrid>

        <br>

        <BaseButton
          :loading="isSaving"
          :disabled="isSaving"
          variant="primary"
          type="submit"
        >
          <template #left="slotProps">
            <BaseIcon
              v-if="!isSaving"
              name="SaveIcon"
              :class="slotProps.class"
            />
          </template>
          {{ $t('purchases.save_purchase') }}
        </BaseButton>

      </BaseScrollPane>
    </form>
  </BasePage>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import {
  required,
  maxLength,
  helpers,
  requiredIf,
  decimal,
} from '@vuelidate/validators'
import useVuelidate from '@vuelidate/core'

import { usePurchaseStore } from '@/scripts/stores/purchase'
import { useCompanyStore } from '@/scripts/stores/company'
import { usePreparedByStore } from '@/scripts/stores/prepared-by'
import { useCustomFieldStore } from '@/scripts/stores/custom-field'

import InvoiceItems from '@/scripts/components/estimate-invoice-common/CreateItems.vue'
import InvoiceTotal from '@/scripts/components/estimate-invoice-common/CreateTotal.vue'
import SelectTemplate from '@/scripts/components/estimate-invoice-common/SelectTemplateButton.vue'
import PurchaseBasicFields from './PurchaseCreateBasicFields.vue'
import NoteFields from '@/scripts/components/estimate-invoice-common/CreateNotesField.vue'
import SelectTemplateModal from '@/scripts/components/modal-components/SelectTemplateModal.vue'
import TaxTypeModal from '@/scripts/components/modal-components/TaxTypeModal.vue'
import ItemModal from '@/scripts/components/modal-components/ItemModal.vue'
import PreparedByModal from '@/scripts/components/modal-components/PreparedByModal.vue'

const purchaseStore = usePurchaseStore()
const companyStore = useCompanyStore()
const customFieldStore = useCustomFieldStore()
const preparedByStore = usePreparedByStore()
const { t } = useI18n()
let route = useRoute()
let router = useRouter()

const purchaseValidationScope = 'newPurchase'
let isSaving = ref(false)

const invoiceNoteFieldList = ref([
  'customer',
  'company',
  'customerCustom',
  'invoice',
  'invoiceCustom',
])

let isLoadingContent = computed(
  () => purchaseStore.isFetchingPurchase || purchaseStore.isFetchingInitialSettings
)

let pageTitle = computed(() =>
  isEdit.value ? t('purchases.edit_purchase') : t('purchases.new_purchase')
)

let isEdit = computed(() => route.name === 'purchases.edit')

const rules = {
  invoice_no: {
    required: helpers.withMessage(t('validation.required'), required),
  },
  invoice_date: {
    required: helpers.withMessage(t('validation.required'), required),
  },
  reference_number: {
    maxLength: helpers.withMessage(
      t('validation.price_maxlength'),
      maxLength(255)
    ),
  },
  customer_id: {
    required: helpers.withMessage(t('validation.required'), required),
  },
  purchase_no: {
    required: helpers.withMessage(t('validation.required'), required),
  },
  purchase_date: {
    required: helpers.withMessage(t('validation.required'), required),
  },
  exchange_rate: {
    required: requiredIf(function () {
      helpers.withMessage(t('validation.required'), required)
      return purchaseStore.showExchangeRate
    }),
    decimal: helpers.withMessage(t('validation.valid_exchange_rate'), decimal),
  },
}

const v$ = useVuelidate(
  rules,
  computed(() => purchaseStore.newPurchase),
  { $scope: purchaseValidationScope }
)

customFieldStore.resetCustomFields()
v$.value.$reset
purchaseStore.resetCurrentPurchase()
purchaseStore.fetchPurchaseInitialSettings(isEdit.value)

watch(
  () => purchaseStore.newPurchase.customer,
  (newVal) => {
    if (newVal && newVal.currency) {
      purchaseStore.newPurchase.selectedCurrency = newVal.currency
    } else {
      purchaseStore.newPurchase.selectedCurrency =
        companyStore.selectedCompanyCurrency
    }
  }
)

function searchVal(val) {
  syncPreparedByToStore()
}


async function submitForm() {
  // alert('working')
  v$.value.$touch()

  if (v$.value.$invalid) {
    return false
  }

  isSaving.value = true

  let data = {
    ...purchaseStore.newPurchase,
    sub_total: purchaseStore.getSubTotal,
    total: purchaseStore.getTotal,
    tax: purchaseStore.getTotalTax,
  }

  try {
    const action = isEdit.value
      ? purchaseStore.updatePurchase
      : purchaseStore.addPurchase
    const response = await action(data)
    router.push(`/admin/purchases/${response.data.data.id}/view`)
  } catch (err) {
    console.error(err)
  }

  isSaving.value = false
}
</script>
