<template>
  <BaseDropdown>
    <template #activator>
      <BaseButton v-if="route.name === 'notes.view'" variant="primary">
        <BaseIcon name="DotsHorizontalIcon" class="h-5 text-white" />
      </BaseButton>
      <BaseIcon v-else name="DotsHorizontalIcon" class="h-5 text-gray-500" />
    </template>

    <!-- edit note  -->
    <BaseDropdownItem
      @click="editCategory(row.id)"
    >
      <BaseIcon
        name="PencilIcon"
        class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
      />
      {{ $t('general.edit') }}
    </BaseDropdownItem>

    <!-- delete note  -->
    <BaseDropdownItem
      @click="removeCategory(row.id)"
    >
      <BaseIcon
        name="TrashIcon"
        class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
      />
      {{ $t('general.delete') }}
    </BaseDropdownItem>
  </BaseDropdown>
</template>

<script setup>
import { useDialogStore } from '@/scripts/stores/dialog'
import { useNotificationStore } from '@/scripts/stores/notification'
import { useI18n } from 'vue-i18n'
import { useCategoryStore } from '@/scripts/stores/item_client_category'
import { useRoute } from 'vue-router'
import { inject } from 'vue'
import { useUserStore } from '@/scripts/stores/user'
import { useModalStore } from '@/scripts/stores/modal'
import abilities from '@/scripts/stub/abilities'

const props = defineProps({
  row: {
    type: Object,
    default: null,
  },
  table: {
    type: Object,
    default: null,
  },
  loadData: {
    type: Function,
    default: null,
  },
  modalTitle:{
    type: String
  }
})

const dialogStore = useDialogStore()
const notificationStore = useNotificationStore()
const { t } = useI18n()
const categoryStore = useCategoryStore()
const route = useRoute()
const userStore = useUserStore()
const modalStore = useModalStore()

const $utils = inject('utils')

function editCategory(data) {
  categoryStore.fetchCategory(data)
  modalStore.openModal({
    title: t(props.modalTitle),
    componentName: 'ItemCategoryModal',
    size: 'sm',
    refreshData: props.loadData,
  })
}

function removeCategory(id) {
  dialogStore
    .openDialog({
      title: t('general.are_you_sure'),
      message: t('settings.categories.confirm_delete'),
      yesLabel: t('general.yes'),
      noLabel: t('general.no'),
      variant: 'danger',
      hideNoButton: false,
      size: 'lg',
    })
    .then(async () => {
      let response = await categoryStore.deleteCategory(id)
      if (response.data.success) {
        notificationStore.showNotification({
          type: 'success',
          message: t('settings.categories.deleted_message'),
        })
      } else {
        notificationStore.showNotification({
          type: 'error',
          message: t('settings.categories.already_in_use'),
        })
      }
      props.loadData && props.loadData()
    })
}
</script>
