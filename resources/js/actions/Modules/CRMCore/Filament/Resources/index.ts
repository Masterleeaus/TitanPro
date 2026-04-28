import ClientPipelineResource from './ClientPipelineResource'
import LeadScoringResource from './LeadScoringResource'
import DealProjectResource from './DealProjectResource'
import CRMCoreActivityLogResource from './CRMCoreActivityLogResource'

const Resources = {
    ClientPipelineResource: Object.assign(ClientPipelineResource, ClientPipelineResource),
    LeadScoringResource: Object.assign(LeadScoringResource, LeadScoringResource),
    DealProjectResource: Object.assign(DealProjectResource, DealProjectResource),
    CRMCoreActivityLogResource: Object.assign(CRMCoreActivityLogResource, CRMCoreActivityLogResource),
}

export default Resources