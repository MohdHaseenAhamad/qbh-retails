<template>
  <form @submit.prevent="updateCompanyData">
    <BaseSettingCard
      :title="$t('settings.company_info.company_info')"
      :description="$t('settings.company_info.section_description')"
    >
      <BaseInputGrid class="mt-5">
        <BaseInputGroup :label="$tc('settings.company_info.company_logo')">
          <BaseFileUploader
            v-model="previewLogo"
            base64
            @change="onFileInputChange"
            @remove="onFileInputRemove"
          />
        </BaseInputGroup>
      </BaseInputGrid>
      <BaseInputGrid class="mt-5">
        <BaseInputGroup :label="$tc('settings.company_info.company_letter_head')">
          <BaseFileUploader
            v-model="previewletterhead"
            base64
            @change="onLetterInputChange"
            @remove="onLetterInputRemove"
          />
        </BaseInputGroup>
      </BaseInputGrid>
      <BaseInputGrid class="mt-5">
        <BaseInputGroup
          :label="$tc('settings.company_info.company_name')"
          :error="v$.name.$error && v$.name.$errors[0].$message"
          required
        >
          <BaseInput
            v-model="companyForm.name"
            :invalid="v$.name.$error"
            @blur="v$.name.$touch()"
            :disabled=isEditable
          />
        </BaseInputGroup>
        <BaseInputGroup :label="$tc('settings.company_info.company_name_another')">
          <BaseInput v-model="companyForm.name_ar" :disabled=isEditable />
        </BaseInputGroup>

        <BaseInputGroup :label="$tc('settings.company_info.phone')">
          <BaseInput v-model="companyForm.address.phone" :disabled=isEditable />
        </BaseInputGroup>
        <BaseInputGroup :label="$tc('settings.company_info.phone_another')">
          <BaseInput v-model="companyForm.phone_ar" :disabled=isEditable />
        </BaseInputGroup>
        <BaseInputGroup :label="$tc('settings.company_info.state')">
          <BaseInput
            v-model="companyForm.address.state"
            name="state"
            type="text"
            :disabled=isEditable 
          />
        </BaseInputGroup>
        <BaseInputGroup :label="$tc('settings.company_info.state_another')">
          <BaseInput
            v-model="companyForm.state_ar"
            name="state"
            type="text"
            :disabled=isEditable 
          />
        </BaseInputGroup>

        <BaseInputGroup :label="$tc('settings.company_info.city')">
          <BaseInput v-model="companyForm.address.city" type="text" :disabled=isEditable />
        </BaseInputGroup>
        <BaseInputGroup :label="$tc('settings.company_info.city_another')">
          <BaseInput v-model="companyForm.city_ar" type="text" :disabled=isEditable />
        </BaseInputGroup>

        <BaseInputGroup :label="$tc('settings.company_info.zip')">
          <BaseInput v-model="companyForm.address.zip" :disabled=isEditable />
        </BaseInputGroup>
        <BaseInputGroup :label="$tc('settings.company_info.zip_another')">
          <BaseInput v-model="companyForm.zip_ar" :disabled=isEditable />
        </BaseInputGroup>

        <BaseInputGroup :label="$tc('settings.company_info.address')">
          <BaseTextarea
            v-model="companyForm.address.address_street_1"
            rows="2"
            style="margin-top: 8px; margin-bottom: 0px; height: 60px;"
            :disabled=isEditable 
          />
        </BaseInputGroup>
        <BaseInputGroup :label="$tc('settings.company_info.address_another')">
          <BaseTextarea
            v-model="companyForm.address_street_1_ar"
            rows="2"
            style="margin-top: 8px; margin-bottom: 0px; height: 60px;"
            :disabled=isEditable 
          />
        </BaseInputGroup>
        <BaseInputGroup :label="$tc('settings.company_info.address2')">
          <BaseTextarea
            v-model="companyForm.address.address_street_2"
            rows="2"
            :row="2"
            class="mt-2"
            style="margin-top: 8px; margin-bottom: 0px; height: 60px;"
            :disabled=isEditable 
          />
        </BaseInputGroup>
        <BaseInputGroup :label="$tc('settings.company_info.address2_another')">
          <BaseTextarea
            v-model="companyForm.address_street_2_ar"
            rows="2"
            :row="2"
            class="mt-2"
            style="margin-top: 8px; margin-bottom: 0px; height: 60px;"
            :disabled=isEditable 
          />
        </BaseInputGroup>
        <BaseInputGroup
        :label="$tc('settings.company_info.cr')"
        :error="v$.cr.$error && v$.cr.$errors[0].$message"
        required>
          <BaseInput v-model="companyForm.cr" type="text" 
          :invalid="v$.cr.$error"
          @blur="v$.cr.$touch()"
          :disabled=isEditable />
        </BaseInputGroup>
        <BaseInputGroup
        :label="$tc('settings.company_info.cr_another')">
          <BaseInput v-model="companyForm.cr_ar" type="text" :disabled=isEditable />
        </BaseInputGroup>

        <BaseInputGroup
        :label="$tc('settings.company_info.vat')"
        :error="v$.vat.$error && v$.vat.$errors[0].$message"
        required>
          <BaseInput v-model="companyForm.vat" 
          :invalid="v$.vat.$error"
          @blur="v$.vat.$touch()" :disabled=isEditable />
        </BaseInputGroup>
        <BaseInputGroup
          :label="$tc('settings.company_info.vat_another')">
          <BaseInput v-model="companyForm.vat_ar" :disabled=isEditable />
        </BaseInputGroup>
        <BaseInputGroup
          :label="$tc('settings.company_info.country')"
          :error="
            v$.address.country_id.$error &&
            v$.address.country_id.$errors[0].$message
          "
          required
        >
          <BaseMultiselect
            v-model="companyForm.address.country_id"
            label="name"
            :invalid="v$.address.country_id.$error"
            :options="globalStore.countries"
            value-prop="id"
            :can-deselect="true"
            :can-clear="false"
            searchable
            track-by="name"
            :disabled=isEditable
          />
        </BaseInputGroup>
        </BaseInputGrid>

      

      <BaseButton
        v-if="!isEditable"
        :loading="isSaving"
        :disabled="isSaving"
        :type="submit"
        class="mt-6"
      >
        <template #left="slotProps">
          <BaseIcon v-if="!isSaving" :class="slotProps.class" name="SaveIcon" />
        </template>
        {{ $tc('settings.company_info.save') }}
      </BaseButton>
      <BaseButton
        v-else
        :loading="isSaving"
        :disabled="isSaving"
        :type="button"
        @click='isEditable = false'
        class="mt-6"
      >
        <template #left="slotProps">
          <BaseIcon v-if="!isSaving" :class="slotProps.class" name="SaveIcon" />
        </template>
        {{ $tc('settings.company_info.edit') }}
      </BaseButton>

      <div v-if="companyStore.companies.length !== 1" class="py-5">
        <BaseDivider class="my-4" />
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          {{ $t('settings.company_info.delete_company') }}
        </h3>
        <div class="mt-2 max-w-xl text-sm text-gray-500">
          <p>
            {{ $t('settings.company_info.delete_company_description') }}
          </p>
        </div>
        <div class="mt-5">
          <button
            type="button"
            class="
              inline-flex
              items-center
              justify-center
              px-4
              py-2
              border border-transparent
              font-medium
              rounded-md
              text-red-700
              bg-red-100
              hover:bg-red-200
              focus:outline-none
              focus:ring-2
              focus:ring-offset-2
              focus:ring-red-500
              sm:text-sm
            "
            @click="removeCompany"
          >
            {{ $t('general.delete') }}
          </button>
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

const v$ = useVuelidate(
  rules,
  computed(() => companyForm)
)

globalStore.fetchCountries()
globalStore.fetchLicencePlans()

function onFileInputChange(fileName, file, fileCount, fileList) {
  logoFileName.value = fileList.name
  logoFileBlob.value = file
  logo_status = null
}

function onFileInputRemove() {
  logoFileBlob.value = null
  logo_status = 'yes'
}
function onLetterInputChange(fileName, file, fileCount, fileList) {
  letterheadFileName.value = fileList.name
  letterheadFileBlob.value = file
}

function onLetterInputRemove() {
  letterheadFileBlob.value = null
}


async function updateCompanyData() {
  v$.value.$touch()

  if (v$.value.$invalid) {
    return true
  }
  isSaving.value = true

  const res = await companyStore.updateCompany(companyForm)

  if (res.data.data) {
    if (logoFileBlob.value) {
      let logoData = new FormData()
      logoData.append('remove_logo',
        JSON.stringify({
          status: logo_status,
        }));
      logoData.append(
        'company_logo',
        JSON.stringify({
          name: logoFileName.value,
          data: logoFileBlob.value,
        })
      )

      await companyStore.updateCompanyLogo(logoData)
    }
    if (letterheadFileBlob.value) {
      let letterheadData = new FormData()

      letterheadData.append(
        'company_letterhead',
        JSON.stringify({
          name: letterheadFileName.value,
          data: letterheadFileBlob.value,
        })
      )

      await companyStore.updateCompanyLetterHead(letterheadData)
    }


    isSaving.value = false
  }
  isEditable.value = true
  isSaving.value = false
}
function removeCompany(id) {
  modalStore.openModal({
    title: t('settings.company_info.are_you_absolutely_sure'),
    componentName: 'DeleteCompanyModal',
    size: 'sm',
  })
}

const formData = companyStore.licenceForm
const licence_rules = {
  formData: {
    planname: {
      required: helpers.withMessage(t('validation.required'), required),
    }
  },
}

const v2$ = useVuelidate(
  licence_rules,
  { formData },
  {
    $scope: false,
  }
)

const { global } = window.i18n

async function upgradePlan(id) {

  v$.value.$touch()
  if (v$.value.$invalid) {
    return true
  }

  // if(globalStore.licence.data.client_id){
  //   const notificationStore = useNotificationStore()
  //         notificationStore.showNotification({
  //           type: 'success',
  //           message: global.t('settings.company_info.licence.request_already_sent'),
  //         })  
  // }

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
