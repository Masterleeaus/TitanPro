import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ListPosts::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ListPosts.php:7
* @route '/titanpro/posts'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/titanpro/posts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ListPosts::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ListPosts.php:7
* @route '/titanpro/posts'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ListPosts::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ListPosts.php:7
* @route '/titanpro/posts'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ListPosts::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ListPosts.php:7
* @route '/titanpro/posts'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ListPosts::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ListPosts.php:7
* @route '/titanpro/posts'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ListPosts::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ListPosts.php:7
* @route '/titanpro/posts'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ListPosts::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ListPosts.php:7
* @route '/titanpro/posts'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\CreatePost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/CreatePost.php:7
* @route '/titanpro/posts/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/titanpro/posts/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\CreatePost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/CreatePost.php:7
* @route '/titanpro/posts/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\CreatePost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/CreatePost.php:7
* @route '/titanpro/posts/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\CreatePost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/CreatePost.php:7
* @route '/titanpro/posts/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\CreatePost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/CreatePost.php:7
* @route '/titanpro/posts/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\CreatePost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/CreatePost.php:7
* @route '/titanpro/posts/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\CreatePost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/CreatePost.php:7
* @route '/titanpro/posts/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ViewPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ViewPost.php:7
* @route '/titanpro/posts/{record}/show'
*/
export const view = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: view.url(args, options),
    method: 'get',
})

view.definition = {
    methods: ["get","head"],
    url: '/titanpro/posts/{record}/show',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ViewPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ViewPost.php:7
* @route '/titanpro/posts/{record}/show'
*/
view.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return view.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ViewPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ViewPost.php:7
* @route '/titanpro/posts/{record}/show'
*/
view.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: view.url(args, options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ViewPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ViewPost.php:7
* @route '/titanpro/posts/{record}/show'
*/
view.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: view.url(args, options),
    method: 'head',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ViewPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ViewPost.php:7
* @route '/titanpro/posts/{record}/show'
*/
const viewForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: view.url(args, options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ViewPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ViewPost.php:7
* @route '/titanpro/posts/{record}/show'
*/
viewForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: view.url(args, options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\ViewPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/ViewPost.php:7
* @route '/titanpro/posts/{record}/show'
*/
viewForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: view.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

view.form = viewForm

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\EditPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/EditPost.php:7
* @route '/titanpro/posts/{record}/edit'
*/
export const edit = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/titanpro/posts/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\EditPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/EditPost.php:7
* @route '/titanpro/posts/{record}/edit'
*/
edit.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\EditPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/EditPost.php:7
* @route '/titanpro/posts/{record}/edit'
*/
edit.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\EditPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/EditPost.php:7
* @route '/titanpro/posts/{record}/edit'
*/
edit.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\EditPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/EditPost.php:7
* @route '/titanpro/posts/{record}/edit'
*/
const editForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\EditPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/EditPost.php:7
* @route '/titanpro/posts/{record}/edit'
*/
editForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\PostResource\Pages\EditPost::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/PostResource/Pages/EditPost.php:7
* @route '/titanpro/posts/{record}/edit'
*/
editForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

const posts = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    view: Object.assign(view, view),
    edit: Object.assign(edit, edit),
}

export default posts