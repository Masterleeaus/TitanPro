import MessageTemplateResource from './MessageTemplateResource'
import CmsPageResource from './CmsPageResource'
import JobTypeChecklistItemResource from './JobTypeChecklistItemResource'
import JobChecklistItemResource from './JobChecklistItemResource'

const Resources = {
    MessageTemplateResource: Object.assign(MessageTemplateResource, MessageTemplateResource),
    CmsPageResource: Object.assign(CmsPageResource, CmsPageResource),
    JobTypeChecklistItemResource: Object.assign(JobTypeChecklistItemResource, JobTypeChecklistItemResource),
    JobChecklistItemResource: Object.assign(JobChecklistItemResource, JobChecklistItemResource),
}

export default Resources