import Http from './Http'
import Resources from './Resources'

const Curator = {
    Http: Object.assign(Http, Http),
    Resources: Object.assign(Resources, Resources),
}

export default Curator