<template>
  <BaseModal :show="modalActive" @close="closeCategoryModal" @open="setFields">
    <template #header>
      <div class="flex justify-between w-full">
        {{ modalStore.title }}
        <BaseIcon
          name="XIcon"
          class="h-6 w-6 text-gray-500 cursor-pointer"
          @click="closeCategoryModal"
        />
      </div>
    </template>
    <form action="" @submit.prevent="submitNote">
      <div class="px-8 py-8 sm:p-6">
        <BaseInputGrid layout="one-column">

          <input type="hidden" v-model="categoryStore.currentCategory.type">

          <BaseInputGroup
            :label="$t('settings.customization.notes.name')"
            variant="vertical"
            :error="
              v$.currentCategory.name.$error &&
              v$.currentCategory.name.$errors[0].$message
            "
            required
          >
            <BaseInput
              v-model="categoryStore.currentCategory.name"
              :invalid="v$.currentCategory.name.$error"
              type="text"
              @input="v$.currentCategory.name.$touch()"
            />
          </BaseInputGroup>

          <BaseInputGroup
            :label="$t('settings.categories.code')"
            variant="vertical"
            :error="
              v$.currentCategory.code.$error &&
              v$.currentCategory.code.$errors[0].$message
            "
            required
          >
            <BaseInput
              v-model="categoryStore.currentCategory.code"
              :invalid="v$.currentCategory.code.$error"
              type="text"
              @input="v$.currentCategory.code.$touch()"
            />
          </BaseInputGroup>

          <div v-show="!categoryStore.codeAvailability.status" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Category Code Already Exist!</strong>
            <br><span class="block sm:inline">Please use different unique category code.</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
              <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"></svg>
            </span>
          </div>

        </BaseInputGrid>
      </div>
      <div
        class="
          z-0
          flex
          justify-end
          px-4
          py-4
          border-t border-solid border-gray-light
        "
      >
        <BaseButton
          class="mr-2"
          variant="primary-outline"
          type="button"
          @click="closeCategoryModal"
        >
          {{ $t('general.cancel') }}
        </BaseButton>

        <BaseButton
          :disabled="!categoryStore.codeAvailability.status || isSaving"
          :loading="isSaving"
          variant="primary"
          type="submit"
        >
          <template #left="slotProps">
            <BaseIcon name="SaveIcon" :class="slotProps.class" />
          </template>
          {{ categoryStore.isEdit ? $t('general.update') : $t('general.save') }}
        </BaseButton>

      </div>
    </form>
  </BaseModal>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRoute } from 'vue-router'
import { required, minLength, maxLength, alphaNum, helpers } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'
import { useModalStore } from '@/scripts/stores/modal'
import { useNotificationStore } from '@/scripts/stores/notification'
import { useNotesStore } from '@/scripts/stores/note'
import { useCategoryStore } from '@/scripts/stores/item_client_category'

const modalStore = useModalStore()
const notificationStore = useNotificationStore()
const noteStore = useNotesStore()
const categoryStore = useCategoryStore()

const route = useRoute()
const { t } = useI18n()

let isSaving = ref(false)
const types = reactive(['Invoice', 'Estimate', 'Payment'])
let fields = ref(['customer', 'customerCustom'])

let isCodeAvailable = false

const modalActive = computed(() => {
  return modalStore.active && modalStore.componentName === 'ItemCategoryModal'
})

const rules = computed(() => {
  return {
    currentCategory: {
      name: {
        required: helpers.withMessage(t('validation.required'), required),
        minLength: helpers.withMessage(
          t('validation.name_min_length', { count: 3 }),
          minLength(3)
        ),
      },
      code: {
        required: helpers.withMessage(t('validation.required'), required),
        maxLength: helpers.withMessage(t('categories.maxlength'), maxLength(5)),
        alphaNum: helpers.withMessage(t('categories.no_space_allowed'), alphaNum)
      },
    },
  }
})

const v$ = useVuelidate(
  rules,
  computed(() => categoryStore)
)

watch(
  () => categoryStore.currentCategory.code,
  (val) => {
    checkCategoryCode()
  }
)

async function checkCategoryCode(){
  let type = categoryStore.currentCategory.type ? categoryStore.currentCategory.type : modalStore.option1

  let data = {
    id: categoryStore.currentCategoryId,
    type: type,
    code: categoryStore.currentCategory.code,
  }

  await categoryStore
    .checkCategoryCodeAvailability(data)
    .then((res) => {
      // isSaving.value = false
      if (res.data) {
        console.log(categoryStore.currentCategory.type)
        console.log(res.data)
        // console.log(categoryStore.currentCategory)
        // console.log(categoryStore.codeAvailability)
      }
    })
    .catch((err) => {
      // isSaving.value = false
      console.log(err)
    })
}

onMounted(() => {
  // console.log('mounted')
})

async function submitNote() {
  v$.value.currentCategory.$touch()
  if (v$.value.currentCategory.$invalid) {
    // console.log(v$.value.currentCategory)
    return true
  }

  isSaving.value = true

  if (categoryStore.isEdit) {
    let data = {
      id: categoryStore.currentCategory.id,
      ...categoryStore.currentCategory,
    }
    await categoryStore
      .updateCategory(data)
      .then((res) => {
        isSaving.value = false
        if (res.data) {
          notificationStore.showNotification({
            type: 'success',
            message: t('settings.categories.updated'),
          })
          modalStore.refreshData ? modalStore.refreshData() : ''
          closeCategoryModal()
        }
      })
      .catch((err) => {
        isSaving.value = false
      })
  } 
  else 
  {
    // console.log(categoryStore.currentCategory)
    categoryStore.currentCategory.type = modalStore.option1;
    
    await categoryStore
      .addCategory(categoryStore.currentCategory)
      .then((res) => {
        isSaving.value = false
        if (res.data) {
          notificationStore.showNotification({
            type: 'success',
            message: t('settings.categories.added'),
          })
        }

        modalStore.refreshData ? modalStore.refreshData() : ''
        closeCategoryModal()
      })
      .catch((err) => {
        isSaving.value = false
      })
  }
}

function closeCategoryModal() {
  modalStore.closeModal()

  setTimeout(() => {
    categoryStore.resetCurrentCategory()
    v$.value.$reset()
  }, 300)
}
</script>

<style lang="scss">
.note-modal {
  .header-editior .editor-menu-bar {
    margin-left: 0.5px;
    margin-right: 0px;
  }
}
</style>
