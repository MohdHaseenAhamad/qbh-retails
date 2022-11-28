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
            :title="$t('creditnote.title')"
            to="/admin/credit-note"
          />
          <BaseBreadcrumbItem
            :title="$t('creditnote.new')"
            to="#"
            active
          />
        </BaseBreadcrumb>

        <template #actions>
          <router-link
            v-if="$route.name === 'invoices.edit'"
            :to="`/invoices/pdf/${creditStore.newInvoice.unique_hash}`"
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
            {{ $t('creditnote.save') }}
          </BaseButton>
        </template>
        
      </BasePageHeader>

      <!-- Select Customer & Basic Fields  -->
      <CreditNoteCreateBasicFields
        :v="v$"
        :is-loading="isLoadingContent"
        :is-edit="isEdit"
      />

      <BaseScrollPane>
        <!-- Invoice Items -->
        <InvoiceItems
          :currency="creditStore.newInvoice.selectedCurrency"
          :is-loading="isLoadingContent"
          :item-validation-scope="invoiceValidationScope"
          :store="creditStore"
          :is-draft-mode="isDraftMode"
          store-prop="newInvoice"
          
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
              :store="creditStore"
              store-prop="newInvoice"
              :fields="invoiceNoteFieldList"
              type="Credit"
              :class="(creditStore.newInvoice.is_edit_credit ? (creditStore.newInvoice.is_edit_credit == '1' ? '' : 'disable') : '')"
            />
          </div>

          <InvoiceTotal
            :currency="creditStore.newInvoice.selectedCurrency"
            :is-loading="isLoadingContent"
            :store="creditStore"
            store-prop="newInvoice"
            tax-popup-type="invoice"
            :class="(creditStore.newInvoice.is_edit_credit ? (creditStore.newInvoice.is_edit_credit == '1' ? '' : 'disable') : '')"
          />
        </div>
        <BaseInputGrid class="sm:grid-cols-4 md:grid-cols-4 col-span-12 mb-4">
          <!-- Invoice Template Button-->
          <SelectTemplate
            :store="creditStore"
            store-prop="newInvoice"
            component-name="InvoiceTemplate"
          />
          <BaseInputGroup
            :label="$t('invoices.upper_margin')"
            :content-loading="isLoadingContent"
          >
            <BaseInput
              v-model="creditStore.newInvoice.upper_margin"
              :content-loading="isLoadingContent"
            />
          </BaseInputGroup>
          <BaseInputGroup
            :label="$t('invoices.lower_margin')"
            :content-loading="isLoadingContent"
          >
            <BaseInput
              v-model="creditStore.newInvoice.lower_margin"
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
          {{ $t('creditnote.save') }}
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

import { useInvoiceStore } from '@/scripts/stores/invoice'
import { useCreditStore } from '@/scripts/stores/credit'
import { useCompanyStore } from '@/scripts/stores/company'
import { usePreparedByStore } from '@/scripts/stores/prepared-by'
import { useCustomFieldStore } from '@/scripts/stores/custom-field'

import InvoiceItems from '@/scripts/components/estimate-invoice-common/CreateItems.vue'
import InvoiceTotal from '@/scripts/components/estimate-invoice-common/CreateTotal.vue'
import SelectTemplate from '@/scripts/components/estimate-invoice-common/SelectTemplateButton.vue'
import CreditNoteCreateBasicFields from './CreditNoteCreateBasicFields.vue'
import InvoiceBasicFields from '../../invoices/create/InvoiceCreateBasicFields.vue'
import NoteFields from '@/scripts/components/estimate-invoice-common/CreateNotesField.vue'
import SelectTemplateModal from '@/scripts/components/modal-components/SelectTemplateModal.vue'
import TaxTypeModal from '@/scripts/components/modal-components/TaxTypeModal.vue'
import ItemModal from '@/scripts/components/modal-components/ItemModal.vue'
import PreparedByModal from '@/scripts/components/modal-components/PreparedByModal.vue'

const creditStore = useCreditStore()
const invoiceStore = useInvoiceStore()
const companyStore = useCompanyStore()
const customFieldStore = useCustomFieldStore()
const preparedByStore = usePreparedByStore()
const { t } = useI18n()
let route = useRoute()
let router = useRouter()

const invoiceValidationScope = 'newInvoice'
let isSaving = ref(false)

const isDraftMode = computed(() => {
  return creditStore.newInvoice.is_edit_credit == 1 ? true : false
})

const invoiceNoteFieldList = ref([
  'customer',
  'company',
  'customerCustom',
  'invoice',
  'invoiceCustom',
])

let isLoadingContent = computed(
  () => creditStore.isFetchingInvoice || creditStore.isFetchingInitialSettings
)

let pageTitle = computed(() =>
  isEdit.value ? t('creditnote.edit') : t('creditnote.create')
)

let isEdit = computed(() => route.name === 'credit.edit')

const rules = {
  credit_date: {
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
  credit_number: {
    required: helpers.withMessage(t('validation.required'), required),
  },
  exchange_rate: {
    required: requiredIf(function () {
      helpers.withMessage(t('validation.required'), required)
      return creditStore.showExchangeRate
    }),
    decimal: helpers.withMessage(t('validation.valid_exchange_rate'), decimal),
  },
}

const v$ = useVuelidate(
  rules,
  computed(() => creditStore.newInvoice),
  { $scope: invoiceValidationScope }
)

customFieldStore.resetCustomFields()
v$.value.$reset
creditStore.resetCurrentInvoice()
creditStore.fetchInvoiceInitialSettings(true)

watch(
  () => creditStore.newInvoice.customer,
  (newVal) => {
    if (newVal && newVal.currency) {
      creditStore.newInvoice.selectedCurrency = newVal.currency
    } else {
      creditStore.newInvoice.selectedCurrency =
        companyStore.selectedCompanyCurrency
    }
  }
)

function searchVal(val) {
  syncPreparedByToStore()
}


async function submitForm() {
  v$.value.$touch()


  if (v$.value.$invalid) {
    return false
  }

  isSaving.value = true

  let data = {
    ...creditStore.newInvoice,
    sub_total: creditStore.getSubTotal,
    total: creditStore.getTotal,
    tax: creditStore.getTotalTax,
    prepared_by_id:creditStore.newInvoice.prepared_by_id ? creditStore.newInvoice.prepared_by_id.id : '',
  }

  try {
    const action = isEdit.value
      ? creditStore.updateCreditNote
      : creditStore.addCreditNote

    const response = await action(data)

    router.push(`/admin/credit-note/${response.data.data.id}/view`)
  } catch (err) {
    console.error(err)
  }

  isSaving.value = false
}
</script>
