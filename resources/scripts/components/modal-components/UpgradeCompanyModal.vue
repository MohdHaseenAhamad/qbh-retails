<template>
  <BaseModal :show="modalActive" @close="closeCompanyModal">
    <div class="flex justify-between w-full">
      <div class="px-6 pt-6">
        <h6 class="font-medium text-lg text-left">
          {{ modalStore.title }}
        </h6>
        <p
          class="mt-2 text-sm leading-snug text-gray-500"
          style="max-width: 680px"
        >
          {{
            $t('settings.company_info.licence.upgrade_description', {
              company: companyStore.selectedCompany.name,
            })
          }}
        </p>
      </div>
    </div>
    <form action="" @submit.prevent="submitCompanyData">
      <div class="p-4 sm:p-6 space-y-4">
        
        <BaseInputGrid class="mt-5">

          <BaseInputGroup
            :error="v$.formData.planid.$error && v$.formData.planid.$errors[0].$message"
            :label="
              $t('settings.company_info.licence.choose_plan', {
                company: companyStore.selectedCompany.name,
              })
            "
          >
          
            <BaseMultiselect 
              required
              v-model="companyStore.licenceForm.planid"
              label="planname"
              :options="globalStore.licences"
              value-prop="id"
              :can-deselect="false"
              :can-clear="false"
              :placeholder="$t('settings.company_info.licence.select_plan')"
              searchable
              track-by="id"
            />
          </BaseInputGroup>

          <BaseInputGroup
            :label="$tc('settings.company_info.company_name')"
            required
          >
            <BaseInput
              v-model="companyStore.licenceForm.companyName"
              disabled
            />
          </BaseInputGroup>

          <BaseInputGroup
            :label="$tc('settings.company_info.licence.form.firstname')"
            required
            :error="v$.formData.firstname.$error && v$.formData.firstname.$errors[0].$message"
          >
            <BaseInput
              v-model="companyStore.licenceForm.firstname"
              :invalid="v$.formData.firstname.$error"
              @input="v$.formData.firstname.$touch()"
            />
          </BaseInputGroup>

          <BaseInputGroup
            :label="$tc('settings.company_info.licence.form.lastname')"
            required
            :error="v$.formData.firstname.$error && v$.formData.firstname.$errors[0].$message"
          >
            <BaseInput
              v-model="companyStore.licenceForm.lastname"
              :invalid="v$.formData.lastname.$error"
              @input="v$.formData.lastname.$touch()"
            />
          </BaseInputGroup>

          <BaseInputGroup
            :label="$tc('settings.company_info.licence.form.address')"
            required
            :error="v$.formData.address.$error && v$.formData.address.$errors[0].$message"
          >
            <BaseInput
              v-model="companyStore.licenceForm.address"
              :invalid="v$.formData.address.$error"
              @input="v$.formData.address.$touch()"
            />
          </BaseInputGroup>

          <BaseInputGroup
            :label="$tc('settings.company_info.licence.form.country')"
            required
            :error="v$.formData.country.$error && v$.formData.country.$errors[0].$message"
          >
            <BaseInput
              v-model="companyStore.licenceForm.country"
              :invalid="v$.formData.country.$error"
              @input="v$.formData.country.$touch()"
            />
          </BaseInputGroup>

          <BaseInputGroup
            :label="$tc('settings.company_info.licence.form.state')"
            required
            :error="v$.formData.state.$error && v$.formData.state.$errors[0].$message"
          >
            <BaseInput
              v-model="companyStore.licenceForm.state"
              :invalid="v$.formData.state.$error"
              @input="v$.formData.state.$touch()"
            />
          </BaseInputGroup>

          <BaseInputGroup
            :label="$tc('settings.company_info.licence.form.city')"
            required
            :error="v$.formData.city.$error && v$.formData.city.$errors[0].$message"
          >
            <BaseInput
              v-model="companyStore.licenceForm.city"
              :invalid="v$.formData.city.$error"
              @input="v$.formData.city.$touch()"
            />
          </BaseInputGroup>

          <BaseInputGroup
            :label="$tc('settings.company_info.licence.form.pincode')"
            required
            :error="v$.formData.pincode.$error && v$.formData.pincode.$errors[0].$message"
          >
            <BaseInput
              v-model="companyStore.licenceForm.pincode"
              :invalid="v$.formData.pincode.$error"
              @input="v$.formData.pincode.$touch()"
            />
          </BaseInputGroup>

          <BaseInputGroup
            :label="$tc('settings.company_info.licence.form.email')"
            required
            :error="v$.formData.email.$error && v$.formData.email.$errors[0].$message"
          >
            <BaseInput
              v-model="companyStore.licenceForm.email"
              :invalid="v$.formData.email.$error"
              @input="v$.formData.email.$touch()"
            />
          </BaseInputGroup>

          <BaseInputGroup
            :label="$tc('settings.company_info.licence.form.phone')"
            required
            :error="v$.formData.phone.$error && v$.formData.phone.$errors[0].$message"
          >
            <BaseInput
              v-model="companyStore.licenceForm.phone"
              :invalid="v$.formData.phone.$error"
              @input="v$.formData.phone.$touch()"
            />
          </BaseInputGroup>

        </BaseInputGrid>

      </div>

      <div class="z-0 flex justify-end p-4 bg-gray-50 border-modal-bg">
        <BaseButton
          class="mr-3 text-sm"
          variant="primary-outline"
          outline
          type="button"
          @click="closeCompanyModal"
        >
          {{ $t('general.cancel') }}
        </BaseButton>
        <BaseButton
          :loading="isDeleting"
          variant="success"
          type="submit"
        >
          {{ $t('settings.company_info.licence.upgrade_request') }}
        </BaseButton>
      </div>
    </form>
  </BaseModal>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useModalStore } from '@/scripts/stores/modal'
import { computed, onMounted, ref, reactive, inject } from 'vue'
import { useI18n } from 'vue-i18n'
import { required, minLength, helpers, sameAs } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'
import { useCompanyStore } from '@/scripts/stores/company'
import { useGlobalStore } from '@/scripts/stores/global'
import { useNotificationStore } from '@/scripts/stores/notification'

const utils = inject('utils')
const companyStore = useCompanyStore()
const modalStore = useModalStore()
const globalStore = useGlobalStore()
const router = useRouter()
const { t } = useI18n()
let isDeleting = ref(false)

const formData = companyStore.licenceForm

const modalActive = computed(() => {
  return modalStore.active && modalStore.componentName === 'UpgradeCompanyModal'
})

const rules = {
  formData: {
    firstname: {
      required: helpers.withMessage(t('validation.required'), required),
    },
    planid: {
      required: helpers.withMessage(t('validation.required'), required),
    },
    lastname: {
      required: helpers.withMessage(t('validation.required'), required),
    },
    address: {
      required: helpers.withMessage(t('validation.required'), required),
    },
    country: {
      required: helpers.withMessage(t('validation.required'), required),
    },
    state: {
      required: helpers.withMessage(t('validation.required'), required),
    },
    city: {
      required: helpers.withMessage(t('validation.required'), required),
    },
    pincode: {
      required: helpers.withMessage(t('validation.required'), required),
    },
    companyName: {
      required: helpers.withMessage(t('validation.required'), required),
    },
    email: {
      required: helpers.withMessage(t('validation.required'), required),
    },
    phone: {
      required: helpers.withMessage(t('validation.required'), required),
    },
  },
}

const v$ = useVuelidate(
  rules,
  { formData },
  {
    $scope: false,
  }
)

const { global } = window.i18n

async function submitCompanyData() {
  v$.value.$touch()
  if (v$.value.$invalid) {
    return true
  }

  // console.log(companyStore.licenceForm)
  // alert(companyStore.licenceForm.planid)

  // return true
  isDeleting.value = true

  try {
    const res = await globalStore.submitLicenceRequest(JSON.stringify(companyStore.licenceForm))
    console.log('res')
    console.log(res)

    if(res){
      console.log('inside result')
      let client_request = {
        planId: companyStore.licenceForm.planid,
        clientId: res.data.id
      }
      console.log(client_request)
      const subscribe = await globalStore.subscribeLicencePlan(JSON.stringify(client_request))
      console.log(subscribe)

      if(subscribe)
      {
        console.log('subscribe')
        console.log(subscribe)
        console.log(subscribe.data.plans[0].subscriptions.clientId)
        console.log(subscribe.data.plans[0].subscriptions.planId)
        console.log(subscribe.data.plans[0].subscriptions.id)
        // return true;
        try {
          const result = await globalStore.updateClientLicence(subscribe.data.plans[0])  
          
          if(result.data.status){
            globalStore.licenceRequestSent = true;

            const notificationStore = useNotificationStore()
              notificationStore.showNotification({
                type: 'success',
                message: global.t('settings.company_info.licence.request_sent'),
              })  
          }
          else{
            const notificationStore = useNotificationStore()
              notificationStore.showNotification({
                type: 'error',
                message: global.t('settings.company_info.licence.request_error_internal'),
              })  
          }
        } 
        catch {
          const notificationStore = useNotificationStore()
              notificationStore.showNotification({
                type: 'error',
                message: global.t('settings.company_info.licence.request_error'),
              })  
        }
      }
      else{
        console.log('not subscribed')
      }

    }

    closeCompanyModal()
    isDeleting.value = false
  } catch {
    isDeleting.value = false
    const notificationStore = useNotificationStore()
      notificationStore.showNotification({
        type: 'error',
        message: global.t('settings.company_info.licence.request_error_external'),
      })  
  }
}

function resetNewCompanyForm() {
  formData.id = null
  formData.name = ''

  v$.value.$reset()
}

function closeCompanyModal() {
  modalStore.closeModal()

  setTimeout(() => {
    resetNewCompanyForm()
    v$.value.$reset()
  }, 300)
}

</script>
