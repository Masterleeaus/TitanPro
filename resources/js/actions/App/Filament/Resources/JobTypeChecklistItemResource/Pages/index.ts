import ListJobTypeChecklistItems from './ListJobTypeChecklistItems'
import CreateJobTypeChecklistItem from './CreateJobTypeChecklistItem'
import EditJobTypeChecklistItem from './EditJobTypeChecklistItem'

const Pages = {
    ListJobTypeChecklistItems: Object.assign(ListJobTypeChecklistItems, ListJobTypeChecklistItems),
    CreateJobTypeChecklistItem: Object.assign(CreateJobTypeChecklistItem, CreateJobTypeChecklistItem),
    EditJobTypeChecklistItem: Object.assign(EditJobTypeChecklistItem, EditJobTypeChecklistItem),
}

export default Pages