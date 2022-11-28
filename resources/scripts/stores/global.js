import { defineStore } from 'pinia'
import { useCompanyStore } from './company'
import { useUserStore } from './user'
import axios from 'axios'
import { handleError } from '@/scripts/helpers/error-handling'
import _ from 'lodash'
import { useNotificationStore } from '@/scripts/stores/notification'
import router from '@/scripts/router'
import Ls from '../services/ls'

export const useGlobalStore = (useWindow = false) => {
  const defineStoreFunc = useWindow ? window.pinia.defineStore : defineStore
  const { global } = window.i18n

  return defineStoreFunc({
    id: 'global',
    state: () => ({
      // Global Configuration
      config: null,

      // Global Lists
      timeZones: [],
      dateFormats: [],
      currencies: [],
      countries: [],
      languages: [],
      fiscalYears: [],

      // Menus
      mainMenu: [],
      settingMenu: [],

      // Boolean Flags
      isAppLoaded: false,
      isSidebarOpen: false,
      areCurrenciesLoading: false,
      
      licence: [],
      licences: [],
      licenceRequest: [],
      licencePurchased: false,
      licenceExpired: false,
      licenceExpiringInDays: 30,
      licenceEndPoint: null,
      licenceRequestSent: false,
      licenceSynced: false,

      // USED FOR TEMPLATE ACCESS
      allowedTemplates: [],

      downloadReport: null,
    }),

    getters: {
      menuGroups: (state) => {
        return Object.values(_.groupBy(state.mainMenu, 'group'))
      },
    },

    actions: {
      bootstrap() {
        return new Promise((resolve, reject) => {
          axios
            .get('/api/v1/bootstrap')
            .then((response) => {

              console.log(response.data.licence)
              // SET TEMPLATE ABILITIES
              this.allowedTemplates = response.data.current_user_template_abilities
              // console.log('this.allowedTemplates')
              // console.log(this.allowedTemplates)

              this.licence = response.data.licence;
              // SET ENDPOINT
              if(this.licence)
                this.licenceEndPoint = this.licence.endpoint
              // console.log(this.licenceEndPoint)
              // console.log(this.licence)
              // return false

              if(this.licence.data.licence_key && !this.licenceSynced){
                // SET REQUEST SET
                this.licenceRequestSent = true

                // LICENCE SYNC WITH CLOUD
                this.syncLicence();

                this.licenceSynced = true;

                // RECALL TO SET THE SYNCED LICENCE DATA
                // this.bootstrap();
                // window.location.href = window.origin+'/admin/dashboard'
              }

              // console.log('return true')
              // return true;

              // console.log('stop')
              this.licenceExpiredInDays(response.data.licence.data.expire_at)

              // REDIRECT IF LICENCE EXPIRED
              if(this.licenceExpired){
                // window.location.href = '/licence_expired'
                router.push('/licence/expired')
              }

              // SHOW ALERT IF LICENCE EXPIRING WITHIN 5 DAYS
              if(this.licenceExpiringInDays <= 5){
                const notificationStore = useNotificationStore()
                      notificationStore.showNotification({
                        type: 'error',
                        message: global.t('settings.company_info.licence.expiring_soon', {
                          no_of_days: this.licenceExpiringInDays,
                        }),
                      })  
              }

              // console.log('this.licence')
              // console.log(this.licence)
              // if(this.licence.data.plan_id){
              //   this.licencePurchased = true
              //   // SYNC LICENCE
              //   // Code...
              // }
              // else{
              //   if(this.licence.data.client_id){
              //     let plan_client = {planId: 5, clientId: this.licence.data.client_id}
              //     // ATTEMPT TO PURCHASE LICENCE
              //     let licence_response = this.subscribeLicencePlan(plan_client)
              //     console.log('subscribeLicencePlan')
              //     console.log(licence_response)
              //   }
              // }


              // company store
              const companyStore = useCompanyStore()
              companyStore.companies = response.data.companies
              companyStore.selectedCompany = response.data.current_company
              companyStore.setSelectedCompany(response.data.current_company)
              companyStore.selectedCompanySettings =
                response.data.current_company_settings
              companyStore.selectedCompanyCurrency =
                response.data.current_company_currency

             // console.log('response.data.main_menu')
              // console.log(response.data.main_menu)
              this.mainMenu = response.data.main_menu
              this.settingMenu = response.data.setting_menu
              // console.log('this.settingMenu')
              // console.log(this.settingMenu)

              this.config = response.data.config

              // user store
              const userStore = useUserStore()
              userStore.currentUser = response.data.current_user
              userStore.currentUserSettings =
                response.data.current_user_settings
              userStore.currentAbilities = response.data.current_user_abilities

              global.locale =
                response.data.current_user_settings.language || 'en'

              this.isAppLoaded = true
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      resetLicenceDetails(){
        this.licence = [];
        this.licences = [];
        this.licenceRequest = [];
        this.licencePurchased = false;
        this.licenceExpired = false;
        this.licenceExpiringInDays = 30;
        this.licenceRequestSent = false;
      },

      syncLicence(){
        axios.defaults.withCredentials = false

        return new Promise((resolve, reject) => {
          axios
            .get(this.licenceEndPoint+'client/'+this.licence.data.client_id)
            // .get(this.licenceEndPoint+'client/2')
            .then((response) => {
              // console.log('sync licence')
              // console.log(response)
              // console.log(response.data.plans.length)

              if(response.data.plans.length != 0){

                // console.log(`expiring at ${response.data.plans[0].subscriptions.expireAt}`)
                // alert(response.data.plans[0].subscriptions.expireAt)
                if(response.data.plans[0].subscriptions.status)
                  this.licenceExpiredInDays(response.data.plans[0].subscriptions.expireAt)

                // console.log('calling updateClientLicence....')
                const result = this.updateClientLicence(response.data.plans[0])
                console.log('result')
              }

              // Object.assign(this.licences, response.data.plans)
              // this.setAddressStub(response.data.data)
              resolve(response)
            })
            .catch((err) => {
              // handleError(err)
              reject(err)
            })
        })
      },

      licenceExpiredInDays(expire_at){
        // alert(expire_at)
        // alert(this.licenceExpired)
        const today = new Date();
        const end = new Date(expire_at);
        const diffTime = Math.abs(end - today);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
        // console.log(diffTime + " milliseconds");
        // console.log(diffDays + " days");

        // console.log(today)
        // console.log(end)
        // alert(diffDays)

        if (end > today) {    
          this.licenceExpiringInDays = diffDays;
          // alert('Licence Expiring in days: '+this.licenceExpiringInDays);
          this.licenceExpired = false;
        }
        else {    

          if(diffDays == 1){
            this.licenceExpiringInDays = 0;
            // console.log('Licence Expiring today...');
            this.licenceExpired = false;
          }
          else{
            this.licenceExpiringInDays = null;
            this.licenceExpired = true;
            // console.log('Licence Expired');
          }
        }    
        // alert(this.licenceExpired)
      },

      subscribeLicencePlan(data){
        axios.defaults.withCredentials = false
        // console.log('subscribing')
        // console.log(data)
        return new Promise((resolve, reject) => {
          axios
            .post(this.licenceEndPoint+'plans/subscribe', data, {
              headers: {
                'Content-Type': 'application/json'
              }
            })
            .then((response) => {
              // console.log(response)
              Object.assign(this.licences, response.data.plans)
              // this.setAddressStub(response.data.data)
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchLicencePlans() {

        axios.defaults.withCredentials = false

        return new Promise((resolve, reject) => {
          axios
            .get(this.licenceEndPoint+'plans')
            .then((response) => {
              // console.log('licences')
              // console.log(response)
              Object.assign(this.licences, response.data.plans)
              // this.setAddressStub(response.data.data)
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      submitLicenceRequest(data){
        axios.defaults.withCredentials = false
        // console.log(data)
        // return true
        return new Promise((resolve, reject) => {
          axios
            .post(this.licenceEndPoint+'client', data, {
              headers: {
                'Content-Type': 'application/json'
              }
            })
            .then((response) => {
              // console.log('external response')
              // console.log(response)
              // this.updateClientLicence(response.data)
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      updateClientLicence(data) {
        let licence_data = {...data, ...this.licence}
        // console.log('updateClientLicence')
        // console.log(this.licence)
        // return true;
        return new Promise((resolve, reject) => {
          axios
            .post('/api/v1/set_licence_client', licence_data)
            .then((response) => {
              // console.log('internal response')
              // console.log(response)
              // this.customers.push(response.data.data)
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      setAllowedTempletes(templates){
        const allowed = [];
        for (const key in templates) {  
          if(this.allowedTemplates.includes(templates[key].name)){
            allowed.push(templates[key])
          }
        }
        return allowed
      },

      fetchCurrencies() {
        return new Promise((resolve, reject) => {
          
          this.areCurrenciesLoading = true
          axios
            .get('/api/v1/currencies')
            .then((response) => {
              this.currencies = response.data.data.filter((currency) => {
                return (currency.name = `${currency.code} - ${currency.name}`)
              })
              this.areCurrenciesLoading = false
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              this.areCurrenciesLoading = false
              reject(err)
            })

        })
      },

      fetchCurrencies() {
        return new Promise((resolve, reject) => {
          if (this.currencies.length || this.areCurrenciesLoading) {
            resolve(this.currencies)
          } else {
            this.areCurrenciesLoading = true
            axios
              .get('/api/v1/currencies')
              .then((response) => {
                this.currencies = response.data.data.filter((currency) => {
                  return (currency.name = `${currency.code} - ${currency.name}`)
                })
                this.areCurrenciesLoading = false
                resolve(response)
              })
              .catch((err) => {
                handleError(err)
                this.areCurrenciesLoading = false
                reject(err)
              })
          }
        })
      },

      fetchConfig(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/config`, { params })
            .then((response) => {
              if (response.data.languages) {
                this.languages = response.data.languages
              } else {
                this.fiscalYears = response.data.fiscal_years
              }
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      fetchDateFormats() {
        return new Promise((resolve, reject) => {
          if (this.dateFormats.length) {
            resolve(this.dateFormats)
          } else {
            axios
              .get('/api/v1/date/formats')
              .then((response) => {
                this.dateFormats = response.data.date_formats
                resolve(response)
              })
              .catch((err) => {
                handleError(err)
                reject(err)
              })
          }
        })
      },

      fetchTimeZones() {
        return new Promise((resolve, reject) => {
          if (this.timeZones.length) {
            resolve(this.timeZones)
          } else {
            axios
              .get('/api/v1/timezones')
              .then((response) => {
                this.timeZones = response.data.time_zones
                resolve(response)
              })
              .catch((err) => {
                handleError(err)
                reject(err)
              })
          }
        })
      },

      fetchCountries() {
        return new Promise((resolve, reject) => {
          if (this.countries.length) {
            resolve(this.countries)
          } else {
            axios
              .get('/api/v1/countries')
              .then((response) => {
                this.countries = response.data.data
                resolve(response)
              })
              .catch((err) => {
                handleError(err)
                reject(err)
              })
          }
        })
      },

      fetchPlaceholders(params) {
        return new Promise((resolve, reject) => {
          axios
            .get(`/api/v1/number-placeholders`, { params })
            .then((response) => {
              resolve(response)
            })
            .catch((err) => {
              handleError(err)
              reject(err)
            })
        })
      },

      setSidebarVisibility(val) {
        this.isSidebarOpen = val
      },

      setIsAppLoaded(isAppLoaded) {
        this.isAppLoaded = isAppLoaded
      },
    },
  })()
}
