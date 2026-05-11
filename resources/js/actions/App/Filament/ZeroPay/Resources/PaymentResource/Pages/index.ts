import ListPayments from './ListPayments'
import CreatePayment from './CreatePayment'
import EditPayment from './EditPayment'

const Pages = {
    ListPayments: Object.assign(ListPayments, ListPayments),
    CreatePayment: Object.assign(CreatePayment, CreatePayment),
    EditPayment: Object.assign(EditPayment, EditPayment),
}

export default Pages