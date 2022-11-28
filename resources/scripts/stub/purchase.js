import Guid from 'guid'
import purchaseItemStub from './purchase-item'
import taxStub from './tax'

export default function () {
  return {
    id: null,
    purchase_no: '',
    customer: null, //Used for Supplier
    customer_id: null,//Used For Supplier ID
    template_name: null,
    order_date: '',
    purchase_date: '',
    supply_date: '',
    invoice_date: '',
    notes: '',
    notes_heading: '',
    discount: 0,
    material_receipt: '',
    order_no: '',
    discount_type: 'fixed',
    discount_val: 0,
    reference_number: null,
    tax: 0,
    sub_total: 0,
    total: 0,
    tax_per_item: null,
    discount_per_item: null,
    taxes: [],
    items: [
      {
        ...purchaseItemStub,
        id: Guid.raw(),
        taxes: [{ ...taxStub, id: Guid.raw() }],
      },
    ],
    customFields: [],
    fields: [],
    selectedNote: null,
    selectedCurrency: '',
    prepared_by_id:''
  }
}
