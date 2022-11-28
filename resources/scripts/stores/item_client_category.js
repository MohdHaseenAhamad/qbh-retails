import axios from 'axios'
import { defineStore } from 'pinia'
import { handleError } from '@/scripts/helpers/error-handling'

export const useCategoryStore = (useWindow = false) => {
  const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore
  const { global } = window.i18n

  return defineStoreFunc({
    id: 'categories',

    state: () => ({
      categories: [],
      currentCategory: {
        name: '',
        code: '',
        type: ''
      },
      currentCategoryId: null,
      codeAvailability: {
        status: true
      }
    }),

    getters: {
      isEdit: (state) => (state.currentCategory.id ? true : false),
    },

    actions: {
      resetCurrentCategory() {
        this.currentCategory = {
          name: '',
          code: '',
          type: '',
        },
       this.currentCategoryId = null
      },

      fetchCategories(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/item_client/categories`, { params })
            .then((response) => {
              this.categories = response.data.data
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchCategory(id) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/item_client/categories/${id}`)
            .then((response) => {
              this.currentCategory = response.data.data
              this.currentCategoryId = response.data.data.id
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      addCategory(data) {
        // console.log('working')
        // return;
        return new Promise((resolve, reject) => {
          axios
            .post('/api/v1/item_client/categories', data)
            .then((response) => {
              this.categories.push(response.data)
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      updateCategory(data) {
        return new Promise((resolve, reject) => {
          axios
            .put(`/api/v1/item_client/categories/${data.id}`, data)
            .then((response) => {
              if (response.data) {
                let pos = this.categories.findIndex(
                  (notes) => notes.id === response.data.data.id
                )
                this.categories[pos] = data.notes
              }
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      checkCategoryCodeAvailability(data) {
        return new Promise((resolve, reject) => {
          axios
            .post(`/api/v1/item_client/categories/code_availability`, data)
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

      deleteCategory(id) {
        return new Promise((resolve, reject) => {
          axios
            .delete(`/api/v1/item_client/categories/${id}`)
            .then((response) => {
              let index = this.categories.findIndex((note) => note.id === id)
              this.categories.splice(index, 1)
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },
    },
  })()
}
