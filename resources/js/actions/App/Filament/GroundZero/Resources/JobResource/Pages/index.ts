import ListJobs from './ListJobs'
import CreateJob from './CreateJob'
import ViewJob from './ViewJob'
import EditJob from './EditJob'

const Pages = {
    ListJobs: Object.assign(ListJobs, ListJobs),
    CreateJob: Object.assign(CreateJob, CreateJob),
    ViewJob: Object.assign(ViewJob, ViewJob),
    EditJob: Object.assign(EditJob, EditJob),
}

export default Pages