import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from '@/scripts/stores/user'
import { useGlobalStore } from '@/scripts/stores/global'
import LayoutBasic from '@/scripts/layouts/LayoutBasic.vue'
import LayoutLogin from '@/scripts/layouts/LayoutLogin.vue'
import Licence from '@/scripts/layouts/Licence.vue'
import abilities from '@/scripts/stub/abilities' 

const LayoutInstallation = () =>
  import('@/scripts/layouts/LayoutInstallation.vue')

const Login = () => import('@/scripts/views/auth/Login.vue')
const ResetPassword = () => import('@/scripts/views/auth/ResetPassword.vue')
const ForgotPassword = () => import('@/scripts/views/auth/ForgotPassword.vue')

// Dashboard 
const Dashboard = () => import('@/scripts/views/dashboard/Dashboard.vue')

// Customers 
const CustomerIndex = () => import('@/scripts/views/customers/Index.vue')
const CustomerCreate = () => import('@/scripts/views/customers/Create.vue')
const CustomerView = () => import('@/scripts/views/customers/View.vue')

// supplier
const SupplierIndex = () => import('@/scripts/views/suppliers/Index.vue')
const SupplierCreate = () => import('@/scripts/views/suppliers/Create.vue')
const SupplierView = () => import('@/scripts/views/suppliers/View.vue')

// stores
const StoreIndex = () => import('@/scripts/views/stores/Index.vue')
const StoreCreate = () => import('@/scripts/views/stores/Create.vue')
const StoreView = () => import('@/scripts/views/stores/View.vue')

//Settings
const SettingsIndex = () => import('@/scripts/views/settings/SettingsIndex.vue')
const LicenceExpiredView = () => import('@/scripts/views/settings/LicenceExpiredView.vue')
const AccountSetting = () =>
  import('@/scripts/views/settings/AccountSetting.vue')
const CompanyInfo = () =>
  import('@/scripts/views/settings/CompanyInfoSettings.vue')
const LicenceDetails = () =>
  import('@/scripts/views/settings/LicenceDetails.vue')
const BankDetail = () =>
  import('@/scripts/views/settings/BankDetailsSettings.vue')
const Preferences = () =>
  import('@/scripts/views/settings/PreferencesSetting.vue')
const Customization = () =>
  import('@/scripts/views/settings/customization/CustomizationSetting.vue')
const Notifications = () =>
  import('@/scripts/views/settings/NotificationsSetting.vue')
const TaxTypes = () => import('@/scripts/views/settings/TaxTypesSetting.vue')
const PaymentMode = () =>
  import('@/scripts/views/settings/PaymentsModeSetting.vue')
const CustomFieldsIndex = () =>
  import('@/scripts/views/settings/CustomFieldsSetting.vue')
// const ItemCategorySetting = () => import('@/scripts/views/settings/ItemCategorySetting.vue')
// const ClientCategorySetting = () => import('@/scripts/views/settings/ClientCategorySetting.vue')
const CategoriesSetting = () => import('@/scripts/views/settings/Categories.vue')
const NotesSetting = () => import('@/scripts/views/settings/NotesSetting.vue')
const ExpenseCategory = () =>
  import('@/scripts/views/settings/ExpenseCategorySetting.vue')
const ExchangeRateSetting = () =>
  import('@/scripts/views/settings/ExchangeRateProviderSetting.vue')
const MailConfig = () =>
  import('@/scripts/views/settings/MailConfigSetting.vue')
const FileDisk = () => import('@/scripts/views/settings/FileDiskSetting.vue')
const Backup = () => import('@/scripts/views/settings/BackupSetting.vue')
const UpdateApp = () => import('@/scripts/views/settings/UpdateAppSetting.vue')
const RolesSettings = () => import('@/scripts/views/settings/RolesSettings.vue')

// Items
const ItemsIndex = () => import('@/scripts/views/items/Index.vue')
const ItemCreate = () => import('@/scripts/views/items/Create.vue')

// Expenses
const ExpensesIndex = () => import('@/scripts/views/expenses/Index.vue')
const ExpenseCreate = () => import('@/scripts/views/expenses/Create.vue')

// Users
const UserIndex = () => import('@/scripts/views/users/Index.vue')
const UserCreate = () => import('@/scripts/views/users/Create.vue')

// Estimates
const EstimateIndex = () => import('@/scripts/views/estimates/Index.vue')
const EstimateCreate = () =>
  import('@/scripts/views/estimates/create/EstimateCreate.vue')
const EstimateView = () => import('@/scripts/views/estimates/View.vue')

// Payments  
const PaymentsIndex = () => import('@/scripts/views/payments/Index.vue')
const PaymentCreate = () => import('@/scripts/views/payments/Create.vue')
const PaymentView = () => import('@/scripts/views/payments/View.vue')

const NotFoundPage = () => import('@/scripts/views/errors/404.vue')

// Invoice
const InvoiceIndex = () => import('@/scripts/views/invoices/Index.vue')
const InvoiceCreate = () =>
  import('@/scripts/views/invoices/create/InvoiceCreate.vue')
const InvoiceView = () => import('@/scripts/views/invoices/View.vue')

// Purchase///
const PurchaseIndex = () => import('@/scripts/views/purchases/Index.vue')
const PurchaseCreate = () =>
  import('@/scripts/views/purchases/create/PurchaseCreate.vue')
const PurchaseView = () => import('@/scripts/views/purchases/View.vue')

// Proforma
const ProformaIndex = () => import('@/scripts/views/proformas/Index.vue')
const ProformaCreate = () =>
  import('@/scripts/views/proformas/create/ProformaCreate.vue')
const ProformaView = () => import('@/scripts/views/proformas/View.vue')

// Credit Note
const CreditNoteIndex = () => import('@/scripts/views/credit-notes/Index.vue')
const CreditNoteCreate = () => import('@/scripts/views/credit-notes/create/CreditNoteCreate.vue')
const CreditNoteView = () => import('@/scripts/views/credit-notes/View.vue')
const CreateCreditNote = () => import('@/scripts/views/credit-notes/SelectInvoice.vue')

// Debit Note
const DebitNoteIndex = () => import('@/scripts/views/debit-notes/Index.vue')
const DebitNoteCreate = () => import('@/scripts/views/debit-notes/create/DebitNoteCreate.vue')
const DebitNoteView = () => import('@/scripts/views/debit-notes/View.vue')
const CreateDebitNote = () => import('@/scripts/views/debit-notes/SelectPurchase.vue')

// Recurring Invoice
const RecurringInvoiceIndex = () =>
  import('@/scripts/views/recurring-invoices/Index.vue')
const RecurringInvoiceCreate = () =>
  import('@/scripts/views/recurring-invoices/create/RecurringInvoiceCreate.vue')
const RecurringInvoiceView = () =>
  import('@/scripts/views/recurring-invoices/View.vue')

// Reports
const ReportsIndex = () => import('@/scripts/views/reports/layout/Index.vue')

// Installation
const Installation = () =>
  import('@/scripts/views/installation/Installation.vue')

let routes = [
  {
    path: '/installation',
    component: LayoutInstallation,
    meta: { requiresAuth: false },
    children: [
      {
        path: '/installation',
        component: Installation,
        name: 'installation',
      },
    ],
  },
  {
    path: '/',
    component: LayoutLogin,
    meta: { requiresAuth: false, redirectIfAuthenticated: true },
    children: [
      {
        path: '/',
        component: Login,
      },
      {
        path: 'login',
        name: 'login',
        component: Login,
      },
      {
        path: 'forgot-password',
        component: ForgotPassword,
        name: 'forgot-password',
      },
      {
        path: '/reset-password/:token',
        component: ResetPassword,
        name: 'reset-password',
      },
    ],
  },
  {
    path: '/licence',
    component: Licence,
    meta: { requiresAuth: false, redirectIfAuthenticated: false },
    children: [
      {
        path: 'expired',
        name: 'licence-expired',
        component: LicenceExpiredView,
      }
    ],
  },
  {
    path: '/admin',
    component: LayoutBasic,
    meta: { requiresAuth: true },
    children: [
      {
        path: 'dashboard',
        name: 'dashboard',
        meta: { ability: abilities.DASHBOARD },
        component: Dashboard,
      },

      // Customers
      {
        path: 'customers',
        meta: { ability: abilities.VIEW_CUSTOMER },
        component: CustomerIndex,
      },
      {
        path: 'customers/create',
        name: 'customers.create',
        meta: { ability: abilities.CREATE_CUSTOMER },
        component: CustomerCreate,
      },
      {
        path: 'customers/:id/edit',
        name: 'customers.edit',
        meta: { ability: abilities.EDIT_CUSTOMER },
        component: CustomerCreate,
      },
      {
        path: 'customers/:id/view',
        name: 'customers.view',
        meta: { ability: abilities.VIEW_CUSTOMER },
        component: CustomerView,
      },
      // Supplier
      {
        path: 'suppliers',
        meta: { ability: abilities.VIEW_CUSTOMER },
        component: SupplierIndex,
      },
      {
        path: 'suppliers/create',
        name: 'suppliers.create',
        meta: { ability: abilities.CREATE_CUSTOMER },
        component: SupplierCreate,
      },
      {
        path: 'suppliers/:id/edit',
        name: 'suppliers.edit',
        meta: { ability: abilities.EDIT_CUSTOMER },
        component: SupplierCreate,
      },
      {
        path: 'suppliers/:id/view',
        name: 'suppliers.view',
        meta: { ability: abilities.VIEW_CUSTOMER },
        component: SupplierView,
      },
      // Stores
      {
        path: 'stores',
        meta: { ability: abilities.VIEW_STORE },
        component: StoreIndex,
      },
      {
        path: 'stores/create',
        name: 'stores.create',
        meta: { ability: abilities.CREATE_STORE },
        component: StoreCreate,
      },
      {
        path: 'stores/:id/edit',
        name: 'stores.edit',
        meta: { ability: abilities.EDIT_STORE },
        component: StoreCreate,
      },
      {
        path: 'stores/:id/view',
        name: 'stores.view',
        meta: { ability: abilities.VIEW_STORE },
        component: StoreView,
      },
      // Payments
      {
        path: 'payments',
        meta: { ability: abilities.VIEW_PAYMENT },
        component: PaymentsIndex,
      },
      {
        path: 'payments/create',
        name: 'payments.create',
        meta: { ability: abilities.CREATE_PAYMENT },
        component: PaymentCreate,
      },
      {
        path: 'payments/:id/create',
        name: 'invoice.payments.create',
        meta: { ability: abilities.CREATE_PAYMENT },
        component: PaymentCreate,
      },
      {
        path: 'payments/:id/edit',
        name: 'payments.edit',
        meta: { ability: abilities.EDIT_PAYMENT },
        component: PaymentCreate,
      },
      {
        path: 'payments/:id/view',
        name: 'payments.view',
        meta: { ability: abilities.VIEW_PAYMENT },
        component: PaymentView,
      },
      {
        path: 'licence_expired',
        name: 'licence.expired',
        component: LicenceExpiredView,
      },

      //settings
      {
        path: 'settings',
        name: 'settings',
        component: SettingsIndex,
        children: [
          {
            path: 'account-settings',
            name: 'account.settings',
            component: AccountSetting,
          },
          {
            path: 'company-info',
            name: 'company.info',
            // meta: { isOwner: true },
            meta: { ability: abilities.MANAGE_COMPANY },
            component: CompanyInfo,
          },
          {
            path: 'licence',
            name: 'licence.details',
            component: LicenceDetails,
          },
          {
            path: 'bank-details',
            name: 'bank.detail',
            // meta: { isOwner: true },
            meta: { ability: abilities.MANAGE_BANK },
            component: BankDetail,
          },
          {
            path: 'preferences',
            name: 'preferences',
            // meta: { isOwner: true },
            meta: { ability: abilities.MANAGE_PREFERENCE },
            component: Preferences,
          },
          {
            path: 'customization',
            name: 'customization',
            // meta: { isOwner: true },
            meta: { ability: abilities.MANAGE_CUSTOMIZATION },
            component: Customization,
          },
          {
            path: 'notifications',
            name: 'notifications',
            meta: { isOwner: true },
            component: Notifications,
          },
          {
            path: 'roles-settings',
            name: 'roles.settings',
            meta: { isOwner: true },
            component: RolesSettings,
          },
          {
            path: 'exchange-rate-provider',
            name: 'exchange.rate.provider',
            meta: { ability: abilities.VIEW_EXCHANGE_RATE },
            component: ExchangeRateSetting,
          },
          {
            path: 'tax-types',
            name: 'tax.types',
            meta: { ability: abilities.VIEW_TAX_TYPE },
            component: TaxTypes,
          },
          {
            path: 'categories',
            name: 'categories',
            component: CategoriesSetting,
          },
          {
            path: 'notes',
            name: 'notes',
            meta: { ability: abilities.VIEW_ALL_NOTES },
            component: NotesSetting,
          },
          {
            path: 'payment-mode',
            name: 'payment.mode',
            component: PaymentMode,
          },
          {
            path: 'custom-fields',
            name: 'custom.fields',
            meta: { ability: abilities.VIEW_CUSTOM_FIELDS },
            component: CustomFieldsIndex,
          },
          {
            path: 'expense-category',
            name: 'expense.category',
            meta: { ability: abilities.VIEW_EXPENSE },
            component: ExpenseCategory,
          },

          {
            path: 'mail-configuration',
            name: 'mailconfig',
            meta: { isOwner: true },
            component: MailConfig,
          },
          {
            path: 'file-disk',
            name: 'file-disk',
            meta: { isOwner: true },
            component: FileDisk,
          },
          {
            path: 'backup',
            name: 'backup',
            meta: { isOwner: true },
            component: Backup,
          },
          {
            path: 'update-app',
            name: 'updateapp',
            meta: { isOwner: false },
            component: UpdateApp,
          },
        ],
      },

      // Items
      {
        path: 'items',
        meta: { ability: abilities.VIEW_ITEM },
        component: ItemsIndex,
      },
      {
        path: 'items/create',
        name: 'items.create',
        meta: { ability: abilities.CREATE_ITEM },
        component: ItemCreate,
      },
      {
        path: 'items/:id/edit',
        name: 'items.edit',
        meta: { ability: abilities.EDIT_ITEM },
        component: ItemCreate,
      },

      // Expenses
      {
        path: 'expenses',
        meta: { ability: abilities.VIEW_EXPENSE },
        component: ExpensesIndex,
      },
      {
        path: 'expenses/create',
        name: 'expenses.create',
        meta: { ability: abilities.CREATE_EXPENSE },
        component: ExpenseCreate,
      },
      {
        path: 'expenses/:id/edit',
        name: 'expenses.edit',
        meta: { ability: abilities.EDIT_EXPENSE },
        component: ExpenseCreate,
      },

      // Users
      {
        path: 'users',
        name: 'users.index',
        meta: { isOwner: true },
        component: UserIndex,
      },
      {
        path: 'users/create',
        meta: { isOwner: true },
        name: 'users.create',
        component: UserCreate,
      },
      {
        path: 'users/:id/edit',
        name: 'users.edit',
        meta: { isOwner: true },
        component: UserCreate,
      },

      // Estimates
      {
        path: 'estimates',
        name: 'estimates.index',
        meta: { ability: abilities.VIEW_ESTIMATE },
        component: EstimateIndex,
      },
      {
        path: 'estimates/create',
        name: 'estimates.create',
        meta: { ability: abilities.CREATE_ESTIMATE },
        component: EstimateCreate,
      },
      {
        path: 'estimates/:id/view',
        name: 'estimates.view',
        meta: { ability: abilities.VIEW_ESTIMATE },
        component: EstimateView,
      },
      {
        path: 'estimates/:id/edit',
        name: 'estimates.edit',
        meta: { ability: abilities.EDIT_ESTIMATE },
        component: EstimateCreate,
      },

      // Invoices
      {
        path: 'invoices',
        name: 'invoices.index',
        meta: { ability: abilities.VIEW_INVOICE },
        component: InvoiceIndex,
      },
      {
        path: 'invoices/create',
        name: 'invoices.create',
        meta: { ability: abilities.CREATE_INVOICE },
        component: InvoiceCreate,
      },
      {
        path: 'invoices/:id/view',
        name: 'invoices.view',
        meta: { ability: abilities.VIEW_INVOICE },
        component: InvoiceView,
      },
      {
        path: 'invoices/:id/edit',
        name: 'invoices.edit',
        meta: { ability: abilities.EDIT_INVOICE },
        component: InvoiceCreate,
      },

      //purchases//////////............

      {
        path: 'purchases',
        name: 'purchases.index',
        meta: { ability: abilities.VIEW_PURCHASE },
        component: PurchaseIndex,
      },
      {
        path: 'purchases/create',
        name: 'purchases.create',
        meta: { ability: abilities.CREATE_PURCHASE },
        component: PurchaseCreate,
      },
      {
        path: 'purchases/:id/view',
        name: 'purchases.view',
        meta: { ability: abilities.VIEW_PURCHASE },
        component: PurchaseView,
      },
      {
        path: 'purchases/:id/edit',
        name: 'purchases.edit',
        meta: { ability: abilities.EDIT_PURCHASE },
        component: PurchaseCreate,
      },

      // Credit Notes
      {
        path: 'credit-note',
        name: 'credit.index',
        meta: { ability: abilities.VIEW_CREDIT_NOTE },
        component: CreditNoteIndex,
      },
      {
        path: 'credit-note/:id/create',
        name: 'credit.create',
        meta: { ability: abilities.CREATE_CREDIT_NOTE },
        component: CreditNoteCreate,
      },
      {
        path: 'credit-note/:id/view',
        name: 'credit.view',
        meta: { ability: abilities.VIEW_CREDIT_NOTE },
        component: CreditNoteView,
      },
      {
        path: 'credit-note/create',
        name: 'credit_note.create',
        meta: { ability: abilities.CREATE_CREDIT_NOTE },
        component: CreateCreditNote,
      },
      {
        path: 'credit-note/:id/edit',
        name: 'credit.edit',
        meta: { ability: abilities.EDIT_CREDIT_NOTE },
        component: CreditNoteCreate,
      },

      // Debit Notes
      {
        path: 'debit-note',
        name: 'debit.index',
        meta: { ability: abilities.VIEW_DEBIT_NOTE },
        component: DebitNoteIndex,
      },
      {
        path: 'debit-note/:id/create',
        name: 'debit.create',
        meta: { ability: abilities.CREATE_DEBIT_NOTE },
        component: DebitNoteCreate,
      },
      {
        path: 'debit-note/:id/view',
        name: 'debit.view',
        meta: { ability: abilities.VIEW_DEBIT_NOTE },
        component: DebitNoteView,
      },
      {
        path: 'debit-note/create',
        name: 'debit_note.create',
        meta: { ability: abilities.CREATE_DEBIT_NOTE },
        component: CreateDebitNote,
      },
      {
        path: 'debit-note/:id/edit',
        name: 'debit.edit',
        meta: { ability: abilities.EDIT_DEBIT_NOTE },
        component: DebitNoteCreate,
      },

      // Proformas
      {
        path: 'proformas',
        name: 'proformas.index',
        meta: { ability: abilities.VIEW_PROFORMA },
        component: ProformaIndex,
      },
      {
        path: 'proformas/create',
        name: 'proformas.create',
        meta: { ability: abilities.CREATE_PROFORMA },
        component: ProformaCreate,
      },
      {
        path: 'proformas/:id/view',
        name: 'proformas.view',
        meta: { ability: abilities.VIEW_PROFORMA },
        component: ProformaView,
      },
      {
        path: 'proformas/:id/edit',
        name: 'proformas.edit',
        meta: { ability: abilities.EDIT_PROFORMA },
        component: ProformaCreate,
      },

      // Recurring Invoices
      {
        path: 'recurring-invoices',
        name: 'recurring-invoices.index',
        meta: { ability: abilities.VIEW_RECURRING_INVOICE },
        component: RecurringInvoiceIndex,
      },
      {
        path: 'recurring-invoices/create',
        name: 'recurring-invoices.create',
        meta: { ability: abilities.CREATE_RECURRING_INVOICE },
        component: RecurringInvoiceCreate,
      },
      {
        path: 'recurring-invoices/:id/view',
        name: 'recurring-invoices.view',
        meta: { ability: abilities.VIEW_RECURRING_INVOICE },
        component: RecurringInvoiceView,
      },
      {
        path: 'recurring-invoices/:id/edit',
        name: 'recurring-invoices.edit',
        meta: { ability: abilities.EDIT_RECURRING_INVOICE },
        component: RecurringInvoiceCreate,
      },

      // Reports
      {
        path: 'reports',
        meta: { ability: abilities.VIEW_FINANCIAL_REPORT },
        component: ReportsIndex,
      },
    ],
  },
  { path: '/:catchAll(.*)', component: NotFoundPage },
]

const router = createRouter({
  history: createWebHistory(),
  linkActiveClass: 'active',
  routes,
})

router.beforeEach((to, from, next) => {
  const userStore = useUserStore()
  const globalStore = useGlobalStore()
  let ability = to.meta.ability
  const { isAppLoaded } = globalStore

  if (ability && isAppLoaded && to.meta.requiresAuth) {
    if (userStore.hasAbilities(ability)) {
      next()
    } else next({ name: 'account.settings' })
  } else if (to.meta.isOwner && isAppLoaded) {
    if (userStore.currentUser.is_owner) {
      next()
    } else next({ name: 'dashboard' })
  } else {
    next()
  }
})

export default router
