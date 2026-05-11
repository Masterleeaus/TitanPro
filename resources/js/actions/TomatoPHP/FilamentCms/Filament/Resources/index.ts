import PostResource from './PostResource'
import CategoryResource from './CategoryResource'

const Resources = {
    PostResource: Object.assign(PostResource, PostResource),
    CategoryResource: Object.assign(CategoryResource, CategoryResource),
}

export default Resources