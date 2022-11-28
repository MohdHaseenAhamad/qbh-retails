<template>
  <div class="relative">
    <BaseCard container-class="px-4 py-5 sm:px-8 sm:py-2">
      <BaseTabGroup>
        <BaseTab
          tab-panel-container="py-4 mt-px"
          :title="$t('settings.customization.invoices.title')"
        >
          <InvoicesTab />
        </BaseTab>

        <BaseTab
          tab-panel-container="py-4 mt-px"
          :title="$t('settings.customization.estimates.title')"
        >
          <EstimatesTab />
        </BaseTab>

        <BaseTab
          tab-panel-container="py-4 mt-px"
          :title="$t('settings.customization.credits.title')"
        >
          <CreditTab />
        </BaseTab>

        <BaseTab
          tab-panel-container="py-4 mt-px"
          :title="$t('settings.customization.debits.title')"
        >
          <DebitTab />
        </BaseTab>

        <BaseTab
          tab-panel-container="py-4 mt-px"
          :title="$t('settings.customization.payments.title')"
        >
          <PaymentsTab />
        </BaseTab>

        <BaseTab
          tab-panel-container="py-4 mt-px"
          :title="$t('settings.customization.items.title')"
        >
          <ItemsTab />
        </BaseTab>
        <BaseTab
          tab-panel-container="py-4 mt-px"
          :title="$t('settings.customization.prepared_by.title')"
        >
          <PreparedByTab />
        </BaseTab>
        <BaseTab
          tab-panel-container="py-4 mt-px"
          :title="$t('settings.customization.proformas.title')"
        >
          <ProformasTab />
        </BaseTab>
        <BaseTab
          tab-panel-container="py-4 mt-px"
          :title="$t('settings.customization.purchases.title')"
        >
          <PurchasesTab />
        </BaseTab>

      </BaseTabGroup>
    </BaseCard>
  </div>
</template>

<script setup>
import InvoicesTab from './invoices/InvoicesTab.vue'
import PurchasesTab from './purchases/PurchasesTab.vue'
import EstimatesTab from './estimates/EstimatesTab.vue'
import PaymentsTab from './payments/PaymentsTab.vue'
import ItemsTab from './items/ItemsTab.vue'
import PreparedByTab from './preparedby/PreparedByTab.vue'
import CreditTab from './creditnotes/CreditTab.vue'
import DebitTab from './debitnotes/DebitTab.vue'
import ProformasTab from './proformas/ProformasTab.vue'
</script>
