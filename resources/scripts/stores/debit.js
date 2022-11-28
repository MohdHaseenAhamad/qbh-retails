import axios from 'axios'
import moment from 'moment'
import Guid from 'guid'
import _ from 'lodash'
import { defineStore } from 'pinia'
import { useRoute } from 'vue-router'
import { handleError } from '@/scripts/helpers/error-handling'
import invoiceItemStub from '../stub/creditnote-item'
import taxStub from '../stub/tax'
import debitnoteStub from '../stub/debitnote'

import { useNotificationStore } from './notification'
import { useSupplierStore } from './supplier'
import { useTaxTypeStore } from './tax-type'
import { useCompanyStore } from './company'
import { useItemStore } from './item'

import { useGlobalStore } from '@/scripts/stores/global'

export const useDebitStore = (useWindow = false) => {
  const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore
  const { global } = window.i18n
  const notificationStore = useNotificationStore()

  const globalStore = useGlobalStore()

  return defineStoreFunc({
    id: 'debit',
    state: () => ({
      templates: [],
      invoices: [],
      selectedInvoices: [],
      selectAllField: false,
      debitTotalCount: 0,
      showExchangeRate: false,
      isFetchingInitialSettings: false,
      isFetchingInvoice: false,
      selectedTab: 'debit',
      tableData: {},
      customers: {'debit':'suppliers', 'purchase':'suppliers'},

      newPurchase: {
        ...debitnoteStub(),
      },
    }),

    getters: {
      getInvoice: (state) => (id) => {
        let invId = parseInt(id)
        return state.invoices.find((invoice) => invoice.id === invId)
      },

      getSubTotal() {
        return this.newPurchase.items.reduce(function (a, b) {
          return a + b['total']
        }, 0)
      },

      getTotalSimpleTax() {
        return _.sumBy(this.newPurchase.taxes, function (tax) {
          if (!tax.compound_tax) {
            return tax.amount
          }
          return 0
        })
      },

      getTotalCompoundTax() {
        return _.sumBy(this.newPurchase.taxes, function (tax) {
          if (tax.compound_tax) {
            return tax.amount
          }
          return 0
        })
      },

      getTotalTax() {
        if (
          this.newPurchase.tax_per_item === 'NO' ||
          this.newPurchase.tax_per_item === null
        ) {
          return this.getTotalSimpleTax + this.getTotalCompoundTax
        }
        return _.sumBy(this.newPurchase.items, function (tax) {
          return tax.tax
        })
      },

      getSubtotalWithDiscount() {
        return this.getSubTotal - this.newPurchase.discount_val
      },

      getTotal() {
        return this.getSubtotalWithDiscount + this.getTotalTax
      },

      // isEdit: (state) => (state.newInvoice.id ? true : false),
      isEdit: (state) => (route.name == 'debit.edit' ? true : false),
    },

    actions: {
      resetCurrentPurchase() {
        this.newPurchase = {
          ...debitnoteStub(),
        }
      },

      previewInvoice(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/invoices/${params.id}/send/preview`, { params })
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },
      previewPerformaInvoice(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/performa-invoices/${params.id}/send/preview`, { params })
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchDebitNote(id) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/debits/${id}`)
            .then((response) => {
              Object.assign(this.newPurchase, response.data.data)
              this.newPurchase.customer = response.data.data.customer
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      addDebitNote(data) {
        return new Promise((resolve, reject) => {
          axios
            .post('/api/v1/debits', data)
            .then((response) => {
              this.invoices = [...this.invoices, response.data.invoice]

              notificationStore.showNotification({
                type: 'success',
                message: global.t('debits.created_message'),
              })

              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      deleteDebitNote(id) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/debits/delete`, id)
            .then((response) => {
              let index = this.invoices.findIndex(
                (invoice) => invoice.id === id
              )
              this.invoices.splice(index, 1)

              notificationStore.showNotification({
                type: 'success',
                message: global.t('debits.deleted_message', 1),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      deleteMultipleInvoices(id) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/credits/delete`, { ids: this.selectedInvoices })
            .then((response) => {
              this.selectedInvoices.forEach((invoice) => {
                let index = this.invoices.findIndex(
                  (_inv) => _inv.id === invoice.id
                )
                this.invoices.splice(index, 1)
              })
              this.selectedInvoices = []

              notificationStore.showNotification({
                type: 'success',
                message: global.tc('credits.deleted_message', 2),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      updateDebitNote(data) {
        console.log('updating data')
        console.log(data)
        return new Promise((resolve, reject) => {
          axios
            .put(`/api/v1/debits/${data.id}`, data)
            .then((response) => {
              let pos = this.invoices.findIndex(
                (invoice) => invoice.id === response.data.data.id
              )
              this.invoices[pos] = response.data.data

              notificationStore.showNotification({
                type: 'success',
                message: global.t('debits.updated_message'),
              })

              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      cloneInvoice(data) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/invoices/${data.id}/clone`, data)
            .then((response) => {
              notificationStore.showNotification({
                type: 'success',
                message: global.t('invoices.cloned_successfully'),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      markAsSent(data) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/debits/${data.id}/status`, data)
            .then((response) => {
              let pos = this.invoices.findIndex(
                (invoices) => invoices.id === data.id
              )

              if (this.invoices[pos]) {
                this.invoices[pos].status = 'SENT'
              }

              notificationStore.showNotification({
                type: 'success',
                message: global.t('debitnote.mark_as_sent_successfully'),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      getNextNumber(params, setState = true) {
        // alert(setState)
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/next-number?key=debit`, { params })
            .then((response) => {
              // console.log('response')
              // console.log(response)
              if (setState) {
                this.newPurchase.debit_number = response.data.nextNumber
                console.log(response.data)
              }
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      searchDebitNotes(data) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/debits?${data}`)
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      selectInvoice(data) {
        this.selectedInvoices = data
        if (this.selectedInvoices.length === this.invoices.length) {
          this.selectAllField = true
        } else {
          this.selectAllField = false
        }
      },

      selectAllInvoices() {
        if (this.selectedInvoices.length === this.invoices.length) {
          this.selectedInvoices = []
          this.selectAllField = false
        } else {
          let allInvoiceIds = this.invoices.map((invoice) => invoice.id)
          this.selectedInvoices = allInvoiceIds
          this.selectAllField = true
        }
      },

      selectCustomer(id, type) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/${this.customers.type}/${id}`)
            .then((response) => {
              this.newPurchase.customer = response.data.data
              this.newPurchase.customer_id = response.data.data.id
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchDebitNote(id) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/debits/${id}`)
            .then((response) => {
              Object.assign(this.newPurchase, response.data.data)
              this.newPurchase.customer = response.data.data.customer
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchDebitNotes(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/debits`, { params })
            .then((response) => {
              console.log('fetchDebitNotes')
              console.log(response)
              this.invoices = response.data.data
              this.debitTotalCount = response.data.meta.debit_total_count
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchPurchases(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/purchases`, { params })
            .then((response) => {
              this.invoices = response.data.data
              this.invoiceTotalCount = response.data.meta.invoice_total_count
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchPurchase(id, is_it_debit_note = false) {
        const route = useRoute()

        // alert(`/api/v1/${is_it_debit_note ? 'credits' : 'purchases'}/${id}`)
        // alert(is_it_debit_note)
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/${is_it_debit_note ? 'debits' : 'purchases'}/${id}`)
            .then((response) => {
              Object.assign(this.newPurchase, response.data.data)

              if(is_it_debit_note){
                let data = response.data.data
                // console.log(`/api/v1/${is_it_debit_note ? 'debits' : 'purchases'}/${id}`)
                // console.log(response.data.data)
                this.newPurchase.purchase_no = data.purchase.purchase_no
                this.newPurchase.purchase_date = data.purchase.formatted_purchase_date
                this.newPurchase.debit_date = data.debit_date
                this.newPurchase.debit_number = data.debit_number
                this.newPurchase.purchase_id = data.purchase.id
                this.newPurchase.template_name = data.template_name

                this.newPurchase.invoice_no = data.purchase.invoice_no
                this.newPurchase.invoice_date = data.purchase.formatted_invoice_date
                this.newPurchase.order_no = data.purchase.order_no
                this.newPurchase.order_date = data.purchase.formatted_order_date
                this.newPurchase.material_receipt = data.purchase.material_receipt
                this.newPurchase.supply_date = data.purchase.formatted_supply_date
                this.newPurchase.reason = data.reason
                this.newPurchase.reference_number = data.reference_number
              }
              // console.log('response.data.data')
              // console.log(response.data.data)
              
              if(route.name == 'debit.create'){
                this.newPurchase.customer = response.data.data.supplier
                this.newPurchase.customer_id = response.data.data.supplier.id
              }
              else{
                this.newPurchase.customer = response.data.data.customer
                this.newPurchase.customer_id = response.data.data.customer.id
              }
              
              // console.log('this.newPurchase')
              // console.log(this.newPurchase)
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },
      
      fetchInvoiceTemplates(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/debits/templates`, { params })
            .then((response) => {
              this.templates = globalStore.setAllowedTempletes(response.data.invoiceTemplates)
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      selectNote(data) {
        this.newPurchase.selectedNote = null
        this.newPurchase.selectedNote = data
      },

      setTemplate(data) {
        this.newPurchase.template_name = data
      },

      resetSelectedCustomer() {
        this.newPurchase.customer = null
        this.newPurchase.customer_id = null
      },

      addItem() {
        this.newPurchase.items.push({
          ...invoiceItemStub,
          id: Guid.raw(),
          taxes: [{ ...taxStub, id: Guid.raw() }],
        })
      },

      updateItem(data) {
        Object.assign(this.newPurchase.items[data.index], { ...data })
      },

      removeItem(index) {
        this.newPurchase.items.splice(index, 1)
      },

      deselectItem(index) {
        this.newPurchase.items[index] = {
          ...invoiceItemStub,
          id: Guid.raw(),
          taxes: [{ ...taxStub, id: Guid.raw() }],
        }
      },
      deselectPreparedBy() {
        this.newPurchase.prepared_by_id = ''
      },

      resetSelectedNote() {
        this.newPurchase.selectedNote = null
      },

      // On Load actions
      async fetchPurchaseInitialSettings(isEdit) {
        const companyStore = useCompanyStore()
        const customerStore = useSupplierStore()
        const itemStore = useItemStore()
        const taxTypeStore = useTaxTypeStore()
        const route = useRoute()

        this.isFetchingInitialSettings = true

        this.newPurchase.selectedCurrency = companyStore.selectedCompanyCurrency

        if (route.query.customer) {
          let response = await customerStore.fetchCustomer(route.query.customer)
          this.newPurchase.customer = response.data.data
          this.newPurchase.customer_id = response.data.data.id
        }

        let editActions = []

        if (!isEdit) {
          this.newPurchase.tax_per_item =
            companyStore.selectedCompanySettings.tax_per_item
          this.newPurchase.discount_per_item =
            companyStore.selectedCompanySettings.discount_per_item
          this.newPurchase.invoice_date = moment().format('YYYY-MM-DD')
          this.newPurchase.debit_date = moment().format('YYYY-MM-DD H:mm:ss')
        } else {
          if(route.name!=='debit.edit'){
            this.newPurchase.purchase_id = route.params.id
          }

          this.newPurchase.debit_date = moment().format('YYYY-MM-DD H:mm:ss')

          editActions = [this.fetchPurchase(route.params.id, route.name==='debit.edit'?true:false)]
        }

        Promise.all([
          itemStore.fetchItems({
            filter: {},
            orderByField: '',
            orderBy: '',
          }),
          // preparedByStore.fetchPreparedBies({
          //   filter: {},
          //   orderByField: '',
          //   orderBy: '',
          // }),
          this.resetSelectedNote(),
          this.fetchInvoiceTemplates(),
          this.getNextNumber('withInvoiceDate', route.name==='debit.edit'?false:true),
          taxTypeStore.fetchTaxTypes({ limit: 'all' }),
          ...editActions,
        ])
          .then(async ([res1, res2, res3, res4, res5, res6]) => {
            // console.log('res1')
            // console.log(res1)
            // console.log('res2')
            // console.log(res2)
            // console.log('res3')
            // console.log(res3)
            // console.log(res3)
            // console.log('res4')
            // console.log(res4)
            // console.log('res5')
            // console.log(res5)
            // console.log('res6')
            // console.log(res6)
            if (!isEdit) {
              if (res4.data) {
                this.newPurchase.invoice_number = res4.data.nextNumber
              }

              if (res3.data) {
                alert(this.templates[0].name)
                this.setTemplate(this.templates[0].name)
              }
            }
            else{
              // SET TEMPLATE NAME
              if(route.name !== 'debit.edit')
                if (res3.data)
                  this.setTemplate(this.templates[0].name)
            }

            this.isFetchingInitialSettings = false
          })
          .catch((err) => {
            console.log(err)
            handleError(err)
            // reject(err)
          })
      },
    },
  })()
}
