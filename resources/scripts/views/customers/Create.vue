<template>
  <BasePage>
    <form @submit.prevent="submitCustomerData">
      <BasePageHeader :title="pageTitle">
        <BaseBreadcrumb>
          <BaseBreadcrumbItem :title="$t('general.home')" to="dashboard" />

          <BaseBreadcrumbItem
            :title="$tc('customers.customer', 2)"
            to="/admin/customers"
          />

          <BaseBreadcrumb-item :title="pageTitle" to="#" active />
        </BaseBreadcrumb>

        <template #actions>
          <div class="flex items-center justify-end">
            <BaseButton type="submit" :loading="isSaving" :disabled="isSaving">
              <template #left="slotProps">
                <BaseIcon name="SaveIcon" :class="slotProps.class" />
              </template>
              {{
                isEdit
                  ? $t('customers.update_customer')
                  : $t('customers.save_customer')
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
              :label="$t('customers.display_name')"
              required
              :error="
                v$.currentCustomer.name.$error &&
                v$.currentCustomer.name.$errors[0].$message
              "
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model="customerStore.currentCustomer.name"
                :content-loading="isFetchingInitialData"
                type="text"
                name="name"
                class=""
                :invalid="v$.currentCustomer.name.$error"
                @input="v$.currentCustomer.name.$touch()"
              />
            </BaseInputGroup>

            <BaseInputGroup
              :label="$t('customers.primary_contact_name')"
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model.trim="customerStore.currentCustomer.contact_name"
                :content-loading="isFetchingInitialData"
                type="text"
              />
            </BaseInputGroup>

            <BaseInputGroup
              :error="
                v$.currentCustomer.email.$error &&
                v$.currentCustomer.email.$errors[0].$message
              "
              :content-loading="isFetchingInitialData"
              :label="$t('customers.email')"
            >
              <BaseInput
                v-model.trim="customerStore.currentCustomer.email"
                :content-loading="isFetchingInitialData"
                type="text"
                name="email"
                :invalid="v$.currentCustomer.email.$error"
                @input="v$.currentCustomer.email.$touch()"
              />
            </BaseInputGroup>

            <BaseInputGroup
              :label="$t('customers.phone')"
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model.trim="customerStore.currentCustomer.phone"
                :content-loading="isFetchingInitialData"
                type="text"
                name="phone"
              />
            </BaseInputGroup>
            <BaseInputGroup
              
              :label="$t('customers.website')"
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model="customerStore.currentCustomer.website"
                :content-loading="isFetchingInitialData"
                type="text"
              />
            </BaseInputGroup>
            <BaseInputGroup
              
              :label="$t('customers.website_another')"
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model="customerStore.currentCustomer.website_ar"
                :content-loading="isFetchingInitialData"
                type="text"
              />
            </BaseInputGroup>

            <BaseInputGroup
              :label="$t('customers.prefix')"
              :error="
                v$.currentCustomer.prefix.$error &&
                v$.currentCustomer.prefix.$errors[0].$message
              "
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model="customerStore.currentCustomer.prefix"
                :content-loading="isFetchingInitialData"
                type="text"
                name="name"
                class=""
                :invalid="v$.currentCustomer.prefix.$error"
                @input="v$.currentCustomer.prefix.$touch()"
              />
            </BaseInputGroup>
            <BaseInputGroup
              :label="$t('customers.prefix_another')" 
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model="customerStore.currentCustomer.prefix_ar"
                :content-loading="isFetchingInitialData"
                type="text"/>
            </BaseInputGroup>
            <BaseInputGroup
              :label="$t('customers.primary_currency')"
              :content-loading="isFetchingInitialData"
              :error="
                v$.currentCustomer.currency_id.$error &&
                v$.currentCustomer.currency_id.$errors[0].$message
              "
              required
            >
              <BaseMultiselect
                v-model="customerStore.currentCustomer.currency_id"
                value-prop="id"
                label="name"
                track-by="name"
                :content-loading="isFetchingInitialData"
                :options="globalStore.currencies"
                searchable
                :can-deselect="false"
                :placeholder="$t('customers.select_currency')"
                :invalid="v$.currentCustomer.currency_id.$error"
                class="w-full"
              >
              </BaseMultiselect>
            </BaseInputGroup>

            <BaseInputGroup
              :label="$t('categories.name')"
              :content-loading="isFetchingInitialData"
            >
              <BaseMultiselect
                v-model="customerStore.currentCustomer.category_id"
                value-prop="id"
                label="name"
                track-by="name"
                :content-loading="isFetchingInitialData"
                :options="categoryStore.categories"
                searchable
                :can-deselect="false"
                :placeholder="$t('categories.select')"
                class="w-full"
              >
              </BaseMultiselect>
            </BaseInputGroup>

          </BaseInputGrid>
        </div>

        <BaseDivider class="mb-5 md:mb-8" />

        <!-- Billing Address   -->
        <div class="grid grid-cols-5 gap-4 mb-8">
          <h6 class="col-span-5 text-lg font-semibold text-left lg:col-span-1">
            {{ $t('customers.billing_address') }}
          </h6>

          <BaseInputGrid
            v-if="customerStore.currentCustomer.billing"
            class="col-span-5 lg:col-span-4"
          >
            <BaseInputGroup
              :label="$t('customers.name')"
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model.trim="customerStore.currentCustomer.billing.name"
                :content-loading="isFetchingInitialData"
                type="text"
                class="w-full"
                name="address_name"
              />
            </BaseInputGroup>
            <BaseInputGroup
              :label="$t('customers.name_another')"
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model.trim="customerStore.currentCustomer.name_ar"
                :content-loading="isFetchingInitialData"
                type="text"
                class="w-full"
              />
            </BaseInputGroup>

            

            <BaseInputGroup
              :label="$t('customers.state')"
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model="customerStore.currentCustomer.billing.state"
                :content-loading="isFetchingInitialData"
                name="billing.state"
                type="text"
              />
            </BaseInputGroup>

            <BaseInputGroup
              :label="$t('customers.state_another')"
              :content-loading="isFetchingInitialData"
            >
              <BaseInput
                v-model="customerStore.currentCustomer.state_ar"
                :content-loading="isFetchingInitialData"
                name="billing.state"
                type="text"
              />
            </BaseInputGroup>

            <BaseInputGroup
              :content-loading="isFetchingInitialData"
              :label="$t('customers.city')"
            >
              <BaseInput
                v-model="customerStore.currentCustomer.billing.city"
                :content-loading="isFetchingInitialData"
                name="billing.city"
                type="text"
              />
            </BaseInputGroup>
            <BaseInputGroup
              :content-loading="isFetchingInitialData"
              :label="$t('customers.city_another')"
            >
              <BaseInput
                v-model="customerStore.currentCustomer.city_ar"
                :content-loading="isFetchingInitialData"
                type="text"
              />
            </BaseInputGroup>

            <BaseInputGroup
              :label="$t('customers.address')"
              :error="
                (v$.currentCustomer.billing.address_street_1.$error &&
                  v$.currentCustomer.billing.address_street_1.$errors[0]
                    .$message) ||
                (v$.currentCustomer.billing.address_street_2.$error &&
                  v$.currentCustomer.billing.address_street_2.$errors[0]
                    .$message)
              "
              :content-loading="isFetchingInitialData"
            >
              <BaseTextarea
                v-model.trim="
                  customerStore.currentCustomer.billing.address_street_1
                "
                :content-loading="isFetchingInitialData"
                :placeholder="$t('general.street_1')"
                type="text"
                name="billing_street1"
                :container-class="`mt-3`"
                @input="v$.currentCustomer.billing.address_street_1.$touch()"
              />

              <BaseTextarea
                v-model.trim="customerStore.currentCustomer.billing.address_street_2"
                :content-loading="isFetchingInitialData"
                :placeholder="$t('general.street_2')"
                type="text"
                class="mt-3"
                name="billing_street2"
                :container-class="`mt-3`"
                @input="v$.currentCustomer.billing.address_street_2.$touch()"
              />
            </BaseInputGroup>
            <BaseInputGroup
              :label="$t('customers.address_another')"
              :content-loading="isFetchingInitialData"
            >
              <BaseTextarea
                v-model.trim="
                  customerStore.currentCustomer.address_street_1_ar
                "
                :content-loading="isFetchingInitialData"
                :placeholder="$t('general.street_1_another')"
                type="text"
                :container-class="`mt-3`"
              />

              <BaseTextarea
                v-model.trim="customerStore.currentCustomer.address_street_2_ar"
                :content-loading="isFetchingInitialData"
                :placeholder="$t('general.street_2_another')"
                type="text"
                class="mt-3"
                :container-class="`mt-3`"
              />
            </BaseInputGroup>

            <div class="space-y-6">
              <BaseInputGroup
                :content-loading="isFetchingInitialData"
                :label="$t('customers.phone')"
                class="text-left"
              >
                <BaseInput
                  v-model.trim="customerStore.currentCustomer.billing.phone"
                  :content-loading="isFetchingInitialData"
                  type="text"
                  name="phone"
                />
              </BaseInputGroup>
               <BaseInputGroup
                :content-loading="isFetchingInitialData"
                :label="$t('customers.phone_another')"
                class="text-left"
              >
                <BaseInput
                  v-model.trim="customerStore.currentCustomer.phone_ar"
                  :content-loading="isFetchingInitialData"
                  type="text"
                />
              </BaseInputGroup>

              <BaseInputGroup
                :label="$t('customers.zip_code')"
                :content-loading="isFetchingInitialData"
                class="mt-2 text-left"
              >
                <BaseInput
                  v-model.trim="customerStore.currentCustomer.billing.zip"
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
                  v-model.trim="customerStore.currentCustomer.zip_ar"
                  :content-loading="isFetchingInitialData"
                  type="text"
                />
              </BaseInputGroup>
              <BaseInputGroup
              :label="$t('customers.country')"
              :content-loading="isFetchingInitialData"
            >
              <BaseMultiselect
                v-model="customerStore.currentCustomer.billing.country_id"
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
            </div>
          </BaseInputGrid>
        </div>

        <BaseDivider class="mb-5 md:mb-8" />

        <!-- Billing Address Copy Button  -->
        <!-- <div
           class="flex items-center justify-start mb-6 md:justify-end md:mb-0"
         >
           <div class="p-1">
             <BaseButton
               type="button"
               :content-loading="isFetchingInitialData"
               size="sm"
               variant="primary-outline"
               @click="customerStore.copyAddress(true)"
             >
               <template #left="slotProps">
                 <BaseIcon
                   name="DocumentDuplicateIcon"
                   :class="slotProps.class"
                 />
               </template>
               {{ $t('customers.copy_billing_address') }}
             </BaseButton>
           </div>
         </div>-->

        <!-- Customer Custom Fields -->
        <div class="grid grid-cols-5 gap-2 mb-8">
          <h6
            v-if="customFieldStore.customFields.length > 0"
            class="col-span-5 text-lg font-semibold text-left lg:col-span-1"
          >
            {{ $t('settings.custom_fields.title') }}
          </h6>

          <div class="col-span-5 lg:col-span-4">
            <CustomerCustomFields
              type="Customer"
              :store="customerStore"
              store-prop="currentCustomer"
              :is-edit="isEdit"
              :is-loading="isLoadingContent"
              :custom-field-scope="customFieldValidationScope"
            />
          </div>
        </div>
      </BaseCard>
    </form>
  </BasePage>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
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
} from '@vuelidate/validators'
import useVuelidate from '@vuelidate/core'
import { useCustomerStore } from '@/scripts/stores/customer'
import { useCustomFieldStore } from '@/scripts/stores/custom-field'
import CustomerCustomFields from '@/scripts/components/custom-fields/CreateCustomFields.vue'
import { useGlobalStore } from '@/scripts/stores/global'
import { useCategoryStore } from '@/scripts/stores/item_client_category'

const customerStore = useCustomerStore()
const customFieldStore = useCustomFieldStore()
const globalStore = useGlobalStore()
const categoryStore = useCategoryStore()

const customFieldValidationScope = 'customFields'

const { t } = useI18n()

const router = useRouter()
const route = useRoute()

let isFetchingInitialData = ref(false)
const isSaving = ref(false)

const isEdit = computed(() => route.name === 'customers.edit')

let isLoadingContent = computed(() => customerStore.isFetchingInitialSettings)

const pageTitle = computed(() =>
  isEdit.value ? t('customers.edit_customer') : t('customers.new_customer')
)

const rules = computed(() => {
  return {
    currentCustomer: {
      name: {
        required: helpers.withMessage(t('validation.required'), required),
        minLength: helpers.withMessage(
          t('validation.name_min_length', { count: 3 }),
          minLength(3)
        ),
      },
      prefix: {
        minLength: helpers.withMessage(
          t('validation.name_min_length', { count: 3 }),
          minLength(3)
        ),
      },
      currency_id: {
        required: helpers.withMessage(t('validation.required'), required),
      },
      email: {
        email: helpers.withMessage(t('validation.email_incorrect'), email),
      },
      billing: {
        address_street_1: {
          maxLength: helpers.withMessage(
            t('validation.address_maxlength', { count: 255 }),
            maxLength(255)
          ),
        },

        address_street_2: {
          maxLength: helpers.withMessage(
            t('validation.address_maxlength', { count: 255 }),
            maxLength(255)
          ),
        },
      },
    },
  }
})

const v$ = useVuelidate(rules, customerStore, {
  $scope: customFieldValidationScope,
})

customerStore.resetCurrentCustomer()

customerStore.fetchCustomerInitialSettings(isEdit.value)

async function submitCustomerData() {
  v$.value.$touch()

  if (v$.value.$invalid) {
    return true
  }

  isSaving.value = true

  let data = {
    ...customerStore.currentCustomer,
  }

  let response = null

  try {
    const action = isEdit.value
      ? customerStore.updateCustomer
      : customerStore.addCustomer
    response = await action(data)
  } catch (err) {
    isSaving.value = false
    return
  }

  router.push(`/admin/customers/${response.data.data.id}/view`)
}
</script>
