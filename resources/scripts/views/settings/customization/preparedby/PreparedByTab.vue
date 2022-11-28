<template>
  <PreparedByModal />

  <div class="flex flex-wrap justify-end mt-2 lg:flex-nowrap">
    <BaseButton variant="primary-outline" @click="addPreparedBy">
      <template #left="slotProps">
        <BaseIcon :class="slotProps.class" name="PlusIcon" />
      </template>
      {{ $t('settings.customization.prepared_by.add_prepared_by') }}
    </BaseButton>
  </div>

  <BaseTable ref="table" class="mt-10" :data="fetchData" :columns="columns">
    <template #cell-actions="{ row }">
      <BaseDropdown>
        <template #activator>
          <div class="inline-block">
            <BaseIcon name="DotsHorizontalIcon" class="text-gray-500" />
          </div>
        </template>

        <BaseDropdownItem @click="editPreparedBy(row)">
          <BaseIcon
            name="PencilIcon"
            class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
          />

          {{ $t('general.edit') }}
        </BaseDropdownItem>
        <BaseDropdownItem @click="removePreparedBy(row)">
          <BaseIcon
            name="TrashIcon"
            class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"
          />
          {{ $t('general.delete') }}
        </BaseDropdownItem>
      </BaseDropdown>
    </template>
  </BaseTable>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { usePreparedByStore } from '@/scripts/stores/prepared-by'
import { useModalStore } from '@/scripts/stores/modal'
import { useDialogStore } from '@/scripts/stores/dialog'
import PreparedByModal from '@/scripts/components/modal-components/PreparedByModal.vue'

const { t } = useI18n()
const table = ref(null)

const preparedByStore = usePreparedByStore()
const modalStore = useModalStore()
const dialogStore = useDialogStore()

const columns = computed(() => {
  return [
    {
      key: 'name',
      label: t('settings.customization.prepared_by.name'),
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
  let data = {
    page,
  }
  let response = await preparedByStore.fetchPreparedBies(data)

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

async function addPreparedBy() {
  modalStore.openModal({
    title: t('items.add_prepared_by'),
    componentName: 'PreparedByModal',
    refreshData: table.value.refresh,
    size: 'sm',
  })
}

async function editPreparedBy(row) {
  console.log(row.data.id,'row.data.idrow.data.idrow.data.id');
  preparedByStore.fetchPreparedBy(row.data.id)
  modalStore.openModal({
    title: t('items.add_prepared_by'),
    componentName: 'PreparedByModal',
    id: row.data.id,
    data: row.data,
    refreshData: table.value && table.value.refresh,
  })
}

function removePreparedBy(row) {
  dialogStore
    .openDialog({
      title: t('general.are_you_sure'),
      message: t('settings.customization.prepared_by.prepared_by_confirm_delete'),
      yesLabel: t('general.yes'),
      noLabel: t('general.no'),
      variant: 'danger',
      hideNoButton: false,
      size: 'lg',
    })
    .then(async (res) => {
      if (res) {
        await preparedByStore.deletePreparedBy(row.data.id)
        table.value && table.value.refresh()
      }
    })
}
</script>
