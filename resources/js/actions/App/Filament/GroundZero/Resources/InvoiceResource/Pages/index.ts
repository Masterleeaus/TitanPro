import ListInvoices from './ListInvoices'
import CreateInvoice from './CreateInvoice'
import EditInvoice from './EditInvoice'

const Pages = {
    ListInvoices: Object.assign(ListInvoices, ListInvoices),
    CreateInvoice: Object.assign(CreateInvoice, CreateInvoice),
    EditInvoice: Object.assign(EditInvoice, EditInvoice),
}

export default Pages