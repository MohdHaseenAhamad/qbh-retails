<template>
  <BasePage>
    <form @submit.prevent="submitStoreData">
      <BasePageHeader :title="pageTitle">
        <BaseBreadcrumb>
          <BaseBreadcrumbItem :title="$t('general.home')" to="/admin/dashboard" />

          <BaseBreadcrumbItem
            :title="$tc('stores.store', 2)"
            to="/admin/stores"
          />

          <BaseBreadcrumb-item :title="pageTitle" to="#" active />
        </BaseBreadcrumb>

        <template #actions>
          <div class="flex items-center justify-end">
            <BaseButton type="submit" :loading="isSaving" :disabled="supplierStore.codeAvailability && (!supplierStore.codeAvailability.status || isSaving)">
              <template #left="slotProps">
                <BaseIcon name="SaveIcon" :class="slotProps.class" />
              </template>
              {{
                // console.log(isEdit),
                isEdit
                  ? $t('stores.update')
                  : $t('stores.save')
              }}
            </BaseButton>
          </div>
        </template>
      </BasePageHeader>

      <BaseCard class="mt-5">
        <!-- Basic Info -->
        <div class="grid grid-cols-5 gap-4 mb-8">
          <h6 class="col-span-5 text-lg font-semibold text-left lg:col-span-1">
            {{ $t('customers.basic_info') }}
          </h6>

          <BaseInputGrid class="col-span-5 lg:col-span-4">
            <BaseInputGroup
              :label="$t('stores.display_name')"
              required
              :error="
                v$.currentSupplier.name.$error &&
                v$.currentSupplier.name.$errors[0].$message
              "
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model="supplierStore.currentSupplier.name"
                :content-loading="isFetchingInitialData"
                type="text"
                name="name"
                class=""
                :invalid="v$.currentSupplier.name.$error"
                @input="v$.currentSupplier.name.$touch()"
              />
            </BaseInputGroup>

            <BaseInputGroup
              :label="$t('stores.code')"
              required
              :error="
                v$.currentSupplier.code.$error &&
                v$.currentSupplier.code.$errors[0].$message
              "
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model="supplierStore.currentSupplier.code"
                :content-loading="isFetchingInitialData"
                type="text"
                name="code"
                placeholder="Eg: NBC5"
                class=""
                :invalid="v$.currentSupplier.code.$error"
                @input="v$.currentSupplier.code.$touch()"
                autocomplete="off"
              />
              <span v-show="!supplierStore.codeAvailability.status" class="block sm:inline text-red-700">
                <b>Code Already Exist!</b> <br>use another store code.
              </span>
            </BaseInputGroup>

            <BaseInputGroup
              :label="$t('stores.contact_name')"
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model.trim="supplierStore.currentSupplier.contact_name"
                :content-loading="isFetchingInitialData"
                type="text"
              />
            </BaseInputGroup>

            <BaseInputGroup
              :error="
                v$.currentSupplier.email.$error &&
                v$.currentSupplier.email.$errors[0].$message
              "
              :content-loading="isFetchingInitialData"
              :label="$t('customers.email')"
            >
              <BaseInput
                v-model.trim="supplierStore.currentSupplier.email"
                :content-loading="isFetchingInitialData"
                type="text"
                name="email"
                :invalid="v$.currentSupplier.email.$error"
                @input="v$.currentSupplier.email.$touch()"
              />
            </BaseInputGroup>

            <BaseInputGroup
              :label="$t('customers.phone')"
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model.trim="supplierStore.currentSupplier.phone"
                :content-loading="isFetchingInitialData"
                type="text"
                name="phone"
              />
            </BaseInputGroup>
            
            <BaseInputGroup
              :label="$t('customers.description')"
              :content-loading="isFetchingInitialData"
            >
              <BaseTextarea
                v-model.trim="
                  supplierStore.currentSupplier.description
                "
                :content-loading="isFetchingInitialData"
                type="text"
                name="description"
                :container-class="`mt-3`"
              />

            </BaseInputGroup>

          </BaseInputGrid>
        </div>

        <BaseDivider class="mb-5 md:mb-8" />

        <!-- Address Info -->
        <div class="grid grid-cols-5 gap-4 mb-8">
          <h6 class="col-span-5 text-lg font-semibold text-left lg:col-span-1">
            {{ $t('stores.address') }}
          </h6>

          <BaseInputGrid
            v-if="true"
            class="col-span-5 lg:col-span-4"
          >
            <BaseInputGroup
              :label="$t('customers.state')"
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model="supplierStore.currentSupplier.state"
                :content-loading="isFetchingInitialData"
                name="state"
                type="text"
              />
            </BaseInputGroup>

            <BaseInputGroup
              :label="$t('customers.state_another')"
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model="supplierStore.currentSupplier.state_ar"
                :content-loading="isFetchingInitialData"
                name="state_ar"
                type="text"
              />
            </BaseInputGroup>

            <BaseInputGroup
              :content-loading="isFetchingInitialData"
              :label="$t('customers.city')"
            >
              <BaseInput
                v-model="supplierStore.currentSupplier.city"
                :content-loading="isFetchingInitialData"
                name="city"
                type="text"
              />
            </BaseInputGroup>
            <BaseInputGroup
              :content-loading="isFetchingInitialData"
              :label="$t('customers.city_another')"
            >
              <BaseInput
                v-model="supplierStore.currentSupplier.city_ar"
                :content-loading="isFetchingInitialData"
                type="text"
              />
            </BaseInputGroup>

            <BaseInputGroup
              :label="$t('customers.address')"
              :content-loading="isFetchingInitialData"
            >
              <BaseTextarea
                v-model.trim="
                  supplierStore.currentSupplier.address_street_1
                "
                :content-loading="isFetchingInitialData"
                :placeholder="$t('general.street_1')"
                type="text"
                name="address_street_1"
                :container-class="`mt-3`"
              />

              <BaseTextarea
                v-model.trim="supplierStore.currentSupplier.address_street_2"
                :content-loading="isFetchingInitialData"
                :placeholder="$t('general.street_2')"
                type="text"
                class="mt-3"
                name="address_street_2"
                :container-class="`mt-3`"
              />
            </BaseInputGroup>
            <BaseInputGroup
              :label="$t('customers.address_another')"
              :content-loading="isFetchingInitialData"
            >
              <BaseTextarea
                v-model.trim="
                  supplierStore.currentSupplier.address_street_1_ar
                "
                :content-loading="isFetchingInitialData"
                :placeholder="$t('general.street_1_another')"
                type="text"
                :container-class="`mt-3`"
              />

              <BaseTextarea
                v-model.trim="supplierStore.currentSupplier.address_street_2_ar"
                :content-loading="isFetchingInitialData"
                :placeholder="$t('general.street_2_another')"
                type="text"
                class="mt-3"
                :container-class="`mt-3`"
              />
            </BaseInputGroup>
              
              <BaseInputGroup
                :label="$t('customers.zip_code')"
                :content-loading="isFetchingInitialData"
                class="mt-2 text-left"
              >
                <BaseInput
                  v-model.trim="supplierStore.currentSupplier.zip"
                  :content-loading="isFetchingInitialData"
                  type="text"
                  name="zip"
                />
              </BaseInputGroup>
              <BaseInputGroup
                :label="$t('customers.zip_code_another')"
                :content-loading="isFetchingInitialData"
                class="mt-2 text-left"
              >
                <BaseInput
                  v-model.trim="supplierStore.currentSupplier.zip_ar"
                  :content-loading="isFetchingInitialData"
                  type="text"
                />
              </BaseInputGroup>
              <BaseInputGroup
              :label="$t('customers.country')"
              :content-loading="isFetchingInitialData"
            >
              <BaseMultiselect
                v-model="supplierStore.currentSupplier.country_id"
                value-prop="id"
                label="name"
                track-by="name"
                resolve-on-load
                searchable
                :content-loading="isFetchingInitialData"
                :options="globalStore.countries"
                :placeholder="$t('general.select_country')"
                class="w-full"
              />
            </BaseInputGroup>
          </BaseInputGrid>
        </div>

      </BaseCard>

      <BaseDivider class="mb-5 md:mb-8" />
      <div class="flex items-center justify-end">
        <BaseButton type="submit" :loading="isSaving" :disabled="isSaving">
          <template #left="slotProps">
            <BaseIcon name="SaveIcon" :class="slotProps.class" />
          </template>
          {{
            // console.log(isEdit),
            isEdit
              ? $t('stores.update')
              : $t('stores.save')
          }}
        </BaseButton>
      </div>

    </form>
  </BasePage>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import {
  required,
  minLength,
  url,
  maxLength,
  helpers,
  email,
  sameAs,
  alphaNum
} from '@vuelidate/validators'
import useVuelidate from '@vuelidate/core'
import { useStore } from '@/scripts/stores/store'
import { useCustomFieldStore } from '@/scripts/stores/custom-field'
import CustomerCustomFields from '@/scripts/components/custom-fields/CreateCustomFields.vue'
import { useGlobalStore } from '@/scripts/stores/global'

const supplierStore = useStore()
const customFieldStore = useCustomFieldStore()
const globalStore = useGlobalStore()

const customFieldValidationScope = 'customFields'

const { t } = useI18n()

const router = useRouter()
const route = useRoute()

let isFetchingInitialData = ref(false)
const isSaving = ref(false)

const isEdit = computed(() => route.name === 'stores.edit')

let isLoadingContent = computed(() => supplierStore.isFetchingInitialSettings)

const pageTitle = computed(() => 
  isEdit.value ? t('stores.edit_store') : t('stores.add_new_store')
)

const rules = computed(() => {
  return {
    currentSupplier: {
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
      email: {
        email: helpers.withMessage(t('validation.email_incorrect'), email),
      },
    },
  }
})

const v$ = useVuelidate(rules, supplierStore, {
  $scope: customFieldValidationScope,
})

supplierStore.resetCurrentSupplier()

supplierStore.fetchSupplierInitialSettings(isEdit.value)

async function submitStoreData() {
  v$.value.$touch()
  if (v$.value.$invalid) {
    return true
  }

  isSaving.value = true

  let data = {
    ...supplierStore.currentSupplier,
  }

  let response = null
  try {
    const action = isEdit.value
      ? supplierStore.updateStore
      : supplierStore.addStore

    response = await action(data)
  } catch (err) {
    isSaving.value = false
    return
  }
  router.push(`/admin/stores/${response.data.data.id}/view`)
}

watch(
  () => supplierStore.currentSupplier.code,
  (val) => {
    checkCategoryCode()
  }
)

async function checkCategoryCode(){

  let data = {
    id: supplierStore.currentSupplierId,
    code: supplierStore.currentSupplier.code
  }

  await supplierStore
    .checkStoreCodeAvailability(data)
    .then((res) => {
      // isSaving.value = false
      if (res.data) {
        console.log(res.data.status)
        console.log(res.data)
      }
    })
    .catch((err) => {
      // isSaving.value = false
      console.log(err)
    })
}

</script>
