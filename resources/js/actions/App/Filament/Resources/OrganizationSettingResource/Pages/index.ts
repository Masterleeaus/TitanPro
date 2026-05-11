import ListOrganizationSettings from './ListOrganizationSettings'
import CreateOrganizationSetting from './CreateOrganizationSetting'
import EditOrganizationSetting from './EditOrganizationSetting'

const Pages = {
    ListOrganizationSettings: Object.assign(ListOrganizationSettings, ListOrganizationSettings),
    CreateOrganizationSetting: Object.assign(CreateOrganizationSetting, CreateOrganizationSetting),
    EditOrganizationSetting: Object.assign(EditOrganizationSetting, EditOrganizationSetting),
}

export default Pages