<template>
  <form @submit.prevent="updateCompanyData">
    <BaseSettingCard
      :title="$t('settings.company_info.licence.subscription')"
    >


      <div class="bg-gray-200 px-7">

      <BaseDivider class="my-6" />
      
      <h3 class="text-lg leading-6 font-medium text-gray-900 my-2">
        <b>{{ $t('settings.company_info.licence.detail') }}</b>
      </h3>

      <div v-if="globalStore.licence.is_trial" class="pb-5">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          {{ $tc('settings.company_info.licence.trial_plan') }}
        </h3>
        <div class="mt-2 max-w-xl text-sm text-red-700">
          {{ $tc('settings.company_info.licence.paid_description', {
                formattedDate: new Date(globalStore.licence.data.expire_at).toLocaleDateString("en-US", { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }),
                date: globalStore.licence.data.expire_at
              }) 
            }}
        </div>
        <BaseInputGrid class="mt-5">

          <BaseInputGroup>
           <button
            type="submit"
            class="
              mt-5
              inline-flex
              items-center
              justify-center
              px-4
              py-2
              border border-transparent
              font-medium
              rounded-md
              text-green-700
              bg-green-100
              hover:bg-green-200
              focus:outline-none
              focus:ring-2
              focus:ring-offset-2
              focus:ring-black-500
              sm:text-sm
            "
            @click="upgradePlan"
          >
            {{ $tc('settings.company_info.licence.upgrade') }}
          </button>

          </BaseInputGroup>

        </BaseInputGrid>
      </div>

      <div class="pb-5" v-else>
        <h3 class="text-lg leading-6 font-medium text-green-700">
          {{ $tc('settings.company_info.licence.paid_plan') }}
        </h3>
        <div class="mt-2 max-w-xl text-sm text-gray-500">
          <p>
            {{ $tc('settings.company_info.licence.paid_description', {
                formattedDate: new Date(globalStore.licence.data.expire_at).toLocaleDateString("en-US", { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
              }) 
            }}
          </p>
        </div>
      </div>

      </div>

    </BaseSettingCard>
  </form>
  <DeleteCompanyModal />
  <UpgradeCompanyModal />
</template>

<script setup>
import { reactive, ref, inject, computed } from 'vue'
import { useGlobalStore } from '@/scripts/stores/global'
import { useCompanyStore } from '@/scripts/stores/company'
import { useI18n } from 'vue-i18n'
import { required, minLength, helpers, url } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'
import { useModalStore } from '@/scripts/stores/modal'
import DeleteCompanyModal from '@/scripts/components/modal-components/DeleteCompanyModal.vue'
import UpgradeCompanyModal from '@/scripts/components/modal-components/UpgradeCompanyModal.vue'
import { useNotificationStore } from '@/scripts/stores/notification'

const companyStore = useCompanyStore()
const globalStore = useGlobalStore()
const modalStore = useModalStore()
const { t } = useI18n()
const utils = inject('utils')

let isSaving = ref(false)

const companyForm = reactive({
  name: null,
  name_ar: null,
  logo: null,
  letterhead: null,
  address: {
    address_street_1: '',
    address_street_2: '',
    website: '',
    country_id: null,
    state: '',
    city: '',
    phone: '',
    zip: '',
  },
  address_street_1_ar: null,
  address_street_2_ar: null,
  state_ar: null,
  city_ar: null,
  phone_ar: null,
  zip_ar: null,
  cr:null,
  vat:null,
  account_name:null,
  bank_name:null,
  account_no:null,
  iban:null,
  swift_code:null,
  cr_ar:null,
  vat_ar:null,
  account_name_ar:null,
  bank_name_ar:null,
  account_no_ar:null,
  iban_ar:null,
  swift_code_ar:null,
})

utils.mergeSettings(companyForm, {
  ...companyStore.selectedCompany,
})

let previewLogo = ref([])
let logoFileBlob = ref(null)
let logoFileName = ref(null)
let previewletterhead = ref([])
let letterheadFileBlob = ref(null)
let letterheadFileName = ref(null)
let logo_status = ref(null)
let isEditable = ref(true)



if (companyForm.logo) {
  console.log(companyForm);
  previewLogo.value.push({
    image: companyForm.logo,
  })
}
if (companyForm.letterhead) {
  previewletterhead.value.push({
    image: companyForm.letterhead,
  })
}

const rules = computed(() => {
  return {
    name: {
      required: helpers.withMessage(t('validation.required'), required),
      minLength: helpers.withMessage(
        t('validation.name_min_length'),
        minLength(3)
      ),
    },
    address: {
      country_id: {
        required: helpers.withMessage(t('validation.required'), required),
      },
    },
    cr: {
      required: helpers.withMessage(t('validation.required'), required),
      minLength: helpers.withMessage(
        t('validation.name_min_length', { count: 3 }),
        minLength(3)
      ),
    },
    vat: {
      required: helpers.withMessage(t('validation.required'), required),
    }
  }
})

globalStore.fetchLicencePlans()

const { global } = window.i18n

async function upgradePlan(id) {

  if(globalStore.licenceRequestSent){
    const notificationStore = useNotificationStore()
          notificationStore.showNotification({
            type: 'success',
            message: global.t('settings.company_info.licence.request_already_sent'),
          })  
    return true;
  }

  // alert(globalStore.licenceRequest.id)

  // console.log('companyStore.selectedCompany');
  // console.log(companyStore.selectedCompany);
  setLicenceForm()

  modalStore.openModal({
    title: t('settings.company_info.licence.upgrade'),
    componentName: 'UpgradeCompanyModal',
    size: 'sm',
  })
  // let response = await globalStore.fetchLicencePlans()
  // console.log(response)
}

function setLicenceForm(){
  
  let address = '';
  if(companyStore.selectedCompany.address.address_street_1)
    companyStore.selectedCompany.address.address_street_1+", "
  address += companyStore.selectedCompany.address.address_street_2 ?? ''

  companyStore.licenceForm.companyName = companyStore.selectedCompany.name ?? 'N/A',
  companyStore.licenceForm.lastname = companyStore.selectedCompany.name_ar ?? 'N/A',
  companyStore.licenceForm.address = address ?? 'N/A',
  companyStore.licenceForm.country = companyStore.selectedCompany.address.country.name ?? 'N/A',
  companyStore.licenceForm.state = companyStore.selectedCompany.address.state ?? 'N/A',
  companyStore.licenceForm.city = companyStore.selectedCompany.address.city ?? 'N/A',
  companyStore.licenceForm.pincode = companyStore.selectedCompany.address.zip ?? 'N/A',
  companyStore.licenceForm.phone= companyStore.selectedCompany.address.phone ?? 'N/A'
}

</script>
