<template>
  <BaseModal :show="modalActive" @close="closeItemModal">
    <template #header>
      <div class="flex justify-between w-full">
        {{ modalStore.title }}
        <BaseIcon
          name="XIcon"
          class="h-6 w-6 text-gray-500 cursor-pointer"
          @click="closeItemModal"
        />
      </div>
    </template>
    <div class="bank-detail-modal">
      <form action="" @submit.prevent="submitItemData">
        <div class="px-8 py-8 sm:p-6">
          <BaseInputGrid class="mt-5">
            <BaseInputGroup
              :label="$t('bankdetail.account_name')"
              :error="v$.account_name.$error && v$.account_name.$errors[0].$message"
              required
            >
              <BaseInput
                v-model="bankDetailStore.currentBankDetail.account_name"
                :invalid="v$.account_name.$error"
                @blur="v$.account_name.$touch()"
              />
            </BaseInputGroup>
            <BaseInputGroup :label="$t('bankdetail.account_name_ar')">
              <BaseInput v-model="bankDetailStore.currentBankDetail.account_name_ar"/>
            </BaseInputGroup>
            <BaseInputGroup
              :label="$t('bankdetail.bank_name')"
              :error="v$.bank_name.$error && v$.bank_name.$errors[0].$message"
              required
            >
              <BaseInput
                v-model="bankDetailStore.currentBankDetail.bank_name"
                :invalid="v$.bank_name.$error"
                @blur="v$.bank_name.$touch()"
              />
            </BaseInputGroup>
            <BaseInputGroup :label="$t('bankdetail.bank_name_ar')">
              <BaseInput v-model="bankDetailStore.currentBankDetail.bank_name_ar"/>
            </BaseInputGroup>
            <BaseInputGroup
              :label="$t('bankdetail.account_no')"
              :error="v$.account_no.$error && v$.account_no.$errors[0].$message"
              required
            >
              <BaseInput
                v-model="bankDetailStore.currentBankDetail.account_no"
                :invalid="v$.account_no.$error"
                @blur="v$.account_no.$touch()"
              />
            </BaseInputGroup>
            <BaseInputGroup :label="$t('bankdetail.account_no_ar')">
              <BaseInput  v-model="bankDetailStore.currentBankDetail.account_no_ar"/>
            </BaseInputGroup>
            <BaseInputGroup
              :label="$t('bankdetail.iban')"
              :error="v$.iban.$error && v$.iban.$errors[0].$message"
              required
            >
              <BaseInput
                v-model="bankDetailStore.currentBankDetail.iban"
                :invalid="v$.iban.$error"
                @blur="v$.iban.$touch()"
              />
            </BaseInputGroup>
            <BaseInputGroup :label="$t('bankdetail.iban_ar')">
              <BaseInput v-model="bankDetailStore.currentBankDetail.iban_ar"/>
            </BaseInputGroup>
            <BaseInputGroup
              :label="$t('bankdetail.swift_code')"
              :error="v$.swift_code.$error && v$.swift_code.$errors[0].$message"
              required
            >
              <BaseInput
                v-model="bankDetailStore.currentBankDetail.swift_code"
                :invalid="v$.swift_code.$error"
                @blur="v$.swift_code.$touch()"
              />
            </BaseInputGroup>
            <BaseInputGroup :label="$t('bankdetail.swift_code_ar')">
              <BaseInput v-model="bankDetailStore.currentBankDetail.swift_code_ar"/>
            </BaseInputGroup>
          </BaseInputGrid>
        </div>
        <div
          class="z-0 flex justify-end p-4 border-t border-gray-200 border-solid"
        >
          <BaseButton
            class="mr-3"
            variant="primary-outline"
            type="button"
            @click="closeItemModal"
          >
            {{ $t('general.cancel') }}
          </BaseButton>
          <BaseButton
            :loading="isLoading"
            :disabled="isLoading"
            variant="primary"
            type="submit"
          >
            <template #left="slotProps">
              <BaseIcon name="SaveIcon" :class="slotProps.class" />
            </template>
            {{ bankDetailStore.isEdit ? $t('general.update') : $t('general.save') }}
          </BaseButton>
        </div>
      </form>
    </div>
  </BaseModal>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRoute } from 'vue-router'
import {
  required,
  minLength,
  maxLength,
  minValue,
  helpers,
  alpha,
  email
} from '@vuelidate/validators'
import useVuelidate from '@vuelidate/core'
import { useModalStore } from '@/scripts/stores/modal'
import { useCompanyStore } from '@/scripts/stores/company'
import { useBankDetailStore } from '@/scripts/stores/bank-detail'
import { useNotificationStore } from '@/scripts/stores/notification'
import { useEstimateStore } from '@/scripts/stores/estimate'
import { useInvoiceStore } from '@/scripts/stores/invoice'

const emit = defineEmits(['newItem'])

const modalStore = useModalStore()
const bankDetailStore = useBankDetailStore()
const companyStore = useCompanyStore()
const estimateStore = useEstimateStore()
const notificationStore = useNotificationStore()

const { t } = useI18n()
const isLoading = ref(false)

const modalActive = computed(
  () => modalStore.active && modalStore.componentName === 'BankDetailModal'
)


const rules = {
  account_no: {
    required: helpers.withMessage(t('validation.required'), required)
  },
   bank_name: {
    required: helpers.withMessage(t('validation.required'), required),
  },
   account_name: {
    required: helpers.withMessage(t('validation.required'), required),
  },
   iban: {
    required: helpers.withMessage(t('validation.required'), required),
  },
   swift_code: {
    required: helpers.withMessage(t('validation.required'), required),
  }

}

const v$ = useVuelidate(
  rules,
  computed(() => bankDetailStore.currentBankDetail)
)

onMounted(() => {
  v$.value.$reset()
})

async function submitItemData() {
  v$.value.$touch()

  if (v$.value.$invalid) {
    return true
  }

  let data = {
    ...bankDetailStore.currentBankDetail,
  }

  isLoading.value = true

  const action = bankDetailStore.isEdit ? bankDetailStore.updateBankDetail : bankDetailStore.addItem

  await action(bankDetailStore.currentBankDetail).then((res) => {
    console.log(res.data.data,'resresresresres')
    isLoading.value = false
    if (res.data.data) {
      modalStore.refreshData ? modalStore.refreshData(res.data.data) : ''
    }
    closeItemModal()
  })
}

function closeItemModal() {
  modalStore.closeModal()
  setTimeout(() => {
    bankDetailStore.resetCurrentItem()
    modalStore.$reset()
    v$.value.$reset()
  }, 300)
}
</script>
