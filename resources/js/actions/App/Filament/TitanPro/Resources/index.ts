import ModuleResource from './ModuleResource'
import OrganizationResource from './OrganizationResource'
import SubscriptionResource from './SubscriptionResource'
import UserResource from './UserResource'

const Resources = {
    ModuleResource: Object.assign(ModuleResource, ModuleResource),
    OrganizationResource: Object.assign(OrganizationResource, OrganizationResource),
    SubscriptionResource: Object.assign(SubscriptionResource, SubscriptionResource),
    UserResource: Object.assign(UserResource, UserResource),
}

export default Resources