import axios from 'axios'
import moment from 'moment'
import Guid from 'guid'
import _ from 'lodash'
import { defineStore } from 'pinia'
import { useRoute } from 'vue-router'
import { handleError } from '@/scripts/helpers/error-handling'
import purchaseItemStub from '../stub/purchase-item'
import taxStub from '../stub/tax'
import purchaseStub from '../stub/purchase'

import { useNotificationStore } from './notification'
import { useSupplierStore } from './supplier'
import { useTaxTypeStore } from './tax-type'
import { useCompanyStore } from './company'
import { useItemStore } from './item'


export const usePurchaseStore = (useWindow = false) => {
  const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore
  const { global } = window.i18n
  const notificationStore = useNotificationStore()

  return defineStoreFunc({
    id: 'purchase',
    state: () => ({
      templates: [],
      purchases: [],
      selectedPurchases: [],
      selectAllField: false,
      purchaseTotalCount: 0,
      showExchangeRate: false,
      isFetchingInitialSettings: false,
      isFetchingPurchase: false,

      newPurchase: {
        ...purchaseStub(),
      },
    }),

    getters: {
      getPurchase: (state) => (id) => {
        let invId = parseInt(id)
        return state.purchases.find((purchase) => purchase.id === invId)
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
        if(this.newPurchase.discount_type == 'percentage')
          return this.getSubTotal - Math.round(this.getSubTotal * this.newPurchase.discount / 100)

        return this.getSubTotal - (this.newPurchase.discount * 100)
      },

      getTotal() {
        return this.getSubtotalWithDiscount + this.getTotalTax
      },

    },
      isEdit: (state) => (state.newPurchase.id ? true : false),

    actions: {
      resetCurrentPurchase() {
        this.newPurchase = {
          ...purchaseStub(),
        }
      },

      previewPurchase(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/purchases/${params.id}/send/preview`, { params })
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },
      

      fetchPurchases(params, for_debit_note = false) {
        // console.log('fetchPurchases')
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/${for_debit_note ? 'purchases/for_debitnotes' : 'purchases'}`, { params })
            .then((response) => {
              this.purchases = response.data.data
              this.purchaseTotalCount = response.data.meta.purchase_total_count
              // console.log(response.data.data)
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchPurchase(id) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/purchases/${id}`)
            .then((response) => {

              let data = response.data.data

              Object.assign(this.newPurchase, data)
              // alert(id)
              // alert(data.purchase_no)
              // console.log('response.data.data')
              // console.log(response.data.data)
              this.newPurchase.customer = data.supplier
              this.newPurchase.customer_id = data.supplier.id

              // alert(this.newPurchase.purchase_date)
              // alert(this.newPurchase.order_date)
              // this.newPurchase.purchase_date = moment(data.purchase_date).format('MM/DD/YYYY');
              this.newPurchase.order_date = data.order_date;
              this.newPurchase.supply_date = data.supply_date;
              this.newPurchase.purchase_no = data.purchase_no;

              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      sendPurchase(data) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/purchases/${data.id}/send`, data)
            .then((response) => {
              notificationStore.showNotification({
                type: 'success',
                message: global.t('purchases.purchase_sent_successfully'),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },
      

      addPurchase(data) {
        return new Promise((resolve, reject) => {
          axios
            .post('/api/v1/purchases', data)
            .then((response) => {
              this.purchases = [...this.purchases, response.data.purchase]

              notificationStore.showNotification({
                type: 'success',
                message: global.t('purchases.created_message'),
              })

              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      deletePurchase(id) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/purchases/delete`, id)
            .then((response) => {
              let index = this.purchases.findIndex(
                (purchase) => purchase.id === id
              )
              this.purchases.splice(index, 1)

              notificationStore.showNotification({
                type: 'success',
                message: global.t('purchases.deleted_message', 1),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      deleteMultiplePurchases(id) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/purchases/delete`, { ids: this.selectedPurchases })
            .then((response) => {
              this.selectedPurchases.forEach((purchase) => {
                let index = this.purchases.findIndex(
                  (_inv) => _inv.id === purchase.id
                )
                this.purchases.splice(index, 1)
              })
              this.selectedPurchases = []

              notificationStore.showNotification({
                type: 'success',
                message: global.tc('purchases.deleted_message', 2),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      updatePurchase(data) {
        return new Promise((resolve, reject) => {
          axios
            .put(`/api/v1/purchases/${data.id}`, data)
            .then((response) => {
              let pos = this.purchases.findIndex(
                (purchase) => purchase.id === response.data.data.id
              )
              this.purchases[pos] = response.data.data

              notificationStore.showNotification({
                type: 'success',
                message: global.t('purchases.updated_message'),
              })

              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      clonePurchse(data) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/purchases/${data.id}/clone`, data)
            .then((response) => {
              notificationStore.showNotification({
                type: 'success',
                message: global.t('purchases.cloned_successfully'),
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
            .post(`/api/v1/purchases/${data.id}/status`, data)
            .then((response) => {
              let pos = this.purchases.findIndex(
                (purchase) => purchases.id === data.id
              )

              if (this.purchases[pos]) {
                this.purchases[pos].status = 'SENT'
              }

              notificationStore.showNotification({
                type: 'success',
                message: global.t('purchases.mark_as_sent_successfully'),
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
        // console.log(params)
        // console.log('getNextNumber')

        setState = this.isEdit ? true : false

        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/next-number?key=purchase`, { params })
            .then((response) => {
              // console.log(response)
              if (setState) {
                  this.newPurchase.purchase_no = response.data.nextNumber
              }
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      searchPurchase(data) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/purchases?${data}`)
            .then((response) => {
              // console.log(`/api/v1/purchases?${data}`)
              // console.log(response)
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      selectPurchase(data) {
        this.selectedPurchases = data
        if (this.selectedPurchases.length === this.purchases.length) {
          this.selectAllField = true
        } else {
          this.selectAllField = false
        }
      },

      selectAllPurchases() {
        if (this.selectedPurchase.length === this.purchases.length) {
          this.selectedPurchases = []
          this.selectAllField = false
        } else {
          let allPurchaseIds = this.purchases.map((purchase) => purchase.id)
          this.selectedPurchases = allPurchaseIds
          this.selectAllField = true
        }
      },

      selectCustomer(id) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/suppliers/${id}`)
            .then((response) => {
              // console.log('this.newPurchase.customer')
              // console.log(response.data.data)
              this.newPurchase.customer = response.data.data
              this.newPurchase.customer_id = response.data.data.id
              // console.log(this.newPurchase.customer_id)
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchPurchaseTemplates(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/purchases/templates`, { params })
            .then((response) => {
              this.templates = response.data.purchaseTemplates
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
          ...purchaseItemStub,
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
          ...purchaseItemStub,
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
        const supplierStore = useSupplierStore()
        const itemStore = useItemStore()
        const taxTypeStore = useTaxTypeStore()
        const route = useRoute()

        this.isFetchingInitialSettings = true

        this.newPurchase.selectedCurrency = companyStore.selectedCompanyCurrency

        if (route.query.supplier) {
          let response = await supplierStore.fetchSupplier(route.query.supplier)
          this.newPurchase.supplier = response.data.data
          this.newPurchase.supplier_id = response.data.data.id
        }

        let editActions = []

        if (!isEdit) {
          this.newPurchase.tax_per_item =
            companyStore.selectedCompanySettings.tax_per_item
          this.newPurchase.discount_per_item =
            companyStore.selectedCompanySettings.discount_per_item
          
          this.newPurchase.purchase_date = moment().format('YYYY-MM-DD')
        } else {
          // alert('working')
          editActions = [this.fetchPurchase(route.params.id)]
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
          this.fetchPurchaseTemplates(),
          this.getNextNumber('withPurchaseDate', true),
          taxTypeStore.fetchTaxTypes({ limit: 'all' }),
          ...editActions,
        ])
          .then(async ([res1, res2, res3, res4, res5, res6]) => {
            if (!isEdit) {
              if (res4.data) {
                // alert('result4')
                // console.log(res4.data)
                this.newPurchase.purchase_no = res4.data.nextNumber
                this.newPurchase.purchase_date = moment(res4.data.purchaseDate).format('YYYY-MM-DD')
              }

              if (res3.data) {
                this.setTemplate(this.templates[0].name)
              }
            }
            else{
              this.newPurchase.purchase_date = moment(this.newPurchase.purchase_date).format('YYYY-MM-DD')
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
