import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titansolo/layouts'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/titansolo/layouts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titansolo/layouts'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titansolo/layouts'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titansolo/layouts'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titansolo/layouts'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titansolo/layouts'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titansolo/layouts'
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
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titansolo/layouts/{record}/edit'
*/
export const edit = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/titansolo/layouts/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titansolo/layouts/{record}/edit'
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
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titansolo/layouts/{record}/edit'
*/
edit.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titansolo/layouts/{record}/edit'
*/
edit.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titansolo/layouts/{record}/edit'
*/
const editForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titansolo/layouts/{record}/edit'
*/
editForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titansolo/layouts/{record}/edit'
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

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titansolo/layouts/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/titansolo/layouts/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titansolo/layouts/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titansolo/layouts/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titansolo/layouts/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titansolo/layouts/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titansolo/layouts/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titansolo/layouts/create'
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

const layouts = {
    index: Object.assign(index, index),
    edit: Object.assign(edit, edit),
    create: Object.assign(create, create),
}

export default layouts