import shield from './shield'
import layouts from './layouts'
import customers from './customers'
import invoices from './invoices'
import jobs from './jobs'

const resources = {
    shield: Object.assign(shield, shield),
    layouts: Object.assign(layouts, layouts),
    customers: Object.assign(customers, customers),
    invoices: Object.assign(invoices, invoices),
    jobs: Object.assign(jobs, jobs),
}

export default resources