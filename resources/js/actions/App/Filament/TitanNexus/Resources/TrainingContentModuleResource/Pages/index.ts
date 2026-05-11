import ListTrainingContentModules from './ListTrainingContentModules'
import CreateTrainingContentModule from './CreateTrainingContentModule'
import ViewTrainingContentModule from './ViewTrainingContentModule'
import EditTrainingContentModule from './EditTrainingContentModule'

const Pages = {
    ListTrainingContentModules: Object.assign(ListTrainingContentModules, ListTrainingContentModules),
    CreateTrainingContentModule: Object.assign(CreateTrainingContentModule, CreateTrainingContentModule),
    ViewTrainingContentModule: Object.assign(ViewTrainingContentModule, ViewTrainingContentModule),
    EditTrainingContentModule: Object.assign(EditTrainingContentModule, EditTrainingContentModule),
}

export default Pages