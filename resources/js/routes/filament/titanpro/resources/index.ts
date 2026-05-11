import shield from './shield'
import clientPipelines from './client-pipelines'
import leadScorings from './lead-scorings'
import dealProjects from './deal-projects'
import cRMCoreActivityLogs from './c-r-m-core-activity-logs'
import media from './media'
import layouts from './layouts'
import menus from './menus'
import menuItems from './menu-items'
import posts from './posts'
import categories from './categories'
import modules from './modules'
import organizations from './organizations'
import subscriptions from './subscriptions'
import users from './users'

const resources = {
    shield: Object.assign(shield, shield),
    clientPipelines: Object.assign(clientPipelines, clientPipelines),
    leadScorings: Object.assign(leadScorings, leadScorings),
    dealProjects: Object.assign(dealProjects, dealProjects),
    cRMCoreActivityLogs: Object.assign(cRMCoreActivityLogs, cRMCoreActivityLogs),
    media: Object.assign(media, media),
    layouts: Object.assign(layouts, layouts),
    menus: Object.assign(menus, menus),
    menuItems: Object.assign(menuItems, menuItems),
    posts: Object.assign(posts, posts),
    categories: Object.assign(categories, categories),
    modules: Object.assign(modules, modules),
    organizations: Object.assign(organizations, organizations),
    subscriptions: Object.assign(subscriptions, subscriptions),
    users: Object.assign(users, users),
}

export default resources