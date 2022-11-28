<template>
  <div class="pt-6 mt-5 border-t border-solid lg:pt-8 md:pt-4 border-gray-200">
    <!-- Basic Info -->
    <BaseHeading>
      {{ $t('customers.basic_info') }}
    </BaseHeading>

    <BaseDescriptionList>
      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('customers.display_name')"
        :value="selectedViewSupplier?.name"
      />

      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('customers.primary_contact_name')"
        :value="selectedViewSupplier?.contact_name"
      />
      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('customers.email')"
        :value="selectedViewSupplier?.email"
      />
    </BaseDescriptionList>

    <BaseDescriptionList class="mt-5">
      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('wizard.currency')"
        :value="
          selectedViewSupplier?.currency
            ? `${selectedViewSupplier?.currency?.code} (${selectedViewSupplier?.currency?.symbol})`
            : ''
        "
      />

      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('customers.phone_number')"
        :value="selectedViewSupplier?.phone"
      />
      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('customers.website')"
        :value="selectedViewSupplier?.website"
      />

      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('customers.prefix')"
        :value="selectedViewSupplier?.prefix"
      />

    </BaseDescriptionList>

    <!-- Address -->
    <BaseHeading
      v-if="selectedViewSupplier.billing || selectedViewSupplier.shipping"
      class="mt-8"
    >
      {{ $t('customers.address') }}
    </BaseHeading>

    <BaseDescriptionList class="mt-5">
      <BaseDescriptionListItem
        v-if="selectedViewSupplier.billing"
        :content-loading="contentLoading"
        :label="$t('customers.billing_address')"
      >
        <BaseCustomerAddressDisplay :address="selectedViewSupplier.billing" />
      </BaseDescriptionListItem>

      <BaseDescriptionListItem
        v-if="selectedViewSupplier.shipping"
        :content-loading="contentLoading"
        :label="$t('customers.shipping_address')"
      >
        <BaseCustomerAddressDisplay :address="selectedViewSupplier.shipping" />
      </BaseDescriptionListItem>
    </BaseDescriptionList>

    <!-- Custom Fields -->
    <BaseHeading v-if="customerCustomFields.length > 0" class="mt-8">
      {{ $t('settings.custom_fields.title') }}
    </BaseHeading>

    <BaseDescriptionList class="mt-5">
      <BaseDescriptionListItem
        v-for="(field, index) in customerCustomFields"
        :key="index"
        :content-loading="contentLoading"
        :label="field.custom_field.label"
      >
        <p
          v-if="field.type === 'Switch'"
          class="text-sm font-bold leading-5 text-black non-italic"
        >
          <span v-if="field.default_answer === 1"> Yes </span>
          <span v-else> No </span>
        </p>
        <p v-else class="text-sm font-bold leading-5 text-black non-italic">
          {{ field.default_answer }}
        </p>
      </BaseDescriptionListItem>
    </BaseDescriptionList>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useSupplierStore } from '@/scripts/stores/supplier'

const supplierStore = useSupplierStore()

const selectedViewSupplier = computed(() => supplierStore.selectedViewSupplier)

const contentLoading = computed(() => supplierStore.isFetchingViewData)

const customerCustomFields = computed(() => {
  if (selectedViewSupplier?.value?.fields) {
    return selectedViewSupplier?.value?.fields
  }
  return []
})
</script>
