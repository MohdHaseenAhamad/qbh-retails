<template>
  <BaseDropdown>
    <template #activator>
      <BaseButton v-if="route.name === 'items.view'" variant="primary">
        <BaseIcon name="DotsHorizontalIcon" class="h-5 text-white" />
      </BaseButton>
      <BaseIcon v-else name="DotsHorizontalIcon" class="h-5 text-gray-500" />
    </template>

    <!-- CREATE DEBIT NOTE  -->
    <router-link v-show="debitStore.selectedTab != 'debit'"
      :to="`/admin/debit-note/${row.id}/create`"
    >
      <BaseDropdownItem>
        <BaseIcon
          name="PlusIcon"
          class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
        />
        {{ $t('general.create') }}
      </BaseDropdownItem>
    </router-link>

    <!-- VIEW DEBIT NOTE  -->
    <router-link v-show="route.name!='debit.view'"
      :to="`/admin/debit-note/${row.id}/view`"
    >
      <BaseDropdownItem>
        <BaseIcon
          name="EyeIcon"
          class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
        />
        {{ $t('general.view') }}
      </BaseDropdownItem>
    </router-link>

    <!-- EDIT DEBIT NOTE  -->
    <router-link
      :to="`/admin/debit-note/${row.id}/edit`"
    >
      <BaseDropdownItem>
        <BaseIcon
          name="PencilIcon"
          class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
        />
        {{ $t('general.edit') }}
      </BaseDropdownItem>
    </router-link>

    <!-- MARK AS SENT DEBIT -->
    <BaseDropdownItem v-if="canSendDebit(row)" @click="onMarkAsSent(row.id)">
      <BaseIcon
        name="CheckCircleIcon"
        class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
      />
      {{ $t('debitnote.mark_as_sent') }}
    </BaseDropdownItem>

    <!-- DELETE DEBIT NOTE  -->
    <BaseDropdownItem
      @click="removeDebit(row.id)"
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
import { useDebitStore } from '@/scripts/stores/debit'
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

const debitStore = useDebitStore()
const modalStore = useModalStore()
const notificationStore = useNotificationStore()
const dialogStore = useDialogStore()
const userStore = useUserStore()

const { t } = useI18n()
const route = useRoute()
const router = useRouter()
const utils = inject('utils')

async function removeDebit(id) {
  dialogStore
    .openDialog({
      title: t('general.are_you_sure'),
      message: t('debits.confirm_delete'),
      yesLabel: t('general.ok'),
      noLabel: t('general.cancel'),
      variant: 'danger',
      hideNoButton: false,
      size: 'lg',
    })
    .then((res) => {
      id = id
      if (res) {
        debitStore.deleteDebitNote({ ids: [id] }).then((res) => {
          if (res.data.success) {
            router.push('/admin/debit-note')
            props.table && props.table.refresh()

            debitStore.$patch((state) => {
              state.selectedInvoices = []
              state.selectAllField = false
            })
          }
        })
      }
    })
}

function canSendDebit(row) {
  return (
    row.status == 'DRAFT' &&
    route.name !== 'debits.view' &&
    userStore.hasAbilities(abilities.SEND_INVOICE)
  )
}

async function onMarkAsSent(id) {
  dialogStore
    .openDialog({
      title: t('general.are_you_sure'),
      message: t('debitnote.debitnote_mark_as_sent'),
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
        debitStore.markAsSent(data).then((response) => {
          props.table && props.table.refresh()
        })
        window.location.href = window.location.href
      }
    })
}

</script>
