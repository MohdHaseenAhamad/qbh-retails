<template>
  <div class="pt-6 mt-2 border-solid lg:pt-8 md:pt-4 border-gray-200">
    <!-- Basic Info -->
    <BaseHeading>
      {{ $t('customers.basic_info') }}
    </BaseHeading>

    <BaseDescriptionList>
      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('stores.display_name')"
        :value="selectedViewSupplier?.name ?? 'N/A'"
      />

      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('stores.code')"
        :value="selectedViewSupplier?.code ?? 'N/A'"
      />

      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('stores.contact_name')"
        :value="selectedViewSupplier?.contact_name ?? 'N/A'"
      />
      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('customers.email')"
        :value="selectedViewSupplier?.email ?? 'N/A'"
      />

      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('customers.phone_number')"
        :value="selectedViewSupplier?.phone ?? 'N/A'"
      />

      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('customers.description')"
        :value="selectedViewSupplier?.description ?? 'N/A'"
      />

    </BaseDescriptionList>

    <!-- Basic Info -->
    <BaseHeading class="mt-8">
      {{ $t('stores.address') }}
    </BaseHeading>

    <BaseDescriptionList>
      
      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('stores.address_street_1')"
        :value="selectedViewSupplier?.address_street_1 ?? 'N/A'"
      />

      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('stores.address_street_2')"
        :value="selectedViewSupplier?.address_street_2 ?? 'N/A'"
      />

      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('stores.state')"
        :value="selectedViewSupplier?.state ?? 'N/A'"
      />

      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('stores.city')"
        :value="selectedViewSupplier?.city ?? 'N/A'"
      />
      <BaseDescriptionListItem
        :content-loading="contentLoading"
        :label="$t('stores.zip')"
        :value="selectedViewSupplier?.zip ?? 'N/A'"
      />
    </BaseDescriptionList>

    <!-- Custom Fields -->
    <BaseHeading v-if="customerCustomFields.length > 0" class="mt-8">
      {{ $t('settings.custom_fields.title') }}
    </BaseHeading>

  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useStore } from '@/scripts/stores/store'

const supplierStore = useStore()

const selectedViewSupplier = computed(() => supplierStore.selectedViewSupplier)

const contentLoading = computed(() => supplierStore.isFetchingViewData)

const customerCustomFields = computed(() => {
  if (selectedViewSupplier?.value?.fields) {
    return selectedViewSupplier?.value?.fields
  }
  return []
})
</script>
