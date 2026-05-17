import InvoiceResource from './InvoiceResource'
import PaymentResource from './PaymentResource'
import SubscriptionResource from './SubscriptionResource'

const Resources = {
    InvoiceResource: Object.assign(InvoiceResource, InvoiceResource),
    PaymentResource: Object.assign(PaymentResource, PaymentResource),
    SubscriptionResource: Object.assign(SubscriptionResource, SubscriptionResource),
}

export default Resources