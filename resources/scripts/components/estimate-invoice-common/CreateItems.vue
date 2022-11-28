<template>
  <table class="text-center item-table min-w-full">
    <colgroup>
      <col style="width: 30%" />
      <col style="width: 15%" />
      <col style="width: 15%" />
      <col style="width: 15%" />
      
      <span v-show="storeProp=='newInvoice' || storeProp=='newProforma' || storeProp=='newPurchase'|| storeProp=='newEstimate'">
        <col
          v-if="store[storeProp].discount_per_item === 'YES'"
          style="width: 20%"
        />
        <col
          v-else-if="storeProp!='newEstimate'"
          style="width: 20%"
        />
      </span>

      <col style="width: 20%" />
    </colgroup>
    <thead class="bg-white border border-gray-200 border-solid">
      <tr>
        <th
          class="
            px-5
            py-3
            text-sm
            not-italic
            font-medium
            leading-5
            text-left text-gray-700
            border-t border-b border-gray-200 border-solid
          "
        >
          <BaseContentPlaceholders v-if="isLoading">
            <BaseContentPlaceholdersText :lines="1" class="w-16 h-5" />
          </BaseContentPlaceholders>
          <span v-else class="pl-7">
            {{ $t('invoices.item.item') }}
          </span>
        </th>
        <th
          class="
            px-8
            py-3
            text-sm
            not-italic
            font-medium
            leading-5
            text-left text-gray-700
            border-t border-b border-gray-200 border-solid
          "
        >
          <BaseContentPlaceholders v-if="isLoading">
            <BaseContentPlaceholdersText :lines="1" class="w-16 h-5" />
          </BaseContentPlaceholders>
          <span v-else>
            {{ $t('invoices.item.quantity') }}
          </span>
        </th>
        <th
          class="
            px-8
            py-3
            text-sm
            not-italic
            font-medium
            leading-5
            text-left text-gray-700
            border-t border-b border-gray-200 border-solid
          "
        >
          <BaseContentPlaceholders v-if="isLoading">
            <BaseContentPlaceholdersText :lines="1" class="w-16 h-5" />
          </BaseContentPlaceholders>
          <span v-else>
            {{ $t('invoices.item.uom') }}
          </span>
        </th>
        <th
          class="
            px-8
            py-3
            text-sm
            not-italic
            font-medium
            leading-5
            text-left
            text-gray-700
            border-t border-b border-gray-200 border-solid
          "
        >
          <BaseContentPlaceholders v-if="isLoading">
            <BaseContentPlaceholdersText :lines="1" class="w-16 h-5" />
          </BaseContentPlaceholders>
          <span v-else>
            {{ $t('invoices.item.price') }}
          </span>
        </th>

        <span v-show="storeProp=='newInvoice' || storeProp=='newProforma' || storeProp=='newPurchase'|| storeProp=='newEstimate'">
          <th
            v-if="(storeProp=='newEstimate' || store[storeProp].discount_per_item === 'YES')"
            class="
              px-8
              py-3
              text-sm
              not-italic
              font-medium
              leading-5
              text-left text-gray-700
              border-t border-b border-gray-200 border-solid
            "
          >
            <BaseContentPlaceholders v-if="isLoading">
              <BaseContentPlaceholdersText :lines="1" class="w-16 h-5" />
            </BaseContentPlaceholders>
            <span v-else>
              {{ $t('invoices.item.discount') }}
            </span>
          </th>
          <th
            v-else-if="storeProp!='newEstimate'"
            class="
              px-5
              py-3
              text-sm
              not-italic
              font-medium
              leading-5
              text-left text-gray-700
              border-t border-b border-gray-200 border-solid
            "
          >
            <BaseContentPlaceholders v-if="isLoading">
              <BaseContentPlaceholdersText :lines="1" class="w-16 h-5" />
            </BaseContentPlaceholders>
            <span v-else>
              {{ $t('invoices.item.deduction') }}
            </span>
          </th>
        </span>

        <th
          class="
            px-5
            py-3
            text-sm
            not-italic
            font-medium
            leading-5
            text-right text-gray-700
            border-t border-b border-gray-200 border-solid
          "
        >
          <BaseContentPlaceholders v-if="isLoading">
            <BaseContentPlaceholdersText :lines="1" class="w-16 h-5" />
          </BaseContentPlaceholders>
          <span v-else class="pr-10 column-heading">
            {{ $t('invoices.item.amount') }}
          </span>
        </th>
      </tr>
    </thead>
    <draggable
      v-model="store[storeProp].items"
      item-key="id"
      tag="tbody"
      handle=".handle"
    >
      <template #item="{ element, index }">
        <Item
          :key="element.id"
          :index="index"
          :item-data="element"
          :loading="isLoading"
          :currency="defaultCurrency"
          :item-validation-scope="itemValidationScope"
          :invoice-items="store[storeProp].items"
          :store="store"
          :store-prop="storeProp"
          :is-draft-mode="isDraftMode"
        />
      </template>
    </draggable>
  </table>

  <div v-if="isDraftMode"
    class="
      flex
      items-center
      justify-center
      w-full
      px-6
      py-3
      text-base
      border border-t-0 border-gray-200 border-solid
      cursor-pointer
      text-primary-400
      hover:bg-primary-100
    "
    @click="store.addItem"
  >
    <BaseIcon name="PlusCircleIcon" class="mr-2" />
    {{ $t('general.add_new_item') }}
  </div>
</template>

<script setup>
import { useCompanyStore } from '@/scripts/stores/company'
import { computed } from 'vue'
import draggable from 'vuedraggable'
import Item from './CreateItemRow.vue'

const props = defineProps({
  store: {
    type: Object,
    default: null,
  },
  storeProp: {
    type: String,
    default: '',
  },
  currency: {
    type: [Object, String, null],
    required: true,
  },
  isLoading: {
    type: Boolean,
    default: false,
  },
  itemValidationScope: {
    type: String,
    default: '',
  },
  isDraftMode:{
    type: Boolean,
    default: true
  },
})

const companyStore = useCompanyStore()

const defaultCurrency = computed(() => {
  if (props.currency) {
    return props.currency
  } else {
    return companyStore.selectedCompanyCurrency
  }
})
</script>
