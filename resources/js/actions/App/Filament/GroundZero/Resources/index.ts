import CustomerResource from './CustomerResource'
import EstimateResource from './EstimateResource'
import InvoiceResource from './InvoiceResource'
import JobResource from './JobResource'
import PropertyResource from './PropertyResource'
import TeamResource from './TeamResource'

const Resources = {
    CustomerResource: Object.assign(CustomerResource, CustomerResource),
    EstimateResource: Object.assign(EstimateResource, EstimateResource),
    InvoiceResource: Object.assign(InvoiceResource, InvoiceResource),
    JobResource: Object.assign(JobResource, JobResource),
    PropertyResource: Object.assign(PropertyResource, PropertyResource),
    TeamResource: Object.assign(TeamResource, TeamResource),
}

export default Resources