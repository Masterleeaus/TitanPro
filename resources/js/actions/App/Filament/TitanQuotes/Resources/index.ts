import CustomerResource from './CustomerResource'
import EstimatePackageResource from './EstimatePackageResource'
import EstimateResource from './EstimateResource'
import ItemResource from './ItemResource'

const Resources = {
    CustomerResource: Object.assign(CustomerResource, CustomerResource),
    EstimatePackageResource: Object.assign(EstimatePackageResource, EstimatePackageResource),
    EstimateResource: Object.assign(EstimateResource, EstimateResource),
    ItemResource: Object.assign(ItemResource, ItemResource),
}

export default Resources