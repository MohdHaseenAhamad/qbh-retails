import axios from 'axios'
import { defineStore } from 'pinia'
import { useRoute } from 'vue-router'
import { handleError } from '@/scripts/helpers/error-handling'
import { useNotificationStore } from '@/scripts/stores/notification'
import { useGlobalStore } from '@/scripts/stores/global'
import { useCompanyStore } from '@/scripts/stores/company'
import addressStub from '@/scripts/stub/address.js'
import customerStub from '@/scripts/stub/customer'
import { useCategoryStore } from '@/scripts/stores/item_client_category'

export const useSupplierStore = (useWindow = false) => {
  const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore
  const { global } = window.i18n

  return defineStoreFunc({
    id: 'supplier',
    state: () => ({
      suppliers: [],
      totalSuppliers: 0,
      selectAllField: false,
      selectedSuppliers: [],
      selectedViewSupplier: {},
      isFetchingInitialSettings: false,
      isFetchingViewData: false,
      currentSupplier: {
        ...customerStub(),
      },
    }),

    getters: {
      isEdit: (state) => (state.currentSupplier.id ? true : false),
    },

    actions: {
      resetCurrentSupplier() {
        this.currentSupplier = {
          ...customerStub(),
        }
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
        const categoryStore = useCategoryStore()

        this.isFetchingInitialSettings = true
        let editActions = []
        if (isEdit) {
          editActions = [this.fetchSupplier(route.params.id)]
        } else {
          this.currentSupplier.currency_id =
            companyStore.selectedCompanyCurrency.id
        }

        Promise.all([
          globalStore.fetchCurrencies(),
          globalStore.fetchCountries(),
          categoryStore.fetchCategories({'type': 'client', 'name_with_code':true}),
          ...editActions,
        ])
          .then(async ([res1, res2, res3]) => {
            this.isFetchingInitialSettings = false
          })
          .catch((error) => {
            handleError(error)
          })
      }, 

      fetchSuppliers(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/suppliers`, { params })
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

      fetchViewSupplier(params) {
        return new Promise((resolve, reject) => {
          this.isFetchingViewData = true
          axios
            .get(`/api/v1/suppliers/${params.id}/stats`, { params })

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

      fetchSupplier(id) {
        return new Promise((resolve, reject) => {
          // console.log(`/api/v1/suppliers/${id}`)
          axios
            .get(`/api/v1/suppliers/${id}`)
            .then((response) => {
              // console.log('response')
              // console.log(response)
              Object.assign(this.currentSupplier, response.data.data)
              this.setAddressStub(response.data.data)
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      addSupplier(data) {
        // console.log(data);
        // alert('stop');
        return new Promise((resolve, reject) => {
          axios
            .post('/api/v1/suppliers', data)
            .then((response) => {
              this.suppliers.push(response.data.data)
              const notificationStore = useNotificationStore()
              notificationStore.showNotification({
                type: 'success',
                message: global.t('suppliers.created_message'),
              })
              resolve(response)
            })

            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      updateSupplier(data) {
        return new Promise((resolve, reject) => {
          axios
            .put(`/api/v1/suppliers/${data.id}`, data)
            .then((response) => {
              if (response.data) {
                let pos = this.suppliers.findIndex(
                  (supplier) => supplier.id === response.data.data.id
                )
                this.suppliers[pos] = data
                const notificationStore = useNotificationStore()
                notificationStore.showNotification({
                  type: 'success',
                  message: global.t('suppliers.updated_message'),
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

      deleteSupplier(id) {
        const notificationStore = useNotificationStore()
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/suppliers/delete`, id)
            .then((response) => {
              let index = this.suppliers.findIndex(
                (supplier) => supplier.id === id
              )
              this.suppliers.splice(index, 1)
              notificationStore.showNotification({
                type: 'success',
                message: global.tc('suppliers.deleted_message', 1),
              })
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      deleteMultipleSuppliers() {
        const notificationStore = useNotificationStore()
        // alert(this.selectedSuppliers)
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/suppliers/delete`, { ids: this.selectedSuppliers })
            .then((response) => {
              this.selectedSuppliers.forEach((supplier) => {
                let index = this.suppliers.findIndex(
                  (_supplier) => _supplier.id === supplier.id
                )
                this.suppliers.splice(index, 1)
              })

              notificationStore.showNotification({
                type: 'success',
                message: global.tc('suppliers.deleted_message', 2),
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
