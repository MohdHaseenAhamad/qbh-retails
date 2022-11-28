import Guid from 'guid'
import creditnoteItemStub from './creditnote-item'
import taxStub from './tax'

export default function () {
  return {
    id: null,
    debit_number: '',
    debit_date: '',
    purchase_id: null,
    customer: null,
    customer_id: null,
    template_name: null,
    notes: '',
    notes_heading: '',
    discount: 0,
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
        ...creditnoteItemStub,
        id: Guid.raw(),
        taxes: [{ ...taxStub, id: Guid.raw() }],
      },
    ],
    customFields: [],
    fields: [],
    selectedNote: null,
    selectedCurrency: '',
    prepared_by_id:'',
    is_edit: true
  }
}
