import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\CreatePost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/CreatePost.php:7
* @route '/titanpro/posts/create'
*/
const CreatePost = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreatePost.url(options),
    method: 'get',
})

CreatePost.definition = {
    methods: ["get","head"],
    url: '/titanpro/posts/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\CreatePost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/CreatePost.php:7
* @route '/titanpro/posts/create'
*/
CreatePost.url = (options?: RouteQueryOptions) => {
    return CreatePost.definition.url + queryParams(options)
}

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\CreatePost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/CreatePost.php:7
* @route '/titanpro/posts/create'
*/
CreatePost.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreatePost.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\CreatePost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/CreatePost.php:7
* @route '/titanpro/posts/create'
*/
CreatePost.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreatePost.url(options),
    method: 'head',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\CreatePost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/CreatePost.php:7
* @route '/titanpro/posts/create'
*/
const CreatePostForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreatePost.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\CreatePost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/CreatePost.php:7
* @route '/titanpro/posts/create'
*/
CreatePostForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreatePost.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\CreatePost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/CreatePost.php:7
* @route '/titanpro/posts/create'
*/
CreatePostForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreatePost.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreatePost.form = CreatePostForm

export default CreatePost