import ListJobMessages from './ListJobMessages'
import CreateJobMessage from './CreateJobMessage'
import EditJobMessage from './EditJobMessage'

const Pages = {
    ListJobMessages: Object.assign(ListJobMessages, ListJobMessages),
    CreateJobMessage: Object.assign(CreateJobMessage, CreateJobMessage),
    EditJobMessage: Object.assign(EditJobMessage, EditJobMessage),
}

export default Pages