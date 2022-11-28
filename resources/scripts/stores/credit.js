import axios from 'axios'
import moment from 'moment'
import Guid from 'guid'
import _ from 'lodash'
import { defineStore } from 'pinia'
import { useRoute } from 'vue-router'
import { handleError } from '@/scripts/helpers/error-handling'
import invoiceItemStub from '../stub/creditnote-item'
import taxStub from '../stub/tax'
import creditnoteStub from '../stub/creditnote'

import { useNotificationStore } from './notification'
import { useCustomerStore } from './customer'
import { useTaxTypeStore } from './tax-type'
import { useCompanyStore } from './company'
import { useItemStore } from './item'

import { useGlobalStore } from '@/scripts/stores/global'

export const useCreditStore = (useWindow = false) => {
  const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore
  const { global } = window.i18n
  const notificationStore = useNotificationStore()

  const globalStore = useGlobalStore()

  return defineStoreFunc({
    id: 'credit',
    state: () => ({
      templates: [],
      invoices: [],
      selectedInvoices: [],
      selectAllField: false,
      creditTotalCount: 0,
      showExchangeRate: false,
      isFetchingInitialSettings: false,
      isFetchingInvoice: false,
      selectedTab: 'credit',
      tableData: {},
      customers: {'credit':'customers', 'purchase':'suppliers'},

      newInvoice: {
        ...creditnoteStub(),
      },
    }),

    getters: {
      getInvoice: (state) => (id) => {
        let invId = parseInt(id)
        return state.invoices.find((invoice) => invoice.id === invId)
      },

      getSubTotal() {
        return this.newInvoice.items.reduce(function (a, b) {
          return a + b['total']
        }, 0)
      },

      getTotalSimpleTax() {
        return _.sumBy(this.newInvoice.taxes, function (tax) {
          if (!tax.compound_tax) {
            return tax.amount
          }
          return 0
        })
      },

      getTotalCompoundTax() {
        return _.sumBy(this.newInvoice.taxes, function (tax) {
          if (tax.compound_tax) {
            return tax.amount
          }
          return 0
        })
      },

      getTotalTax() {
        if (
          this.newInvoice.tax_per_item === 'NO' ||
          this.newInvoice.tax_per_item === null
        ) {
          return this.getTotalSimpleTax + this.getTotalCompoundTax
        }
        return _.sumBy(this.newInvoice.items, function (tax) {
          return tax.tax
        })
      },

      getSubtotalWithDiscount() {
        return this.getSubTotal - this.newInvoice.discount_val
      },

      getTotal() {
        return this.getSubtotalWithDiscount + this.getTotalTax
      },

      // isEdit: (state) => (state.newInvoice.id ? true : false),
      isEdit: (state) => (route.name == 'credit.edit' ? true : false),
    },

    actions: {
      resetCurrentInvoice() {
        this.newInvoice = {
          ...creditnoteStub(),
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

      // fetchCreditNotes(params) {
      //   return new Promise((resolve, reject) => {
      //     axios
      //       .get(`/api/v1/credits`, { params })
      //       .then((response) => {
      //         this.invoices = response.data.data
      //         this.invoiceTotalCount = response.data.meta.invoice_total_count
      //         resolve(response)
      //       })
      //       .catch((err) => {
      //         handleError(err)
      //         reject(err)
      //       })
      //   })
      // },

      fetchCreditNote(id) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/credits/${id}`)
            .then((response) => {
              Object.assign(this.newInvoice, response.data.data)
              this.newInvoice.customer = response.data.data.customer
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      sendInvoice(data) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/invoices/${data.id}/send`, data)
            .then((response) => {
              notificationStore.showNotification({
                type: 'success',
                message: global.t('invoices.invoice_sent_successfully'),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },
      sendPerformaInvoice(data) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/performa-invoices/${data.id}/send`, data)
            .then((response) => {
              notificationStore.showNotification({
                type: 'success',
                message: global.t('invoices.invoice_sent_successfully'),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      addCreditNote(data) {
        return new Promise((resolve, reject) => {
          axios
            .post('/api/v1/credits', data)
            .then((response) => {
              this.invoices = [...this.invoices, response.data.invoice]

              notificationStore.showNotification({
                type: 'success',
                message: global.t('credits.created_message'),
              })

              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      deleteInvoice(id) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/credits/delete`, id)
            .then((response) => {
              let index = this.invoices.findIndex(
                (invoice) => invoice.id === id
              )
              this.invoices.splice(index, 1)

              notificationStore.showNotification({
                type: 'success',
                message: global.t('credits.deleted_message', 1),
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

      updateCreditNote(data) {
        return new Promise((resolve, reject) => {
          axios
            .put(`/api/v1/credits/${data.id}`, data)
            .then((response) => {
              let pos = this.invoices.findIndex(
                (invoice) => invoice.id === response.data.data.id
              )
              this.invoices[pos] = response.data.data

              notificationStore.showNotification({
                type: 'success',
                message: global.t('credits.updated_message'),
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
            .post(`/api/v1/credits/${data.id}/status`, data)
            .then((response) => {
              let pos = this.invoices.findIndex(
                (invoices) => invoices.id === data.id
              )

              if (this.invoices[pos]) {
                this.invoices[pos].status = 'SENT'
              }

              notificationStore.showNotification({
                type: 'success',
                message: global.t('creditnote.mark_as_sent_successfully'),
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
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/next-number?key=credit`, { params })
            .then((response) => {
              if (setState) {
                this.newInvoice.credit_number = response.data.nextNumber
              }
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      searchInvoice(data) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/invoices?${data}`)
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
              this.newInvoice.customer = response.data.data
              this.newInvoice.customer_id = response.data.data.id
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchCreditNotes(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/credits`, { params })
            .then((response) => {
              this.invoices = response.data.data
              this.creditTotalCount = response.data.meta.credit_total_count
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchInvoices(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/invoices`, { params })
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

      fetchInvoice(id, is_it_credit_note = false) {
        // alert(is_it_credit_note)
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/${is_it_credit_note ? 'credits' : 'invoices'}/${id}`)
            .then((response) => {
              Object.assign(this.newInvoice, response.data.data)

              if(is_it_credit_note){
                // alert('inside')
                let data = response.data.data
                this.newInvoice.invoice_number = data.invoice.invoice_number
                this.newInvoice.invoice_date = moment(data.invoice.invoice_date).format('YYYY-MM-DD H:mm:ss')
                this.newInvoice.credit_date = data.credit_date
                this.newInvoice.credit_number = data.credit_number
                this.newInvoice.invoice_id = data.invoice.id
                this.newInvoice.template_name = data.template_name
              }
              // alert('outside')
              this.newInvoice.customer = response.data.data.customer
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
            .get(`/api/v1/credits/templates`, { params })
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
        this.newInvoice.selectedNote = null
        this.newInvoice.selectedNote = data
      },

      setTemplate(data) {
        this.newInvoice.template_name = data
      },

      resetSelectedCustomer() {
        this.newInvoice.customer = null
        this.newInvoice.customer_id = null
      },

      addItem() {
        this.newInvoice.items.push({
          ...invoiceItemStub,
          id: Guid.raw(),
          taxes: [{ ...taxStub, id: Guid.raw() }],
        })
      },

      updateItem(data) {
        Object.assign(this.newInvoice.items[data.index], { ...data })
      },

      removeItem(index) {
        this.newInvoice.items.splice(index, 1)
      },

      deselectItem(index) {
        this.newInvoice.items[index] = {
          ...invoiceItemStub,
          id: Guid.raw(),
          taxes: [{ ...taxStub, id: Guid.raw() }],
        }
      },
      deselectPreparedBy() {
        this.newInvoice.prepared_by_id = ''
      },

      resetSelectedNote() {
        this.newInvoice.selectedNote = null
      },

      // On Load actions
      async fetchInvoiceInitialSettings(isEdit) {
        const companyStore = useCompanyStore()
        const customerStore = useCustomerStore()
        const itemStore = useItemStore()
        const taxTypeStore = useTaxTypeStore()
        const route = useRoute()

        this.isFetchingInitialSettings = true

        this.newInvoice.selectedCurrency = companyStore.selectedCompanyCurrency

        if (route.query.customer) {
          let response = await customerStore.fetchCustomer(route.query.customer)
          this.newInvoice.customer = response.data.data
          this.newInvoice.customer_id = response.data.data.id
        }

        let editActions = []

        if (!isEdit) {
          this.newInvoice.tax_per_item =
            companyStore.selectedCompanySettings.tax_per_item
          this.newInvoice.discount_per_item =
            companyStore.selectedCompanySettings.discount_per_item
          this.newInvoice.credit_date = moment().format('YYYY-MM-DD H:mm:ss')
        } else {
          if(route.name!=='credit.edit'){
            this.newInvoice.invoice_id = route.params.id
          }

          this.newInvoice.credit_date = moment().format('YYYY-MM-DD H:mm:ss')

          editActions = [this.fetchInvoice(route.params.id, route.name==='credit.edit'?true:false)]
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
          this.getNextNumber('withInvoiceDate', route.name==='credit.edit'?false:true),
          taxTypeStore.fetchTaxTypes({ limit: 'all' }),
          ...editActions,
        ])
          .then(async ([res1, res2, res3, res4, res5, res6]) => {
            if (!isEdit) {
              if (res4.data) {
                this.newInvoice.invoice_number = res4.data.nextNumber
              }

              if (res3.data) {
                this.setTemplate(this.templates[0].name)
              }
            }
            else{
              // SET TEMPLATE NAME
              if(route.name !== 'credit.edit')
                if (res3.data)
                  this.setTemplate(this.templates[0].name)
            }

            this.isFetchingInitialSettings = false
          })
          .catch((err) => {
            handleError(err)
            reject(err)
          })
      },
    },
  })()
}
