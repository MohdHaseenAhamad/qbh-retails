import axios from 'axios'
import { defineStore } from 'pinia'
import { useNotificationStore } from './notification'
import { handleError } from '@/scripts/helpers/error-handling'

export const useBankDetailStore = (useWindow = false) => {
  const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore
  const { global } = window.i18n

  return defineStoreFunc({
    id: 'bank-detail',
    state: () => ({
      items: [],
      totalItems: 0,
      selectAllField: false,
      selectedItems: [],
      currentBankDetail: {
        id:null,
        bank_name: '',
        bank_name_ar: '',
        account_name: '',
        account_name_ar: '',
        iban: '',
        iban_ar: '',
        swift_code: '',
        swift_code_ar: '',
        account_no: '',
        account_no_ar: '',
      },
    }),

    getters: {
      isEdit: (state) => (state.currentBankDetail.id ? true : false),
    },
    actions: {
      resetCurrentItem() {
        this.currentBankDetail = {
          id:null,
          bank_name: '',
          bank_name_ar: '',
          account_name: '',
          account_name_ar: '',
          iban: '',
          iban_ar: '',
          swift_code: '',
          swift_code_ar: '',
          account_no: '',
          account_no_ar: '',
        }
      },
      fetchBankDetails(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/bank-detail`, { params })
            .then((response) => {
              this.items = response.data.data
              this.totalItems = response.data.meta.item_total_count

              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchBankDetail(id) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/bank-detail/${id}`)
            .then((response) => {
              if (response.data) {
                this.currentBankDetail = response.data.data
              }
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      addItem(data) {
        return new Promise((resolve, reject) => {
          axios
            .post('/api/v1/bank-detail', data)
            .then((response) => {
              const notificationStore = useNotificationStore()

              this.items.push(response.data.data)

              notificationStore.showNotification({
                type: 'success',
                message: global.t('settings.bankdetail.created_message'),
              })

              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      updateBankDetail(data) {
        console.log(data,'datadatadatadatadata')
        const notificationStore = useNotificationStore()
        return new Promise((resolve, reject) => {
          axios
            .put(`/api/v1/bank-detail/${data.id}`, data)
            .then((response) => {
              if (response.data) {
                let pos = this.items.findIndex(
                  (items) => items.id === response.data.id
                )
                this.items[pos] = data
                notificationStore.showNotification({
                  type: 'success',
                  message: global.t('settings.bankdetail.updated_message'),
                })
              }
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },


      deleteBankDetail(id) {
        return new Promise((resolve, reject) => {
          window.axios
            .delete(`/api/v1/bank-detail/${id}`)
            .then((response) => {
              if (response.data.success) {
                let index = this.items.findIndex(
                  (bankDetail) => bankDetail.id === id
                )
                this.items.splice(index, 1)
                const notificationStore = useNotificationStore()
                notificationStore.showNotification({
                  type: 'success',
                  message: global.t('settings.bankdetail.deleted_message'),
                })
              }
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      }
    },
  })()
}
