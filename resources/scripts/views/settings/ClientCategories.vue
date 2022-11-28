<template>
  <ItemCategoryModal />

  <BaseSettingCard
    :title="$t('settings.categories.clients.heading')"
    :description="$t('settings.categories.clients.description')"
  >
    <template #action>
      <BaseButton
        v-if="userStore.hasAbilities(abilities.CREATE_CATEGORY)"
        variant="primary-outline"
        @click="openNoteSelectModal"
      >
        <template #left="slotProps">
          <BaseIcon :class="slotProps.class" name="PlusIcon" />
        </template>
        {{ $t('settings.categories.add') }}
      </BaseButton>
    </template>

    <BaseTable
      ref="table"
      :data="fetchData"
      :columns="notesColumns"
      class="mt-14"
    >
      <template #cell-actions="{ row }">
        <CategoryDropdown
          :row="row.data"
          :table="table"
          :load-data="refreshTable"
          modal-title="settings.categories.clients.edit"
        />
      </template>
    </BaseTable>
  </BaseSettingCard>
</template>

<script setup>
import { computed, reactive, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useModalStore } from '@/scripts/stores/modal'
import { useDialogStore } from '@/scripts/stores/dialog'
import { useNotesStore } from '@/scripts/stores/note'
import { useCategoryStore } from '@/scripts/stores/item_client_category'
import { useNotificationStore } from '@/scripts/stores/notification'
import CategoryDropdown from '@/scripts/components/dropdowns/CategoryDropdown.vue'
import ItemCategoryModal from '@/scripts/components/modal-components/ItemCategoryModal.vue'
import { useUserStore } from '@/scripts/stores/user'
import abilities from '@/scripts/stub/abilities'

const { t } = useI18n()

const modalStore = useModalStore()
const dialogStore = useDialogStore()
const noteStore = useNotesStore()
const categoryStore = useCategoryStore()
const notificationStore = useNotificationStore()
const userStore = useUserStore()

const table = ref('')

const notesColumns = computed(() => {
  return [
    {
      key: 'name',
      label: t('settings.customization.notes.name'),
      thClass: 'extra',
      tdClass: 'font-medium text-gray-900',
    },
    {
      key: 'code',
      label: t('settings.categories.code'),
      thClass: 'extra',
      tdClass: 'font-medium text-gray-900',
    },

    {
      key: 'actions',
      label: '',
      tdClass: 'text-right text-sm font-medium',
      sortable: false,
    },
  ]
})

async function fetchData({ page, filter, sort }) {
  let data = reactive({
    orderByField: sort.fieldName || 'created_at',
    orderBy: sort.order || 'desc',
    page,
    'type':'client'
  })

  let response = await categoryStore.fetchCategories(data)

  return {
    data: response.data.data,
    pagination: {
      totalPages: response.data.meta.last_page,
      currentPage: page,
      totalCount: response.data.meta.total,
      limit: 5,
    },
  }
}

async function openNoteSelectModal() {
  await modalStore.openModal({
    title: t('settings.categories.clients.add_category'),
    componentName: 'ItemCategoryModal',
    size: 'sm',
    refreshData: table.value && table.value.refresh,
    option1:'client',
  })
}

async function refreshTable() {
  table.value && table.value.refresh()
}
</script>
