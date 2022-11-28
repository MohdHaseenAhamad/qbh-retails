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
            :title="$tc('debitnote.debitnotes', 2)"
            to="/admin/debit-note"
          />
          <BaseBreadcrumbItem
            v-if="$route.name === 'purchases.edit'"
            :title="$t('debitnote.edit')"
            to="#"
            active
          />
          <BaseBreadcrumbItem
            v-else
            :title="$t('debitnote.new')"
            to="#"
            active
          />
        </BaseBreadcrumb>

        <template #actions>
          <router-link
            v-if="$route.name === 'debit.edit'"
            :to="`/debits/pdf/${debitStore.newPurchase.unique_hash}`"
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
            {{ $t('debitnote.save') }}
          </BaseButton>
        </template>
        
      </BasePageHeader>

      <!-- Select Customer & Basic Fields  -->
      <DebitNoteBasicFields
        :v="v$"
        :is-loading="isLoadingContent"
        :is-edit="isEdit"
      />

      <BaseScrollPane>
        <!-- Invoice Items -->
        <InvoiceItems
          :currency="debitStore.newPurchase.selectedCurrency"
          :is-loading="isLoadingContent"
          :item-validation-scope="purchaseValidationScope"
          :store="debitStore"
          store-prop="newPurchase"
          :is-draft-mode="isDraftMode"
          
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
              :store="debitStore"
              store-prop="newPurchase"
              :fields="invoiceNoteFieldList"
              type="Debit"
              :class="(debitStore.newPurchase.is_edit ? (debitStore.newPurchase.is_edit == '1' ? '' : 'disable') : '')"
            />
          </div>

          <InvoiceTotal
            :currency="debitStore.newPurchase.selectedCurrency"
            :is-loading="isLoadingContent"
            :store="debitStore"
            store-prop="newPurchase"
            tax-popup-type="invoice"
            :class="(debitStore.newPurchase.is_edit ? (debitStore.newPurchase.is_edit == '1' ? '' : 'disable') : '')"
          />
        </div>
        <BaseInputGrid class="sm:grid-cols-4 md:grid-cols-4 col-span-12 mb-4">
          <!-- Invoice Template Button-->
          <SelectTemplate
            :store="debitStore"
            store-prop="newPurchase"
            component-name="InvoiceTemplate"
          />
          <BaseInputGroup
            :label="$t('invoices.upper_margin')"
            :content-loading="isLoadingContent"
          >
            <BaseInput
              v-model="debitStore.newPurchase.upper_margin"
              :content-loading="isLoadingContent"
            />
          </BaseInputGroup>
          <BaseInputGroup
            :label="$t('invoices.lower_margin')"
            :content-loading="isLoadingContent"
          >
            <BaseInput
              v-model="debitStore.newPurchase.lower_margin"
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
          {{ $t('debitnote.save') }}
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

import { useDebitStore } from '@/scripts/stores/debit'
import { useCompanyStore } from '@/scripts/stores/company'
import { usePreparedByStore } from '@/scripts/stores/prepared-by'
import { useCustomFieldStore } from '@/scripts/stores/custom-field'

import InvoiceItems from '@/scripts/components/estimate-invoice-common/CreateItems.vue'
import InvoiceTotal from '@/scripts/components/estimate-invoice-common/CreateTotal.vue'
import SelectTemplate from '@/scripts/components/estimate-invoice-common/SelectTemplateButton.vue'
import DebitNoteBasicFields from './DebitNoteCreateBasicFields.vue'
import NoteFields from '@/scripts/components/estimate-invoice-common/CreateNotesField.vue'
import SelectTemplateModal from '@/scripts/components/modal-components/SelectTemplateModal.vue'
import TaxTypeModal from '@/scripts/components/modal-components/TaxTypeModal.vue'
import ItemModal from '@/scripts/components/modal-components/ItemModal.vue'
import PreparedByModal from '@/scripts/components/modal-components/PreparedByModal.vue'

const debitStore = useDebitStore()
const companyStore = useCompanyStore()
const customFieldStore = useCustomFieldStore()
const preparedByStore = usePreparedByStore()
const { t } = useI18n()
let route = useRoute()
let router = useRouter()

const purchaseValidationScope = 'newPurchase'
let isSaving = ref(false)
const isDraftMode = computed(() => {
  return debitStore.newPurchase.is_edit == 1 ? true : false
})

const invoiceNoteFieldList = ref([
  'customer',
  'company',
  'customerCustom',
  'invoice',
  'invoiceCustom',
])

let isLoadingContent = computed(
  () => debitStore.isFetchingInvoice || debitStore.isFetchingInitialSettings
)

let pageTitle = computed(() =>
  isEdit.value ? t('debitnote.edit') : t('debitnote.create')
)

let isEdit = computed(() => route.name === 'debit.edit')

const rules = {
  debit_date: {
    required: helpers.withMessage(t('validation.required'), required),
  },
  debit_number: {
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
  exchange_rate: {
    required: requiredIf(function () {
      helpers.withMessage(t('validation.required'), required)
      return debitStore.showExchangeRate
    }),
    decimal: helpers.withMessage(t('validation.valid_exchange_rate'), decimal),
  },
}

const v$ = useVuelidate(
  rules,
  computed(() => debitStore.newPurchase),
  { $scope: purchaseValidationScope }
)

customFieldStore.resetCustomFields()
v$.value.$reset
debitStore.resetCurrentPurchase()
debitStore.fetchPurchaseInitialSettings(true)

watch(
  () => debitStore.newPurchase.customer,
  (newVal) => {
    if (newVal && newVal.currency) {
      debitStore.newPurchase.selectedCurrency = newVal.currency
    } else {
      debitStore.newPurchase.selectedCurrency =
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
    ...debitStore.newPurchase,
    sub_total: debitStore.getSubTotal,
    total: debitStore.getTotal,
    tax: debitStore.getTotalTax,
  }

  try {
    const action = route.name == 'debit.edit'
      ? debitStore.updateDebitNote
      : debitStore.addDebitNote

    const response = await action(data)
    router.push(`/admin/debit-note/${response.data.data.id}/view`)
  } catch (err) {
    console.error(err)
  }

  isSaving.value = false
}
</script>
