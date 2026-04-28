import ListEstimatePackages from './ListEstimatePackages'
import CreateEstimatePackage from './CreateEstimatePackage'
import EditEstimatePackage from './EditEstimatePackage'

const Pages = {
    ListEstimatePackages: Object.assign(ListEstimatePackages, ListEstimatePackages),
    CreateEstimatePackage: Object.assign(CreateEstimatePackage, CreateEstimatePackage),
    EditEstimatePackage: Object.assign(EditEstimatePackage, EditEstimatePackage),
}

export default Pages