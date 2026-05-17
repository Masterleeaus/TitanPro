import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ListVerticalPacks::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ListVerticalPacks.php:7
* @route '/titannexus/verticals'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/titannexus/verticals',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ListVerticalPacks::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ListVerticalPacks.php:7
* @route '/titannexus/verticals'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ListVerticalPacks::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ListVerticalPacks.php:7
* @route '/titannexus/verticals'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ListVerticalPacks::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ListVerticalPacks.php:7
* @route '/titannexus/verticals'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ListVerticalPacks::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ListVerticalPacks.php:7
* @route '/titannexus/verticals'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ListVerticalPacks::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ListVerticalPacks.php:7
* @route '/titannexus/verticals'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ListVerticalPacks::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ListVerticalPacks.php:7
* @route '/titannexus/verticals'
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
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\CreateVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/CreateVerticalPack.php:7
* @route '/titannexus/verticals/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/titannexus/verticals/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\CreateVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/CreateVerticalPack.php:7
* @route '/titannexus/verticals/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\CreateVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/CreateVerticalPack.php:7
* @route '/titannexus/verticals/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\CreateVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/CreateVerticalPack.php:7
* @route '/titannexus/verticals/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\CreateVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/CreateVerticalPack.php:7
* @route '/titannexus/verticals/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\CreateVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/CreateVerticalPack.php:7
* @route '/titannexus/verticals/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\CreateVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/CreateVerticalPack.php:7
* @route '/titannexus/verticals/create'
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
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ViewVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ViewVerticalPack.php:7
* @route '/titannexus/verticals/{record}'
*/
export const view = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: view.url(args, options),
    method: 'get',
})

view.definition = {
    methods: ["get","head"],
    url: '/titannexus/verticals/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ViewVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ViewVerticalPack.php:7
* @route '/titannexus/verticals/{record}'
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
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ViewVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ViewVerticalPack.php:7
* @route '/titannexus/verticals/{record}'
*/
view.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: view.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ViewVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ViewVerticalPack.php:7
* @route '/titannexus/verticals/{record}'
*/
view.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: view.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ViewVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ViewVerticalPack.php:7
* @route '/titannexus/verticals/{record}'
*/
const viewForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: view.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ViewVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ViewVerticalPack.php:7
* @route '/titannexus/verticals/{record}'
*/
viewForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: view.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ViewVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ViewVerticalPack.php:7
* @route '/titannexus/verticals/{record}'
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
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\EditVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/EditVerticalPack.php:7
* @route '/titannexus/verticals/{record}/edit'
*/
export const edit = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/titannexus/verticals/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\EditVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/EditVerticalPack.php:7
* @route '/titannexus/verticals/{record}/edit'
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
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\EditVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/EditVerticalPack.php:7
* @route '/titannexus/verticals/{record}/edit'
*/
edit.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\EditVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/EditVerticalPack.php:7
* @route '/titannexus/verticals/{record}/edit'
*/
edit.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\EditVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/EditVerticalPack.php:7
* @route '/titannexus/verticals/{record}/edit'
*/
const editForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\EditVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/EditVerticalPack.php:7
* @route '/titannexus/verticals/{record}/edit'
*/
editForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\EditVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/EditVerticalPack.php:7
* @route '/titannexus/verticals/{record}/edit'
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

const verticals = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    view: Object.assign(view, view),
    edit: Object.assign(edit, edit),
}

export default verticals