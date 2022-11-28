import axios from 'axios'
import { defineStore } from 'pinia'
import { useRoute } from 'vue-router'
import { handleError } from '@/scripts/helpers/error-handling'
import { useNotificationStore } from '@/scripts/stores/notification'
import { useGlobalStore } from '@/scripts/stores/global'
import { useCompanyStore } from '@/scripts/stores/company'
import addressStub from '@/scripts/stub/address.js'
import storeStub from '@/scripts/stub/store'

export const useStore = (useWindow = false) => {
  const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore
  const { global } = window.i18n

  return defineStoreFunc({
    id: 'store',
    state: () => ({
      suppliers: [],
      totalSuppliers: 0,
      selectAllField: false,
      selectedSuppliers: [],
      selectedViewSupplier: {},
      isFetchingInitialSettings: false,
      isFetchingViewData: false,
      currentSupplier: {
        ...storeStub(),
      },
      currentSupplierId: null,
      codeAvailability:[]
    }),

    getters: {
      isEdit: (state) => (state.currentSupplier.id ? true : false),
    },

    actions: {
      resetCurrentSupplier() {
        this.currentSupplier = {
          ...storeStub(),
        },
        this.currentSupplierId = null
      },

      copyAddress() {
        this.currentSupplier.shipping = {
          ...this.currentSupplier.billing,
          type: 'shipping',
        }
      },

      fetchSupplierInitialSettings(isEdit) {
        const route = useRoute()
        const globalStore = useGlobalStore()
        const companyStore = useCompanyStore()

        this.isFetchingInitialSettings = true
        let editActions = []
        if (isEdit) {
          editActions = [this.fetchStore(route.params.id)]
        } else {
          this.currentSupplier.currency_id =
            companyStore.selectedCompanyCurrency.id
        }

        Promise.all([
          globalStore.fetchCurrencies(),
          globalStore.fetchCountries(),
          ...editActions,
        ])
          .then(async ([res1, res2, res3]) => {
            this.isFetchingInitialSettings = false
          })
          .catch((error) => {
            handleError(error)
          })
      }, 

      fetchStores(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/stores`, { params })
            .then((response) => {
              // console.log('response')
              // console.log(response)
              this.suppliers = response.data.data
              this.totalSuppliers = response.data.meta.customer_total_count
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchViewStore(params) {
        return new Promise((resolve, reject) => {
          this.isFetchingViewData = true
          axios
            .get(`/api/v1/stores/${params.id}/stats`, { params })

            .then((response) => {
              this.selectedViewSupplier = {}
              Object.assign(this.selectedViewSupplier, response.data.data)
              this.setAddressStub(response.data.data)
              this.isFetchingViewData = false
              resolve(response)
            })
            .catch((err) => {
              this.isFetchingViewData = false
              handleError(err)
              reject(err)
            })
        })
      },

      fetchStore(id) {
        return new Promise((resolve, reject) => {
          // console.log(`/api/v1/suppliers/${id}`)
          axios
            .get(`/api/v1/stores/${id}`)
            .then((response) => {
              Object.assign(this.currentSupplier, response.data.data)
              this.currentSupplierId = response.data.data.id
              this.setAddressStub(response.data.data)
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      checkStoreCodeAvailability(data) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/stores/code_availability`, data)
            .then((response) => {
              if (response.data) {
                this.codeAvailability = response.data
                // console.log('response.data')
                // console.log(response.data)
              }
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      addStore(data) {
        return new Promise((resolve, reject) => {
          axios
            .post('/api/v1/stores', data)
            .then((response) => {
              // console.log(response)
              this.suppliers.push(response.data.data)
              const notificationStore = useNotificationStore()
              notificationStore.showNotification({
                type: 'success',
                message: global.t('stores.created_message'),
              })
              resolve(response)
            })

            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      updateStore(data) {
        return new Promise((resolve, reject) => {
          axios
            .put(`/api/v1/stores/${data.id}`, data)
            .then((response) => {
              if (response.data) {
                let pos = this.suppliers.findIndex(
                  (supplier) => supplier.id === response.data.data.id
                )
                this.suppliers[pos] = data
                const notificationStore = useNotificationStore()
                notificationStore.showNotification({
                  type: 'success',
                  message: global.t('stores.updated_message'),
                })
              }
              resolve(response)
            })
            .catch((err) => {
              // console.log(err)
              handleError(err)
              reject(err)
            })
        })
      },

      deleteStore(id) {
        const notificationStore = useNotificationStore()
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/stores/delete`, id)
            .then((response) => {
              let index = this.suppliers.findIndex(
                (supplier) => supplier.id === id
              )
              this.suppliers.splice(index, 1)
              notificationStore.showNotification({
                type: 'success',
                message: global.tc('stores.deleted_message', 1),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      deleteMultipleStores() {
        const notificationStore = useNotificationStore()
        // alert(this.selectedSuppliers)
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/stores/delete`, { ids: this.selectedSuppliers })
            .then((response) => {
              this.selectedSuppliers.forEach((supplier) => {
                let index = this.suppliers.findIndex(
                  (_supplier) => _supplier.id === supplier.id
                )
                this.suppliers.splice(index, 1)
              })

              notificationStore.showNotification({
                type: 'success',
                message: global.tc('stores.deleted_message', 2),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      setSelectAllState(data) {
        this.selectAllField = data
      },

      selectedSupplier(data) {
        this.selectedSuppliers = data
        if (this.selectedSuppliers.length === this.suppliers.length) {
          this.selectAllField = true
        } else {
          this.selectAllField = false
        }
      },

      selectAllSuppliers() {
        if (this.selectedSuppliers.length === this.suppliers.length) {
          this.selectedSuppliers = []
          this.selectAllField = false
        } else {
          let allSupplierIds = this.suppliers.map((supplier) => supplier.id)
          this.selectedSuppliers = allSupplierIds
          this.selectAllField = true
        }
      },

      setAddressStub(data) {
        if (!data.billing) this.currentSupplier.billing = { ...addressStub }
        if (!data.shipping) this.currentSupplier.shipping = { ...addressStub }
      },
    },
  })()
}
