import auth from './auth'
import pages from './pages'
import resources from './resources'
import passkeys from './passkeys'

const titango = {
    auth: Object.assign(auth, auth),
    pages: Object.assign(pages, pages),
    resources: Object.assign(resources, resources),
    passkeys: Object.assign(passkeys, passkeys),
}

export default titango