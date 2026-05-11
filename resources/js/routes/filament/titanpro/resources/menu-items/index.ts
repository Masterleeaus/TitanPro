import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/ListMenuItems.php:7
* @route '/titanpro/menu-items'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/titanpro/menu-items',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/ListMenuItems.php:7
* @route '/titanpro/menu-items'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/ListMenuItems.php:7
* @route '/titanpro/menu-items'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/ListMenuItems.php:7
* @route '/titanpro/menu-items'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/ListMenuItems.php:7
* @route '/titanpro/menu-items'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/ListMenuItems.php:7
* @route '/titanpro/menu-items'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/ListMenuItems.php:7
* @route '/titanpro/menu-items'
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
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/CreateMenuItem.php:7
* @route '/titanpro/menu-items/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/titanpro/menu-items/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/CreateMenuItem.php:7
* @route '/titanpro/menu-items/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/CreateMenuItem.php:7
* @route '/titanpro/menu-items/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/CreateMenuItem.php:7
* @route '/titanpro/menu-items/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/CreateMenuItem.php:7
* @route '/titanpro/menu-items/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/CreateMenuItem.php:7
* @route '/titanpro/menu-items/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/CreateMenuItem.php:7
* @route '/titanpro/menu-items/create'
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
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/EditMenuItem.php:7
* @route '/titanpro/menu-items/{record}/edit'
*/
export const edit = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/titanpro/menu-items/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/EditMenuItem.php:7
* @route '/titanpro/menu-items/{record}/edit'
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
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/EditMenuItem.php:7
* @route '/titanpro/menu-items/{record}/edit'
*/
edit.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/EditMenuItem.php:7
* @route '/titanpro/menu-items/{record}/edit'
*/
edit.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/EditMenuItem.php:7
* @route '/titanpro/menu-items/{record}/edit'
*/
const editForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/EditMenuItem.php:7
* @route '/titanpro/menu-items/{record}/edit'
*/
editForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/EditMenuItem.php:7
* @route '/titanpro/menu-items/{record}/edit'
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

const menuItems = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    edit: Object.assign(edit, edit),
}

export default menuItems