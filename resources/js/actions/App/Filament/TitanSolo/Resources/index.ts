import CustomerResource from './CustomerResource'
import InvoiceResource from './InvoiceResource'
import JobResource from './JobResource'

const Resources = {
    CustomerResource: Object.assign(CustomerResource, CustomerResource),
    InvoiceResource: Object.assign(InvoiceResource, InvoiceResource),
    JobResource: Object.assign(JobResource, JobResource),
}

export default Resources