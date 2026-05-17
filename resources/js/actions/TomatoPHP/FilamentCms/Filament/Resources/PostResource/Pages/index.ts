import ListPosts from './ListPosts'
import CreatePost from './CreatePost'
import ViewPost from './ViewPost'
import EditPost from './EditPost'

const Pages = {
    ListPosts: Object.assign(ListPosts, ListPosts),
    CreatePost: Object.assign(CreatePost, CreatePost),
    ViewPost: Object.assign(ViewPost, ViewPost),
    EditPost: Object.assign(EditPost, EditPost),
}

export default Pages