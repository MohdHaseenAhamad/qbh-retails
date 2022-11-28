import Guid from 'guid'
import proformaItemStub from './proforma-item'
import taxStub from './tax'

export default function () {
  return {
    id: null,
    proforma_number: '',
    customer: null,
    customer_id: null,
    template_name: null,
    proforma_date: '',
    due_date: '',
    notes: '',
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
        ...proformaItemStub,
        id: Guid.raw(),
        taxes: [{ ...taxStub, id: Guid.raw() }],
      },
    ],
    customFields: [],
    fields: [],
    selectedNote: null,
    selectedCurrency: '',
    prepared_by_id:'',
    bank_detail_id:''

  }
}
