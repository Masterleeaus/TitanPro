import ListJobChecklistItems from './ListJobChecklistItems'
import CreateJobChecklistItem from './CreateJobChecklistItem'
import EditJobChecklistItem from './EditJobChecklistItem'

const Pages = {
    ListJobChecklistItems: Object.assign(ListJobChecklistItems, ListJobChecklistItems),
    CreateJobChecklistItem: Object.assign(CreateJobChecklistItem, CreateJobChecklistItem),
    EditJobChecklistItem: Object.assign(EditJobChecklistItem, EditJobChecklistItem),
}

export default Pages