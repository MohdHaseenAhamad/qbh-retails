<template>
  <BaseDropdown>
    <template #activator>
      <BaseButton v-if="route.name === 'invoices.view'" variant="primary">
        <BaseIcon name="DotsHorizontalIcon" class="h-5 text-white" />
      </BaseButton>
      <BaseIcon v-else name="DotsHorizontalIcon" class="h-5 text-gray-500" />
    </template>

    <!-- Edit Purchase Voucher  -->
    <router-link
      v-if="userStore.hasAbilities(abilities.EDIT_INVOICE)"
      :to="`/admin/purchases/${row.id}/edit`"
    >
      <BaseDropdownItem v-show="canEditInvoice(row)">
        <BaseIcon
          name="PencilIcon"
          class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
        />
        {{ $t('general.edit') }}
      </BaseDropdownItem>
    </router-link>

    <!-- View Purchase Voucher  -->
    <router-link
      v-if="
        route.name !== 'invoices.view' &&
        userStore.hasAbilities(abilities.VIEW_INVOICE)
      "
      :to="`/admin/purchases/${row.id}/view`"
    >
      <BaseDropdownItem>
        <BaseIcon
          name="EyeIcon"
          class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
        />
        {{ $t('general.view') }}
      </BaseDropdownItem>
    </router-link>

    <!--  Delete Purchase Voucher  -->
    <BaseDropdownItem
      v-if="userStore.hasAbilities(abilities.DELETE_INVOICE)"
      @click="removePurchase(row.id)"
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
import { usePurchaseStore } from '@/scripts/stores/purchase'
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

const purchaseStore = usePurchaseStore()
const modalStore = useModalStore()
const notificationStore = useNotificationStore()
const dialogStore = useDialogStore()
const userStore = useUserStore()

const { t } = useI18n()
const route = useRoute()
const router = useRouter()
const utils = inject('utils')

function canEditInvoice(row) {
  return (
   row.status != 'ACCEPTED' &&
    userStore.hasAbilities(abilities.EDIT_INVOICE)
  )
}

async function removePurchase(id) {
  dialogStore
    .openDialog({
      title: t('general.are_you_sure'),
      message: t('purchases.confirm_delete'),
      yesLabel: t('general.ok'),
      noLabel: t('general.cancel'),
      variant: 'danger',
      hideNoButton: false,
      size: 'lg',
    })
    .then((res) => {
      id = id
      if (res) {
        purchaseStore.deletePurchase({ ids: [id] }).then((res) => {
          if (res.data.success) {
            router.push('/admin/purchases')
            props.table && props.table.refresh()

            purchaseStore.$patch((state) => {
              state.selectedPurchases = []
              state.selectAllField = false
            })
          }
        })
      }
    })
}

</script>
