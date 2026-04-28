import ListCmsPages from './ListCmsPages'
import CreateCmsPage from './CreateCmsPage'
import EditCmsPage from './EditCmsPage'

const Pages = {
    ListCmsPages: Object.assign(ListCmsPages, ListCmsPages),
    CreateCmsPage: Object.assign(CreateCmsPage, CreateCmsPage),
    EditCmsPage: Object.assign(EditCmsPage, EditCmsPage),
}

export default Pages