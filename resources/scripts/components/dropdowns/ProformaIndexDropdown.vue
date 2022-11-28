<template>
  <BaseDropdown>
    <template #activator>
      <BaseButton v-if="route.name === 'proformas.view'" variant="primary">
        <BaseIcon name="DotsHorizontalIcon" class="h-5 text-white" />
      </BaseButton>
      <BaseIcon v-else name="DotsHorizontalIcon" class="h-5 text-gray-500" />
    </template>

    <!-- Edit Proforma  -->
    <router-link
      v-if="userStore.hasAbilities(abilities.EDIT_INVOICE)"
      :to="`/admin/proformas/${row.id}/edit`"
    >
      <BaseDropdownItem v-show="canEditProforma(row)">
        <BaseIcon
          name="PencilIcon"
          class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
        />
        {{ $t('general.edit') }}
      </BaseDropdownItem>
    </router-link>

    <!-- Copy PDF url  -->
    <BaseDropdownItem v-if="route.name === 'proformas.view'" @click="copyPdfUrl">
      <BaseIcon
        name="LinkIcon"
        class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
      />
      {{ $t('general.copy_pdf_url') }}
    </BaseDropdownItem>

    <!-- View Proforma  -->
    <router-link
      v-if="
        route.name !== 'proformas.view' &&
        userStore.hasAbilities(abilities.VIEW_INVOICE)
      "
      :to="`/admin/proformas/${row.id}/view`"
    >
      <BaseDropdownItem>
        <BaseIcon
          name="EyeIcon"
          class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
        />
        {{ $t('general.view') }}
      </BaseDropdownItem>
    </router-link>

    <!-- Send Proforma Mail  -->
    <BaseDropdownItem v-if="canSendProforma(row)" @click="sendProforma(row)">
      <BaseIcon
        name="PaperAirplaneIcon"
        class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
      />
      {{ $t('proformas.send_proforma') }}
    </BaseDropdownItem>

    <!-- Resend Proforma -->
    <BaseDropdownItem v-if="canReSendProforma(row)" @click="sendProforma(row)">
      <BaseIcon
        name="PaperAirplaneIcon"
        class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
      />
      {{ $t('proformas.resend_proforma') }}
    </BaseDropdownItem>

    <!-- Record payment  -->
    <router-link :to="`/admin/payments/${row.id}/create`">
      <BaseDropdownItem
        v-if="row.status == 'SENT' && route.name !== 'proformas.view'"
      >
        <BaseIcon
          name="CreditCardIcon"
          class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
        />
        {{ $t('proformas.record_payment') }}
      </BaseDropdownItem>
    </router-link>

   

    <!-- Clone Proforma into new proforma  -->
    <BaseDropdownItem
      v-if="userStore.hasAbilities(abilities.CREATE_INVOICE)"
      @click="cloneProformaData(row)"
    >
      <BaseIcon
        name="DocumentTextIcon"
        class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
      />
      {{ $t('proformas.clone_proforma') }}
    </BaseDropdownItem>

    <!--  Delete Proforma  -->
    <BaseDropdownItem
      v-if="userStore.hasAbilities(abilities.DELETE_INVOICE)"
      @click="removeProforma(row.id)"
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
import { useProformaStore } from '@/scripts/stores/proforma'
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

const proformaStore = useProformaStore()
const modalStore = useModalStore()
const notificationStore = useNotificationStore()
const dialogStore = useDialogStore()
const userStore = useUserStore()

const { t } = useI18n()
const route = useRoute()
const router = useRouter()
const utils = inject('utils')

function canReSendProforma(row) {
  return (
    (row.status == 'SENT' || row.status == 'VIEWED') &&
    route.name !== 'proformas.view' &&
    userStore.hasAbilities(abilities.SEND_INVOICE)
  )
}

function canSendProforma(row) {
  return (
    row.status == 'DRAFT' &&
    route.name !== 'proformas.view' &&
    userStore.hasAbilities(abilities.SEND_INVOICE)
  )
}

function canEditProforma(row) {
  return (
   row.status != 'ACCEPTED' &&
    userStore.hasAbilities(abilities.EDIT_INVOICE)
  )
}

async function removeProforma(id) {
  dialogStore
    .openDialog({
      title: t('general.are_you_sure'),
      message: t('proformas.confirm_delete'),
      yesLabel: t('general.ok'),
      noLabel: t('general.cancel'),
      variant: 'danger',
      hideNoButton: false,
      size: 'lg',
    })
    .then((res) => {
      id = id
      if (res) {
        proformaStore.deleteProforma({ ids: [id] }).then((res) => {
          if (res.data.success) {
            router.push('/admin/proformas')
            props.table && props.table.refresh()

            proformaStore.$patch((state) => {
              state.selectedProformas = []
              state.selectAllField = false
            })
          }
        })
      }
    })
}

async function cloneProformaData(data) {
  dialogStore
    .openDialog({
      title: t('general.are_you_sure'),
      message: t('proformas.confirm_clone'),
      yesLabel: t('general.ok'),
      noLabel: t('general.cancel'),
      variant: 'primary',
      hideNoButton: false,
      size: 'lg',
    })
    .then((res) => {
      if (res) {
        proformaStore.cloneProforma(data).then((res) => {
          router.push(`/admin/proformas/${res.data.data.id}/edit`)
        })
      }
    })
}

async function onMarkAsSent(id) {
  dialogStore
    .openDialog({
      title: t('general.are_you_sure'),
      message: t('proformas.proforma_mark_as_sent'),
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
      }
      if (response) {
        proformaStore.markAsSent(data).then((response) => {
          props.table && props.table.refresh()
        })
      }
    })
}

async function sendProforma(proforma) {
  modalStore.openModal({
    title: t('proformas.send_proforma'),
    componentName: 'SendProformaModal',
    id: proforma.id,
    data: proforma,
    variant: 'sm',
  })
}

function copyPdfUrl() {
  let pdfUrl = `${window.location.origin}/proformas/pdf/${props.row.unique_hash}`

  utils.copyTextToClipboard(pdfUrl)

  notificationStore.showNotification({
    type: 'success',
    message: t('general.copied_pdf_url_clipboard'),
  })
}
</script>
