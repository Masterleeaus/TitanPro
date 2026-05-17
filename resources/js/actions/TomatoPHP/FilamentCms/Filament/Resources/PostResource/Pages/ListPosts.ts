import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ListPosts::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ListPosts.php:7
* @route '/titanpro/posts'
*/
const ListPosts = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListPosts.url(options),
    method: 'get',
})

ListPosts.definition = {
    methods: ["get","head"],
    url: '/titanpro/posts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ListPosts::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ListPosts.php:7
* @route '/titanpro/posts'
*/
ListPosts.url = (options?: RouteQueryOptions) => {
    return ListPosts.definition.url + queryParams(options)
}

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ListPosts::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ListPosts.php:7
* @route '/titanpro/posts'
*/
ListPosts.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListPosts.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ListPosts::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ListPosts.php:7
* @route '/titanpro/posts'
*/
ListPosts.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListPosts.url(options),
    method: 'head',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ListPosts::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ListPosts.php:7
* @route '/titanpro/posts'
*/
const ListPostsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListPosts.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ListPosts::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ListPosts.php:7
* @route '/titanpro/posts'
*/
ListPostsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListPosts.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ListPosts::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ListPosts.php:7
* @route '/titanpro/posts'
*/
ListPostsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListPosts.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListPosts.form = ListPostsForm

export default ListPosts