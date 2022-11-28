<template>
  <PaymentModeModal />

  <BasePage class="relative payment-create">
    <form action="" @submit.prevent="submitPaymentData">
        
        <BasePageHeader :title="$t('debitnote.title')">

          <BaseBreadcrumb>
            <BaseBreadcrumbItem
              :title="$t('general.home')"
              to="/admin/dashboard"
            />
            <BaseBreadcrumbItem
              :title="$t('debitnote.title')"
              to="/admin/debit-note"
            />
            <BaseBreadcrumbItem
              :title="$t('debitnote.new')"
              to="#"
              active
            />
          </BaseBreadcrumb>

        </BasePageHeader>

        <BaseCard>

          <h3>Please select supplier and purchase voucher to generate debit note</h3><br>

          <BaseInputGroup
            :label="$t('purchases.supplier')"
            :error="
              v$.currentPayment.customer_id.$error &&
              v$.currentPayment.customer_id.$errors[0].$message
            "
            :content-loading="isLoadingContent"
            required
          >
            <BaseCustomerSelectInput
              v-model="paymentStore.currentPayment.customer_id"
              :content-loading="isLoadingContent"
              :invalid="v$.currentPayment.customer_id.$error"
              :placeholder="$t('customers.select_a_customer')"
              :fetch-all="isEdit"
              :customer-type="customerType"
              show-action
              @update:modelValue="
                selectNewCustomer(paymentStore.currentPayment.customer_id)
              "
            />
          </BaseInputGroup>

          <br>

            <BaseInputGroup
              required
              :content-loading="isLoadingContent"
              :label="$t('pdf_label_purchase_voucher')"
              :help-text="
                selectedInvoice
                  ? `Due Amount: ${
                      paymentStore.currentPayment.maxPayableAmount / 100
                    }`
                  : ''
              "
            >
              <BaseMultiselect
                v-model="paymentStore.currentPayment.invoice_id"
                :content-loading="isLoadingContent"
                value-prop="id"
                track-by="purchase_no"
                label="purchase_no"
                :options="invoiceList"
                :loading="isLoadingInvoices"
                :placeholder="$t('invoices.select_invoice')"
                @select="onSelectInvoice"
              >
                <template #singlelabel="{ value }">
                  <div class="absolute left-3.5">
                    {{ value.purchase_no }} ({{
                      utils.formatMoney(value.total, value.supplier.currency)
                    }})
                  </div>
                </template>

              </BaseMultiselect>
            </BaseInputGroup>

            <BaseButton
              :loading="isSaving"
              :content-loading="isLoadingContent"
              variant="primary"
              type="submit"
              class="flex justify-center mt-4"
            > 
              {{ $t('debitnote.proceed') }}
            </BaseButton>

          </BaseCard>

    </form>
  </BasePage>
</template>

<script setup>
import ExchangeRateConverter from '@/scripts/components/estimate-invoice-common/ExchangeRateConverter.vue'

import {
  ref,
  reactive,
  computed,
  inject,
  watchEffect,
  onBeforeUnmount,
} from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import {
  required,
  numeric,
  helpers,
  between,
  requiredIf,
  decimal,
} from '@vuelidate/validators'

import useVuelidate from '@vuelidate/core'
import { useSupplierStore } from '@/scripts/stores/supplier'
import { usePaymentStore } from '@/scripts/stores/payment'
import { useNotificationStore } from '@/scripts/stores/notification'
import { useCustomFieldStore } from '@/scripts/stores/custom-field'
import { useCompanyStore } from '@/scripts/stores/company'
import { useModalStore } from '@/scripts/stores/modal'
// import { useInvoiceStore } from '@/scripts/stores/invoice'
import { usePurchaseStore } from '@/scripts/stores/purchase'
import { useGlobalStore } from '@/scripts/stores/global'

import SelectNotePopup from '@/scripts/components/SelectNotePopup.vue'
import PaymentCustomFields from '@/scripts/components/custom-fields/CreateCustomFields.vue'
import PaymentModeModal from '@/scripts/components/modal-components/PaymentModeModal.vue'

const route = useRoute()
const router = useRouter()

const paymentStore = usePaymentStore()
const notificationStore = useNotificationStore()
const supplierStore = useSupplierStore()
const customFieldStore = useCustomFieldStore()
const companyStore = useCompanyStore()
const modalStore = useModalStore()
// const invoiceStore = useInvoiceStore()
const purchaseStore = usePurchaseStore()
const globalStore = useGlobalStore()

const utils = inject('utils')
const { t } = useI18n()

let isSaving = ref(false)
let isLoadingInvoices = ref(false)
let invoiceList = ref([])
const selectedInvoice = ref(null)

const paymentValidationScope = 'newEstimate'

const PaymentFields = reactive([
  'customer',
  'company',
  'customerCustom',
  'payment',
  'paymentCustom',
])

const amount = computed({
  get: () => paymentStore.currentPayment.amount / 100,
  set: (value) => {
    paymentStore.currentPayment.amount = Math.round(value * 100)
  },
})

const isLoadingContent = computed(() => paymentStore.isFetchingInitialData)

const isEdit = computed(() => route.name === 'payments.edit')

const customerType = 'purchase'

const pageTitle = computed(() => {
  if (isEdit.value) {
    return t('payments.edit_payment')
  }
  return t('payments.new_payment')
})

const rules = computed(() => {
  return {
    currentPayment: {
      customer_id: {
        required: helpers.withMessage(t('validation.required'), required),
      },
      invoice_id: {
        required: helpers.withMessage(t('validation.required'), required),
      },
    },
  }
})

const v$ = useVuelidate(rules, paymentStore, {
  $scope: paymentValidationScope,
})

watchEffect(() => {
  // fetch customer and its invoices
  paymentStore.currentPayment.customer_id
    ? onCustomerChange(paymentStore.currentPayment.customer_id)
    : ''
  if (route.query.customer) {
    paymentStore.currentPayment.customer_id = route.query.customer
  }
})

// Reset State on Create
paymentStore.resetCurrentPayment()

if (route.query.customer) {
  paymentStore.currentPayment.customer_id = route.query.customer
}

paymentStore.fetchPaymentInitialData(isEdit.value)

if (route.params.id && !isEdit.value) {
  setInvoiceFromUrl()
}

async function addPaymentMode() {
  modalStore.openModal({
    title: t('settings.payment_modes.add_payment_mode'),
    componentName: 'PaymentModeModal',
  })
}

function onSelectNote(data) {
  paymentStore.currentPayment.notes = '' + data.notes
}

async function setInvoiceFromUrl() {
  let res = await purchaseStore.fetchInvoice(route?.params?.id)

  paymentStore.currentPayment.customer_id = res.data.data.customer.id
  paymentStore.currentPayment.invoice_id = res.data.data.id
}

async function onSelectInvoice(id) {
  // if (id) {
  //   selectedInvoice.value = invoiceList.value.find((inv) => inv.id === id)

  //   // console.log(selectedInvoice.value)

  //   amount.value = selectedInvoice.value.due_amount / 100
  //   paymentStore.currentPayment.maxPayableAmount =
  //     selectedInvoice.value.due_amount
  // }
}

function onCustomerChange(customer_id) {
  if (customer_id) {
    let data = {
      supplier_id: customer_id,
      // status: 'DRAFT',
      limit: 'all',
    }

    if (isEdit.value) {
      data.status = ''
    }

    isLoadingInvoices.value = true
    // alert(customer_id)
    Promise.all([
      purchaseStore.fetchPurchases(data, true),
      supplierStore.fetchSupplier(customer_id),
    ])
      .then(async ([res1, res2]) => {

        // console.log('res1')
        // console.log(res1)
        // console.log('res2')
        // console.log(res2)

        if (res1) {
          invoiceList.value = [...res1.data.data]
        }

        if (res2 && res2.data) {
          paymentStore.currentPayment.selectedCustomer = res2.data.data
          paymentStore.currentPayment.customer = res2.data.data
          paymentStore.currentPayment.currency = res2.data.data.currency
        }

        if (isEdit.value && paymentStore.currentPayment.invoice_id) {
          selectedInvoice.value = invoiceList.value.find(
            (inv) => inv.id === paymentStore.currentPayment.invoice_id
          )

          paymentStore.currentPayment.maxPayableAmount =
            selectedInvoice.value.due_amount +
            paymentStore.currentPayment.amount
        }

        if (isEdit.value) {
          alert('isEdit.value')
          invoiceList.value = invoiceList.value.filter((v) => {
            return (
              v.due_amount > 0 || v.id == paymentStore.currentPayment.invoice_id
            )
          })
        }

        isLoadingInvoices.value = false
      })
      .catch((error) => {
        isLoadingInvoices.value = false
        console.error(error, 'error')
      })
  }
}
onBeforeUnmount(() => {
  paymentStore.resetCurrentPayment()
  invoiceList.value = []
})

async function submitPaymentData() {
  v$.value.$touch()

  if (v$.value.$invalid) {
    return false
  }

  isSaving.value = true

  router.push(`/admin/debit-note/${paymentStore.currentPayment.invoice_id}/create`)
}

function selectNewCustomer(id) {
  let params = {
    userId: id,
  }

  if (route.params.id) params.model_id = route.params.id

  paymentStore.currentPayment.invoice_id = selectedInvoice.value = null
  paymentStore.currentPayment.amount = 100
  invoiceList.value = []
  paymentStore.getNextNumber(params, true)
}
</script>
