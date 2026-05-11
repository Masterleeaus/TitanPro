import MenuResource from './MenuResource'
import MenuItemResource from './MenuItemResource'

const Resources = {
    MenuResource: Object.assign(MenuResource, MenuResource),
    MenuItemResource: Object.assign(MenuItemResource, MenuItemResource),
}

export default Resources