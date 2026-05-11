import ListMenuItems from './ListMenuItems'
import CreateMenuItem from './CreateMenuItem'
import EditMenuItem from './EditMenuItem'

const Pages = {
    ListMenuItems: Object.assign(ListMenuItems, ListMenuItems),
    CreateMenuItem: Object.assign(CreateMenuItem, CreateMenuItem),
    EditMenuItem: Object.assign(EditMenuItem, EditMenuItem),
}

export default Pages