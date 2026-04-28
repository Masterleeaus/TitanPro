import ListDriverLocations from './ListDriverLocations'
import CreateDriverLocation from './CreateDriverLocation'
import EditDriverLocation from './EditDriverLocation'

const Pages = {
    ListDriverLocations: Object.assign(ListDriverLocations, ListDriverLocations),
    CreateDriverLocation: Object.assign(CreateDriverLocation, CreateDriverLocation),
    EditDriverLocation: Object.assign(EditDriverLocation, EditDriverLocation),
}

export default Pages