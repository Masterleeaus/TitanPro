import ListLayout from './ListLayout'
import EditLayout from './EditLayout'
import CreateLayout from './CreateLayout'

const Pages = {
    ListLayout: Object.assign(ListLayout, ListLayout),
    EditLayout: Object.assign(EditLayout, EditLayout),
    CreateLayout: Object.assign(CreateLayout, CreateLayout),
}

export default Pages