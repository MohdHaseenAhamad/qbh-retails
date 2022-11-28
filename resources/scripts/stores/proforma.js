import axios from 'axios'
import moment from 'moment'
import Guid from 'guid'
import _ from 'lodash'
import { defineStore } from 'pinia'
import { useRoute } from 'vue-router'
import { handleError } from '@/scripts/helpers/error-handling'
import proformaItemStub from '../stub/proforma-item'
import taxStub from '../stub/tax'
import proformaStub from '../stub/proforma'

import { useNotificationStore } from './notification'
import { useCustomerStore } from './customer'
import { useTaxTypeStore } from './tax-type'
import { useCompanyStore } from './company'
import { useItemStore } from './item'

import { useGlobalStore } from '@/scripts/stores/global'

export const useProformaStore = (useWindow = false) => {
  const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore
  const { global } = window.i18n
  const notificationStore = useNotificationStore()

  const globalStore = useGlobalStore()

  return defineStoreFunc({
    id: 'proforma',
    state: () => ({
      templates: [],
      proformas: [],
      selectedProformas: [],
      selectAllField: false,
      proformaTotalCount: 0,
      showExchangeRate: false,
      isFetchingInitialSettings: false,
      isFetchingProforma: false,

      newProforma: {
        ...proformaStub(),
      },
    }),

    getters: {
      getProforma: (state) => (id) => {
        let invId = parseInt(id)
        return state.proformas.find((proforma) => proforma.id === invId)
      },

      getSubTotal() {
        return this.newProforma.items.reduce(function (a, b) {
          return a + b['total']
        }, 0)
      },

      getTotalSimpleTax() {
        return _.sumBy(this.newProforma.taxes, function (tax) {
          if (!tax.compound_tax) {
            return tax.amount
          }
          return 0
        })
      },

      getTotalCompoundTax() {
        return _.sumBy(this.newProforma.taxes, function (tax) {
          if (tax.compound_tax) {
            return tax.amount
          }
          return 0
        })
      },

      getTotalTax() {
        if (
          this.newProforma.tax_per_item === 'NO' ||
          this.newProforma.tax_per_item === null
        ) {
          return this.getTotalSimpleTax + this.getTotalCompoundTax
        }
        return _.sumBy(this.newProforma.items, function (tax) {
          return tax.tax
        })
      },

      getSubtotalWithDiscount() {
        if(this.newProforma.discount_type == 'percentage')
          return this.getSubTotal - Math.round(this.getSubTotal * this.newProforma.discount / 100)

        return this.getSubTotal - (this.newProforma.discount * 100)
      },

      getTotal() {
        return this.getSubtotalWithDiscount + this.getTotalTax
      },

      isEdit: (state) => (state.newProforma.id ? true : false),
    },

    actions: {
      resetCurrentProforma() {
        this.newProforma = {
          ...proformaStub(),
        }
      },

      previewProforma(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/proformas/${params.id}/send/preview`, { params })
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },
      

      fetchProformas(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/proformas`, { params })
            .then((response) => {
              this.proformas = response.data.data
              this.proformaTotalCount = response.data.meta.proforma_total_count
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchProforma(id) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/proformas/${id}`)
            .then((response) => {
              Object.assign(this.newProforma, response.data.data)
              this.newProforma.customer = response.data.data.customer
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      addItem() {
        this.newProforma.items.push({
          ...proformaItemStub,
          id: Guid.raw(),
          taxes: [{ ...taxStub, id: Guid.raw() }],
        })
      },

      sendProforma(data) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/proformas/${data.id}/send`, data)
            .then((response) => {
              notificationStore.showNotification({
                type: 'success',
                message: global.t('proformas.proforma_sent_successfully'),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },
      

      addProforma(data) {
        return new Promise((resolve, reject) => {
          axios
            .post('/api/v1/proformas', data)
            .then((response) => {
              this.proformas = [...this.proformas, response.data.proforma]

              notificationStore.showNotification({
                type: 'success',
                message: global.t('proformas.created_message'),
              })

              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      deleteProforma(id) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/proformas/delete`, id)
            .then((response) => {
              let index = this.proformas.findIndex(
                (proforma) => proforma.id === id
              )
              this.proformas.splice(index, 1)

              notificationStore.showNotification({
                type: 'success',
                message: global.t('proformas.deleted_message', 1),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      deleteMultipleProformas(id) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/proformas/delete`, { ids: this.selectedProformas })
            .then((response) => {
              this.selectedProformas.forEach((proforma) => {
                let index = this.proformas.findIndex(
                  (_inv) => _inv.id === proforma.id
                )
                this.proformas.splice(index, 1)
              })
              this.selectedProformas = []

              notificationStore.showNotification({
                type: 'success',
                message: global.tc('proformas.deleted_message', 2),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },
      deselectBankDetail() {
        this.newProforma.bank_detail_id = ''
      },


      updateProforma(data) {
        return new Promise((resolve, reject) => {
          axios
            .put(`/api/v1/proformas/${data.id}`, data)
            .then((response) => {
              let pos = this.proformas.findIndex(
                (proforma) => proforma.id === response.data.data.id
              )
              this.proformas[pos] = response.data.data

              notificationStore.showNotification({
                type: 'success',
                message: global.t('proformas.updated_message'),
              })

              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      cloneProforma(data) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/proformas/${data.id}/clone`, data)
            .then((response) => {
              notificationStore.showNotification({
                type: 'success',
                message: global.t('proformas.cloned_successfully'),
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
            .post(`/api/v1/proformas/${data.id}/status`, data)
            .then((response) => {
              let pos = this.proformas.findIndex(
                (proformas) => proformas.id === data.id
              )

              if (this.proformas[pos]) {
                this.proformas[pos].status = 'SENT'
              }

              notificationStore.showNotification({
                type: 'success',
                message: global.t('proformas.mark_as_sent_successfully'),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      getNextNumber(params, setState = false) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/next-number?key=proforma`, { params })
            .then((response) => {
              if (setState) {
                this.newInvoice.invoice_number = res4.data.nextNumber
              }
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      searchProforma(data) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/proformas?${data}`)
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      selectProforma(data) {
        this.selectedProformas = data
        if (this.selectedProformas.length === this.proformas.length) {
          this.selectAllField = true
        } else {
          this.selectAllField = false
        }
      },

      selectAllProformas() {
        if (this.selectedProformas.length === this.proformas.length) {
          this.selectedProformas = []
          this.selectAllField = false
        } else {
          let allProformaIds = this.proformas.map((proforma) => proforma.id)
          this.selectedProformas = allProformaIds
          this.selectAllField = true
        }
      },

      selectCustomer(id) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/customers/${id}`)
            .then((response) => {
              this.newProforma.customer = response.data.data
              this.newProforma.customer_id = response.data.data.id
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchProformaTemplates(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/proformas/templates`, { params })
            .then((response) => {
              this.templates = globalStore.setAllowedTempletes(response.data.proformaTemplates)
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      selectNote(data) {
        this.newProforma.selectedNote = null
        this.newProforma.selectedNote = data
      },

      setTemplate(data) {
        this.newProforma.template_name = data
      },

      resetSelectedCustomer() {
        this.newProforma.customer = null
        this.newProforma.customer_id = null
      },

      

      updateItem(data) {
        Object.assign(this.newProforma.items[data.index], { ...data })
      },

      removeItem(index) {
        this.newProforma.items.splice(index, 1)
      },

      deselectItem(index) {
        this.newProforma.items[index] = {
          ...proformaItemStub,
          id: Guid.raw(),
          taxes: [{ ...taxStub, id: Guid.raw() }],
        }
      },
      deselectPreparedBy() {
        this.newProforma.prepared_by_id = ''
      },

      resetSelectedNote() {
        this.newProforma.selectedNote = null
      },

      // On Load actions
      async fetchProformaInitialSettings(isEdit) {
        const companyStore = useCompanyStore()
        const customerStore = useCustomerStore()
        const itemStore = useItemStore()
        const taxTypeStore = useTaxTypeStore()
        const route = useRoute()

        this.isFetchingInitialSettings = true

        this.newProforma.selectedCurrency = companyStore.selectedCompanyCurrency

        if (route.query.customer) {
          let response = await customerStore.fetchCustomer(route.query.customer)
          this.newProforma.customer = response.data.data
          this.newProforma.customer_id = response.data.data.id
        }

        let editActions = []

        if (!isEdit) {
          this.newProforma.tax_per_item =
            companyStore.selectedCompanySettings.tax_per_item
          this.newProforma.discount_per_item =
            companyStore.selectedCompanySettings.discount_per_item
          
          this.newProforma.due_date = moment()
            .add(7, 'days')
            .format('YYYY-MM-DD')
        } else {
          editActions = [this.fetchProforma(route.params.id)]
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
          this.fetchProformaTemplates(),
          this.getNextNumber('withProformaDate'),
          taxTypeStore.fetchTaxTypes({ limit: 'all' }),
          ...editActions,
        ])
          .then(async ([res1, res2, res3, res4, res5, res6]) => {
            if (!isEdit) {
              if (res4.data) {
                this.newProforma.proforma_number = res4.data.nextNumber
                this.newProforma.proforma_date = moment(res4.data.proformaDate).format('YYYY-MM-DD')
              }

              if (res3.data) {
                this.setTemplate(this.templates[0].name)
              }
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
