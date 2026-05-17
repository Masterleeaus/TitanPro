import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ViewPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ViewPost.php:7
* @route '/titanpro/posts/{record}/show'
*/
const ViewPost = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewPost.url(args, options),
    method: 'get',
})

ViewPost.definition = {
    methods: ["get","head"],
    url: '/titanpro/posts/{record}/show',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ViewPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ViewPost.php:7
* @route '/titanpro/posts/{record}/show'
*/
ViewPost.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { record: args }
    }

    if (Array.isArray(args)) {
        args = {
            record: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        record: args.record,
    }

    return ViewPost.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ViewPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ViewPost.php:7
* @route '/titanpro/posts/{record}/show'
*/
ViewPost.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewPost.url(args, options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ViewPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ViewPost.php:7
* @route '/titanpro/posts/{record}/show'
*/
ViewPost.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewPost.url(args, options),
    method: 'head',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ViewPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ViewPost.php:7
* @route '/titanpro/posts/{record}/show'
*/
const ViewPostForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewPost.url(args, options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ViewPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ViewPost.php:7
* @route '/titanpro/posts/{record}/show'
*/
ViewPostForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewPost.url(args, options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ViewPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ViewPost.php:7
* @route '/titanpro/posts/{record}/show'
*/
ViewPostForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewPost.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ViewPost.form = ViewPostForm

export default ViewPost