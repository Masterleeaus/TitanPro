import ListJobs from './ListJobs'
import CreateJob from './CreateJob'
import EditJob from './EditJob'

const Pages = {
    ListJobs: Object.assign(ListJobs, ListJobs),
    CreateJob: Object.assign(CreateJob, CreateJob),
    EditJob: Object.assign(EditJob, EditJob),
}

export default Pages