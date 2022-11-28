import axios from 'axios'
import { defineStore } from 'pinia'
import { useNotificationStore } from './notification'
import { handleError } from '@/scripts/helpers/error-handling'

export const usePreparedByStore = (useWindow = false) => {
  const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore
  const { global } = window.i18n

  return defineStoreFunc({
    id: 'prepared-by',
    state: () => ({
      items: [],
      totalItems: 0,
      selectAllField: false,
      selectedItems: [],
      currentPreparedBy: {
        name: '',
        designation: '',
        contact_number: '',
        email: '',
        signature: '',
      },
    }),
    getters: {
      isEdit: (state) => (state.currentPreparedBy.id ? true : false),
    },
    actions: {
      resetCurrentItem() {
        this.currentPreparedBy = {
          name: '',
          designation: '',
          contact_number: '',
          email: '',
          signature: '',
        }
      },
      fetchPreparedBies(params) {
        console.log(params,'tttt')
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/prepared-by`, { params })
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

      fetchPreparedBy(id) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/prepared-by/${id}`)
            .then((response) => {
              if (response.data) {
                Object.assign(this.currentPreparedBy, response.data.data)
              }
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      addItem(data, id='') {
        return new Promise((resolve, reject) => {
          axios
            .post('/api/v1/prepared-by', data)
            .then((response) => {
              const notificationStore = useNotificationStore()

              this.items.push(response.data.data)

              notificationStore.showNotification({
                type: 'success',
                message: global.t('settings.customization.prepared_by.prepared_by_added'),
              })

              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },
      
      updatePreparedBy(data, id) {
        const notificationStore = useNotificationStore()

        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/prepared-by/${id}?_method=PUT`, data)
            .then((response) => {
              let pos = this.items.findIndex(
                (unit) => unit.id === response.data.data.id
              )

              this.items[pos] = data

              if (response.data.data) {
                notificationStore.showNotification({
                  type: 'success',
                  message: global.t(
                    'settings.customization.prepared_by.prepared_by_updated'
                  ),
                })
              }

              if (response.data.errors) {
                notificationStore.showNotification({
                  type: 'error',
                  message: err.response.data.errors[0],
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

      deletePreparedBy(id) {
        const notificationStore = useNotificationStore()
        return new Promise((resolve, reject) => {
          axios
            .delete(`/api/v1/prepared-by/${id}`)
            .then((response) => {
              if (!response.data.error) {
                let index = this.items.findIndex((preparedBy) => preparedBy.id === id)
                this.items.splice(index, 1)
              }

              if (response.data.success) {
                notificationStore.showNotification({
                  type: 'success',
                  message: global.t(
                    'settings.customization.prepared_by.deleted_message'
                  ),
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
