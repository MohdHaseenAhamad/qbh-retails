import { defineStore } from 'pinia'

export const useModalStore = (useWindow = false) => {
  const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore
  const { global } = window.i18n

  return defineStoreFunc({
    id: 'modal',

    state: () => ({
      active: false,
      content: '',
      title: '',
      componentName: '',
      id: '',
      size: 'md',
      data: null,
      refreshData: null,
      variant: '',
      option1: ''
    }),

    getters: {
      isEdit() {
        return (this.id ? true : false)
      }
    },

    actions: {
      openModal(payload) {
        this.componentName = payload.componentName
        this.active = true

        if (payload.id) {
          this.id = payload.id
        }

        this.title = payload.title

        if (payload.data) {
          this.data = payload.data
        }

        if (payload.refreshData) {
          this.refreshData = payload.refreshData
        }

        if (payload.variant) {
          this.variant = payload.variant
        }

        if (payload.size) {
          this.size = payload.size
        }

        if (payload.option1) {
          this.option1 = payload.option1
        }
      },

      resetModalData() {
        this.content = ''
        this.title = ''
        this.componentName = ''
        this.id = ''
        this.data = null
        this.refreshData = null
        this.option1=''
      },

      closeModal() {
        this.active = false

        setTimeout(() => {
          this.resetModalData()
        }, 300)
      }
    }
  })()
}
