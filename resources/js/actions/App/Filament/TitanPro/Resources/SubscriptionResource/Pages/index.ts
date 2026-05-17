import ListSubscriptions from './ListSubscriptions'
import CreateSubscription from './CreateSubscription'
import EditSubscription from './EditSubscription'

const Pages = {
    ListSubscriptions: Object.assign(ListSubscriptions, ListSubscriptions),
    CreateSubscription: Object.assign(CreateSubscription, CreateSubscription),
    EditSubscription: Object.assign(EditSubscription, EditSubscription),
}

export default Pages