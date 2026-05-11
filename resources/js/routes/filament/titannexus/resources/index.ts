import shield from './shield'
import layouts from './layouts'
import leadPipeline from './lead-pipeline'
import marketingCampaigns from './marketing-campaigns'
import trainingContent from './training-content'
import verticals from './verticals'

const resources = {
    shield: Object.assign(shield, shield),
    layouts: Object.assign(layouts, layouts),
    leadPipeline: Object.assign(leadPipeline, leadPipeline),
    marketingCampaigns: Object.assign(marketingCampaigns, marketingCampaigns),
    trainingContent: Object.assign(trainingContent, trainingContent),
    verticals: Object.assign(verticals, verticals),
}

export default resources