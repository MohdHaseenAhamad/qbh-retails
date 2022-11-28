<template>
  <SelectTemplateModal />
  <ItemModal />
  <PreparedByModal />
  <BankDetailModal />
  <TaxTypeModal />

  <BasePage class="relative proforma-create-page">
    <form @submit.prevent="submitForm">
      <BasePageHeader :title="pageTitle">
        <BaseBreadcrumb>
          <BaseBreadcrumbItem
            :title="$t('general.home')"
            to="/admin/dashboard"
          />
          <BaseBreadcrumbItem
            :title="$tc('proformas.proforma', 2)"
            to="/admin/proformas"
          />
          <BaseBreadcrumbItem
            v-if="$route.name === 'proformas.edit'"
            :title="$t('proformas.edit_proforma')"
            to="#"
            active
          />
          <BaseBreadcrumbItem
            v-else
            :title="$t('proformas.new_proforma')"
            to="#"
            active
          />
        </BaseBreadcrumb>

        <template #actions>
          <router-link
            v-if="$route.name === 'proformas.edit'"
            :to="`/proformas/pdf/${proformaStore.newProforma.unique_hash}`"
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
            {{ $t('proformas.save_proforma') }}
          </BaseButton>
        </template>
        
      </BasePageHeader>

      <!-- Select Customer & Basic Fields  -->
      <ProformaBasicFields
        :v="v$"
        :is-loading="isLoadingContent"
        :is-edit="isEdit"
      />

      <BaseScrollPane>
        <!-- Invoice Items -->
        <InvoiceItems
          :currency="proformaStore.newProforma.selectedCurrency"
          :is-loading="isLoadingContent"
          :item-validation-scope="proformaValidationScope"
          :store="proformaStore"
          store-prop="newProforma"
          
        />
        <!-- Proforma Footer Section -->
        <div
          class="
            block
            mt-10
            proforma-foot
            lg:flex lg:justify-between lg:items-start
          "
        >
          <div class="relative w-full lg:w-1/2 lg:mr-4">
            <!-- Proforma Custom Notes -->
            <NoteFields
              :store="proformaStore"
              store-prop="newProforma"
              :fields="proformaNoteFieldList"
              type="Invoice"
            />
          </div>

          <InvoiceTotal
            :currency="proformaStore.newProforma.selectedCurrency"
            :is-loading="isLoadingContent"
            :store="proformaStore"
            store-prop="newProforma"
            tax-popup-type="invoice"
          />
        </div>
        <BaseInputGrid class="sm:grid-cols-4 md:grid-cols-4 col-span-12 mb-4">
          <!-- Invoice Custom Fields -->
            <InvoiceCustomFields
              type="Proforma"
              :is-edit="isEdit"
              :is-loading="isLoadingContent"
              :store="proformaStore"
              store-prop="newProforma"
              :custom-field-scope="proformaValidationScope"
              class="mb-6"
            />
          <!-- Proforma Template Button-->
          <SelectTemplate
            :store="proformaStore"
            store-prop="newProforma"
            component-name="ProformaTemplate"
          />
        </BaseInputGrid>
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
            {{ $t('proformas.save_proforma') }}
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

import { useProformaStore } from '@/scripts/stores/proforma'
import { useCompanyStore } from '@/scripts/stores/company'
import { usePreparedByStore } from '@/scripts/stores/prepared-by'
import { useCustomFieldStore } from '@/scripts/stores/custom-field'

import InvoiceItems from '@/scripts/components/estimate-invoice-common/CreateItems.vue'
import InvoiceTotal from '@/scripts/components/estimate-invoice-common/CreateTotal.vue'
import SelectTemplate from '@/scripts/components/estimate-invoice-common/SelectTemplateButton.vue'
import ProformaBasicFields from './ProformaCreateBasicFields.vue'
import InvoiceCustomFields from '@/scripts/components/custom-fields/CreateCustomFields.vue'
import NoteFields from '@/scripts/components/estimate-invoice-common/CreateNotesField.vue'
import SelectTemplateModal from '@/scripts/components/modal-components/SelectTemplateModal.vue'
import TaxTypeModal from '@/scripts/components/modal-components/TaxTypeModal.vue'
import ItemModal from '@/scripts/components/modal-components/ItemModal.vue'
import PreparedByModal from '@/scripts/components/modal-components/PreparedByModal.vue'
import BankDetailModal from '@/scripts/components/modal-components/BankDetailModal.vue'

const proformaStore = useProformaStore()
const companyStore = useCompanyStore()
const customFieldStore = useCustomFieldStore()
const preparedByStore = usePreparedByStore()
const { t } = useI18n()
let route = useRoute()
let router = useRouter()

const proformaValidationScope = 'newProforma'
let isSaving = ref(false)

const proformaNoteFieldList = ref([
  'customer',
  'company',
  'customerCustom',
  'proforma',
  'proformaCustom',
])

let isLoadingContent = computed(
  () => proformaStore.isFetchingProforma || proformaStore.isFetchingInitialSettings
)

let pageTitle = computed(() =>
  isEdit.value ? t('proformas.edit_proforma') : t('proformas.new_proforma')
)

let isEdit = computed(() => route.name === 'proformas.edit')

const rules = {
  proforma_date: {
    required: helpers.withMessage(t('validation.required'), required),
  },
  due_date: {
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
  proforma_number: {
    required: helpers.withMessage(t('validation.required'), required),
  },
  exchange_rate: {
    required: requiredIf(function () {
      helpers.withMessage(t('validation.required'), required)
      return proformaStore.showExchangeRate
    }),
    decimal: helpers.withMessage(t('validation.valid_exchange_rate'), decimal),
  },
}

const v$ = useVuelidate(
  rules,
  computed(() => proformaStore.newProforma),
  { $scope: proformaValidationScope }
)

customFieldStore.resetCustomFields()
v$.value.$reset
proformaStore.resetCurrentProforma()
proformaStore.fetchProformaInitialSettings(isEdit.value)

watch(
  () => proformaStore.newProforma.customer,
  (newVal) => {
    if (newVal && newVal.currency) {
      proformaStore.newProforma.selectedCurrency = newVal.currency
    } else {
      proformaStore.newProforma.selectedCurrency =
        companyStore.selectedCompanyCurrency
    }
  }
)

function searchVal(val) {
  syncPreparedByToStore()
}


async function submitForm() {
  console.log('working')
  v$.value.$touch()

  if (v$.value.$invalid) {
    return false
  }

  isSaving.value = true

  let data = {
    ...proformaStore.newProforma,
    sub_total: proformaStore.getSubTotal,
    total: proformaStore.getTotal,
    tax: proformaStore.getTotalTax,
    prepared_by_id:proformaStore.newProforma.prepared_by_id ? proformaStore.newProforma.prepared_by_id.id : '',
    bank_detail_id:proformaStore.newProforma.bank_detail_id ? proformaStore.newProforma.bank_detail_id.id : ''
  }

  try {
    const action = isEdit.value
      ? proformaStore.updateProforma
      : proformaStore.addProforma
    const response = await action(data)
    router.push(`/admin/proformas/${response.data.data.id}/view`)
  } catch (err) {
    console.error(err)
  }

  isSaving.value = false
}
</script>
