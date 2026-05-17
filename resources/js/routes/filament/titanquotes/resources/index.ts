import shield from './shield'
import customers from './customers'
import estimatePackages from './estimate-packages'
import estimates from './estimates'
import items from './items'

const resources = {
    shield: Object.assign(shield, shield),
    customers: Object.assign(customers, customers),
    estimatePackages: Object.assign(estimatePackages, estimatePackages),
    estimates: Object.assign(estimates, estimates),
    items: Object.assign(items, items),
}

export default resources