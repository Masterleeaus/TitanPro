import ListMenus from './ListMenus'
import CreateMenu from './CreateMenu'
import EditMenu from './EditMenu'
import MenuBuilder from './MenuBuilder'

const Pages = {
    ListMenus: Object.assign(ListMenus, ListMenus),
    CreateMenu: Object.assign(CreateMenu, CreateMenu),
    EditMenu: Object.assign(EditMenu, EditMenu),
    MenuBuilder: Object.assign(MenuBuilder, MenuBuilder),
}

export default Pages