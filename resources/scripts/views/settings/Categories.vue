<template>
  <div class="relative">
    <BaseCard container-class="px-4 py-5 sm:px-8 sm:py-2">
      <BaseTabGroup>
        <BaseTab
          tab-panel-container="py-4 mt-px"
          :title="$t('settings.categories.items.title')"
        >
          <ItemCategoriesTab />
        </BaseTab>

        <BaseTab
          tab-panel-container="py-4 mt-px"
          :title="$t('settings.categories.clients.title')"
        >
          <ClientCategoriesTab />
        </BaseTab>
      </BaseTabGroup>
    </BaseCard>
  </div>
</template>

<script setup>
import ItemCategoriesTab from './ItemCategories.vue'
import ClientCategoriesTab from './ClientCategories.vue'
</script>
