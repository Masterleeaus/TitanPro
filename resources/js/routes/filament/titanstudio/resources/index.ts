import shield from './shield'
import messageTemplates from './message-templates'
import cmsPages from './cms-pages'
import jobTypeChecklistItems from './job-type-checklist-items'
import jobChecklistItems from './job-checklist-items'

const resources = {
    shield: Object.assign(shield, shield),
    messageTemplates: Object.assign(messageTemplates, messageTemplates),
    cmsPages: Object.assign(cmsPages, cmsPages),
    jobTypeChecklistItems: Object.assign(jobTypeChecklistItems, jobTypeChecklistItems),
    jobChecklistItems: Object.assign(jobChecklistItems, jobChecklistItems),
}

export default resources