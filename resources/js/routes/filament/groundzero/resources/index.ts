import shield from './shield'
import layouts from './layouts'
import customers from './customers'
import estimates from './estimates'
import invoices from './invoices'
import jobs from './jobs'
import properties from './properties'
import team from './team'

const resources = {
    shield: Object.assign(shield, shield),
    layouts: Object.assign(layouts, layouts),
    customers: Object.assign(customers, customers),
    estimates: Object.assign(estimates, estimates),
    invoices: Object.assign(invoices, invoices),
    jobs: Object.assign(jobs, jobs),
    properties: Object.assign(properties, properties),
    team: Object.assign(team, team),
}

export default resources