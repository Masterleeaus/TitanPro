import shield from './shield'
import invoices from './invoices'
import payments from './payments'
import subscriptions from './subscriptions'

const resources = {
    shield: Object.assign(shield, shield),
    invoices: Object.assign(invoices, invoices),
    payments: Object.assign(payments, payments),
    subscriptions: Object.assign(subscriptions, subscriptions),
}

export default resources