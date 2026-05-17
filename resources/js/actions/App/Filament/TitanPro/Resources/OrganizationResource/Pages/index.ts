import ListOrganizations from './ListOrganizations'
import CreateOrganization from './CreateOrganization'
import EditOrganization from './EditOrganization'

const Pages = {
    ListOrganizations: Object.assign(ListOrganizations, ListOrganizations),
    CreateOrganization: Object.assign(CreateOrganization, CreateOrganization),
    EditOrganization: Object.assign(EditOrganization, EditOrganization),
}

export default Pages