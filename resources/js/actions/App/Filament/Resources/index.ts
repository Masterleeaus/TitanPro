import AttachmentResource from './AttachmentResource'
import CmsPageResource from './CmsPageResource'
import CustomerResource from './CustomerResource'
import DriverLocationResource from './DriverLocationResource'
import EstimatePackageResource from './EstimatePackageResource'
import EstimateResource from './EstimateResource'
import InvoiceResource from './InvoiceResource'
import ItemResource from './ItemResource'
import JobChecklistItemResource from './JobChecklistItemResource'
import JobMessageResource from './JobMessageResource'
import JobResource from './JobResource'
import JobTypeChecklistItemResource from './JobTypeChecklistItemResource'
import JobTypeResource from './JobTypeResource'
import MessageTemplateResource from './MessageTemplateResource'
import OrganizationSettingResource from './OrganizationSettingResource'
import PaymentResource from './PaymentResource'
import PropertyResource from './PropertyResource'

const Resources = {
    AttachmentResource: Object.assign(AttachmentResource, AttachmentResource),
    CmsPageResource: Object.assign(CmsPageResource, CmsPageResource),
    CustomerResource: Object.assign(CustomerResource, CustomerResource),
    DriverLocationResource: Object.assign(DriverLocationResource, DriverLocationResource),
    EstimatePackageResource: Object.assign(EstimatePackageResource, EstimatePackageResource),
    EstimateResource: Object.assign(EstimateResource, EstimateResource),
    InvoiceResource: Object.assign(InvoiceResource, InvoiceResource),
    ItemResource: Object.assign(ItemResource, ItemResource),
    JobChecklistItemResource: Object.assign(JobChecklistItemResource, JobChecklistItemResource),
    JobMessageResource: Object.assign(JobMessageResource, JobMessageResource),
    JobResource: Object.assign(JobResource, JobResource),
    JobTypeChecklistItemResource: Object.assign(JobTypeChecklistItemResource, JobTypeChecklistItemResource),
    JobTypeResource: Object.assign(JobTypeResource, JobTypeResource),
    MessageTemplateResource: Object.assign(MessageTemplateResource, MessageTemplateResource),
    OrganizationSettingResource: Object.assign(OrganizationSettingResource, OrganizationSettingResource),
    PaymentResource: Object.assign(PaymentResource, PaymentResource),
    PropertyResource: Object.assign(PropertyResource, PropertyResource),
}

export default Resources