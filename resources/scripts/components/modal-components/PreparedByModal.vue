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
    <div class="prepared-by-modal">
      <form action="" @submit.prevent="submitItemData">
        <div class="px-8 py-8 sm:p-6">
          <BaseInputGrid layout="one-column">
            <BaseInputGroup
              :label="$t('preparedby.name')"
              required
              :error="v$.name.$error && v$.name.$errors[0].$message"
            >
              <BaseInput
                v-model="preparedByStore.currentPreparedBy.name"
                type="text"
                :invalid="v$.name.$error"
                @input="v$.name.$touch()"
              />
            </BaseInputGroup>
            <BaseInputGroup
              :label="$t('preparedby.signature')"
            >
              <BaseFileUploader
                v-model="signatureLogo"
                accept="image/*"
                @change="onFileInputChange"
                @remove="onFileInputRemove"
              />
            </BaseInputGroup>
            <BaseInputGroup
              :label="$t('preparedby.designation')"
            >
              <BaseInput
                v-model="preparedByStore.currentPreparedBy.designation"
                type="text"
              />
            </BaseInputGroup>
            <BaseInputGroup
              :label="$t('preparedby.contact_number')"
            >
              <BaseInput
                v-model="preparedByStore.currentPreparedBy.contact_number"
                type="number"
              />
            </BaseInputGroup>
            <BaseInputGroup
              :label="$t('preparedby.email')"
              :error="v$.email.$error && v$.email.$errors[0].$message"
            >
              <BaseInput
                v-model="preparedByStore.currentPreparedBy.email"
                type="text"
                :invalid="v$.name.$error"
                @input="v$.name.$touch()"
              />
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
            {{ preparedByStore.isEdit ? $t('general.update') : $t('general.save') }}
          </BaseButton>
        </div>
      </form>
    </div>
  </BaseModal>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch, inject } from 'vue'
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
import { usePreparedByStore } from '@/scripts/stores/prepared-by'
import { useNotificationStore } from '@/scripts/stores/notification'
import { useEstimateStore } from '@/scripts/stores/estimate'
import { useInvoiceStore } from '@/scripts/stores/invoice'

const emit = defineEmits(['newItem'])

const modalStore = useModalStore()
const preparedByStore = usePreparedByStore()
const companyStore = useCompanyStore()
const estimateStore = useEstimateStore()
const notificationStore = useNotificationStore()
const utils = inject('utils')

const { t } = useI18n()
const isLoading = ref(false)
let avatarFileBlob = ref(null)
let signatureLogo = ref([])

const modalActive = computed(
  () => modalStore.active && modalStore.componentName === 'PreparedByModal'
)

const rules = {
  name: {
    required: helpers.withMessage(t('validation.required'), required),
    minLength: helpers.withMessage(
      t('validation.name_min_length', { count: 3 }),
      minLength(3)
    ),
  },
   email: {
    email: helpers.withMessage(t('validation.email_incorrect'), email),
  },

}

const v$ = useVuelidate(
  rules,
  computed(() => preparedByStore.currentPreparedBy),
  computed(() => companyForm)
)

onMounted(() => {
  v$.value.$reset()
})

const companyForm = reactive({
  signature: null
})

utils.mergeSettings(companyForm, {
  ...preparedByStore.currentPreparedBy,
})

if (companyForm.signature) {
  signatureLogo.value.push({
    image: companyForm.signature,
  })
}
async function submitItemData() {
  v$.value.$touch();

  if (v$.value.$invalid) {
    return true;
  }

  let data = {
    ...preparedByStore.currentPreparedBy,
  }

  isLoading.value = true;

  let PreparedByData = new FormData()
  PreparedByData.append('name', preparedByStore.currentPreparedBy.name)
  PreparedByData.append('designation', preparedByStore.currentPreparedBy.designation)
  PreparedByData.append('contact_number', preparedByStore.currentPreparedBy.contact_number)
  PreparedByData.append('email', preparedByStore.currentPreparedBy.email)
  if (avatarFileBlob.value) {
    PreparedByData.append('signature', avatarFileBlob.value)
  }
  const action = preparedByStore.isEdit ? preparedByStore.updatePreparedBy : preparedByStore.addItem;
  await action(PreparedByData,
    preparedByStore.currentPreparedBy ? preparedByStore.currentPreparedBy.id : '')
  .then((res) => {
    isLoading.value = false;
    if (res.data.data) {
       modalStore.refreshData ? modalStore.refreshData() : ''
    }
    closeItemModal();
  })
}
function onFileInputChange(fileName, file) {
  avatarFileBlob.value = file
}

function onFileInputRemove() {
  avatarFileBlob.value = null
}

function closeItemModal() {
  modalStore.closeModal()
  setTimeout(() => {
    preparedByStore.resetCurrentItem()
    modalStore.$reset()
    v$.value.$reset()
  }, 300)
}
</script>
