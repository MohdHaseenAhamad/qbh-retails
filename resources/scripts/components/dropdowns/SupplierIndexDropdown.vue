<template>
  <BaseDropdown :content-loading="supplierStore.isFetchingViewData">
    <template #activator>
      <BaseButton v-if="route.name === 'suppliers.view'" variant="primary">
        <BaseIcon name="DotsHorizontalIcon" class="h-5 text-white" />
      </BaseButton>
      <BaseIcon v-else name="DotsHorizontalIcon" class="h-5 text-gray-500" />
    </template>

    <!-- Edit Customer  -->
    <router-link
      v-if="userStore.hasAbilities(abilities.EDIT_CUSTOMER)"
      :to="`/admin/suppliers/${row.id}/edit`"
    >
      <BaseDropdownItem>
        <BaseIcon
          name="PencilIcon"
          class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
        />
        {{ $t('general.edit') }}
      </BaseDropdownItem>
    </router-link>

    <!-- View Customer -->
    <router-link
      v-if="
        route.name !== 'suppliers.view' &&
        userStore.hasAbilities(abilities.VIEW_CUSTOMER)
      "
      :to="`suppliers/${row.id}/view`"
    >
      <BaseDropdownItem>
        <BaseIcon
          name="EyeIcon"
          class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
        />
        {{ $t('general.view') }}
      </BaseDropdownItem>
    </router-link>

    <!-- Delete Customer  -->
    <BaseDropdownItem
      v-if="userStore.hasAbilities(abilities.DELETE_CUSTOMER)"
      @click="removeSuppleir(row.id)"
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
import { useSupplierStore } from '@/scripts/stores/supplier'
import { useNotificationStore } from '@/scripts/stores/notification'
import { useDialogStore } from '@/scripts/stores/dialog'
import { useModalStore } from '@/scripts/stores/modal'
import { useI18n } from 'vue-i18n'
import { useRoute, useRouter } from 'vue-router'
import { useUserStore } from '@/scripts/stores/user'
import { inject } from 'vue'
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
    default: () => {},
  },
})

const supplierStore = useSupplierStore()
const notificationStore = useNotificationStore()
const dialogStore = useDialogStore()
const userStore = useUserStore()

const { t } = useI18n()
const route = useRoute()
const router = useRouter()
const utils = inject('utils')

function removeSuppleir(id) {
  // alert(id)
  dialogStore
    .openDialog({
      title: t('general.are_you_sure'),
      message: t('suppliers.confirm_delete', 1),
      yesLabel: t('general.ok'),
      noLabel: t('general.cancel'),
      variant: 'danger',
      hideNoButton: false,
      size: 'lg',
    })
    .then((res) => {
      if (res) {
        supplierStore.deleteSupplier({ ids: [id] }).then((response) => {
          if (response.data.success) {
            props.loadData && props.loadData()
            return true
          }
        })
      }
    })
}
</script>
