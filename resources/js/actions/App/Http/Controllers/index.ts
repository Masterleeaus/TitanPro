import Auth from './Auth'
import Platform from './Platform'
import Owner from './Owner'
import PublicEstimateController from './PublicEstimateController'
import Technician from './Technician'
import HealthController from './HealthController'
import StripeWebhookController from './StripeWebhookController'
import CmsPageController from './CmsPageController'
import EsoftTemplateController from './EsoftTemplateController'
import Settings from './Settings'

const Controllers = {
    Auth: Object.assign(Auth, Auth),
    Platform: Object.assign(Platform, Platform),
    Owner: Object.assign(Owner, Owner),
    PublicEstimateController: Object.assign(PublicEstimateController, PublicEstimateController),
    Technician: Object.assign(Technician, Technician),
    HealthController: Object.assign(HealthController, HealthController),
    StripeWebhookController: Object.assign(StripeWebhookController, StripeWebhookController),
    CmsPageController: Object.assign(CmsPageController, CmsPageController),
    EsoftTemplateController: Object.assign(EsoftTemplateController, EsoftTemplateController),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers