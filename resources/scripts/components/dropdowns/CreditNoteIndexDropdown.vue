<template>
  <BaseDropdown>
    <template #activator>
      <BaseButton v-if="route.name === 'items.view'" variant="primary">
        <BaseIcon name="DotsHorizontalIcon" class="h-5 text-white" />
      </BaseButton>
      <BaseIcon v-else name="DotsHorizontalIcon" class="h-5 text-gray-500" />
    </template>

    <!-- CREATE CREDIT NOTE  -->
    <router-link v-show="creditStore.selectedTab != 'credit'"
      :to="`/admin/credit-note/${row.id}/create`"
    >
      <BaseDropdownItem>
        <BaseIcon
          name="PlusIcon"
          class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
        />
        {{ $t('general.create') }}
      </BaseDropdownItem>
    </router-link>

    <!-- VIEW CREDIT NOTE  -->
    <router-link v-show="route.name!='credit.view'"
      :to="`/admin/credit-note/${row.id}/view`"
    >
      <BaseDropdownItem>
        <BaseIcon
          name="EyeIcon"
          class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
        />
        {{ $t('general.view') }}
      </BaseDropdownItem>
    </router-link>

    <!-- EDIT CREDIT NOTE  -->
    <router-link
      :to="`/admin/credit-note/${row.id}/edit`"
    >
      <BaseDropdownItem>
        <BaseIcon
          name="PencilIcon"
          class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
        />
        {{ $t('general.edit') }}
      </BaseDropdownItem>
    </router-link>

    <!-- MARK AS SENT CREDIT -->
    <BaseDropdownItem v-if="canSendCredit(row)" @click="onMarkAsSent(row.id)">
      <BaseIcon
        name="CheckCircleIcon"
        class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
      />
      {{ $t('invoices.mark_as_sent') }}
    </BaseDropdownItem>

    <!-- DELETE CREDIT NOTE  -->
    <BaseDropdownItem
      @click="removeInvoice(row.id)"
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
import { useCreditStore } from '@/scripts/stores/credit'
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

const creditStore = useCreditStore()
const modalStore = useModalStore()
const notificationStore = useNotificationStore()
const dialogStore = useDialogStore()
const userStore = useUserStore()

const { t } = useI18n()
const route = useRoute()
const router = useRouter()
const utils = inject('utils')

async function removeInvoice(id) {
  dialogStore
    .openDialog({
      title: t('general.are_you_sure'),
      message: t('credits.confirm_delete'),
      yesLabel: t('general.ok'),
      noLabel: t('general.cancel'),
      variant: 'danger',
      hideNoButton: false,
      size: 'lg',
    })
    .then((res) => {
      id = id
      if (res) {
        creditStore.deleteInvoice({ ids: [id] }).then((res) => {
          if (res.data.success) {
            router.push('/admin/credit-note')
            props.table && props.table.refresh()

            creditStore.$patch((state) => {
              state.selectedInvoices = []
              state.selectAllField = false
            })
          }
        })
      }
    })
}
function canSendCredit(row) {
  return (
    row.status == 'DRAFT' &&
    route.name !== 'invoices.view' &&
    userStore.hasAbilities(abilities.SEND_INVOICE)
  )
}

async function onMarkAsSent(id) {
  dialogStore
    .openDialog({
      title: t('general.are_you_sure'),
      message: t('creditnote.creditnote_mark_as_sent'),
      yesLabel: t('general.ok'),
      noLabel: t('general.cancel'),
      variant: 'primary',
      hideNoButton: false,
      size: 'lg',
    })
    .then((response) => {
      const data = {
        id: id,
        status: 'SENT',
        is_edit: '0',
      }
      if (response) {
        creditStore.markAsSent(data).then((response) => {
          props.table && props.table.refresh()
        })
          window.location.href = window.location.href
      }
    })
}

</script>
