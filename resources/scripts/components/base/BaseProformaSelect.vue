<template>
  <div class="flex-1 text-sm">
    <!-- Selected Item Field  -->
    <div
      v-if="item && item.id"
      class="
        relative
        flex
        items-center
        h-10
        pl-2
        bg-gray-200
        border border-gray-200 border-solid
        rounded
      "
    >
      {{ item.name }}

      <span
        class="absolute text-gray-400 cursor-pointer top-[8px] right-[10px]"
        @click="deselectPreparedBy()"
      >
        <BaseIcon name="XCircleIcon" />
      </span>
    </div>

    <!-- Select Item Field -->
    <BaseMultiselect
      v-else
      v-model="itemSelect"
      :content-loading="contentLoading"
      value-prop="id"
      track-by="id"
      :invalid="invalid"
      preserve-search
      :initial-search="itemData.name"
      label="name"
      :filterResults="false"
      resolve-on-load
      :delay="500"
      
      :options="searchItems"
      object
      @update:modelValue="(val) => $emit('select', val)"
      @searchChange="(val) => $emit('search', val)"
    >
    </BaseMultiselect>    
  </div>
</template>

<script setup>
import { computed, reactive, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRoute } from 'vue-router'
import { useEstimateStore } from '@/scripts/stores/estimate'
import { useProformaStore } from '@/scripts/stores/proforma'
import { usePreparedByStore } from '@/scripts/stores/prepared-by'
import { useModalStore } from '@/scripts/stores/modal'
import { useUserStore } from '@/scripts/stores/user'
import abilities from '@/scripts/stub/abilities'

const props = defineProps({
  contentLoading: {
    type: Boolean,
    default: false,
  },
  type: {
    type: String,
    default: null,
  },
  item: {
    type: Object,
    required: true,
  },
  invalid: {
    type: Boolean,
    required: false,
    default: false,
  },
  store: {
    type: Object,
    default: null,
  },
  storeProp: {
    type: String,
    default: '',
  },
})

const emit = defineEmits(['search', 'select'])

const preparedByStore = usePreparedByStore()
const estimateStore = useEstimateStore()
const proformaStore = useProformaStore()
const modalStore = useModalStore()
const userStore = useUserStore()

let route = useRoute()
const { t } = useI18n()

const itemSelect = ref(null)
const loading = ref(false)
let itemData = reactive({ ...props.item })
Object.assign(itemData, props.item)

const taxAmount = computed(() => {
  return 0
})

async function searchItems(search) {
  let res = await preparedByStore.fetchPreparedBies({ search })
  return res.data.data
}

function onTextChange(val) {
  searchItems(val)
  emit('search', val)
}

function openItemModal() {
  modalStore.openModal({
    title: t('items.add_prepared_by'),
    componentName: 'PreparedByModal',
    refreshData: (val) => emit('select', val),
    data: {
      store: props.store,
      storeProps: props.storeProp,
    },
    size:18
  })
}

function deselectPreparedBy() {
  props.store.deselectPreparedBy()
}
</script>
