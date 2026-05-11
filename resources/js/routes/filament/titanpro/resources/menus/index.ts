import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\ListMenus::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/ListMenus.php:7
* @route '/titanpro/menus'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/titanpro/menus',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\ListMenus::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/ListMenus.php:7
* @route '/titanpro/menus'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\ListMenus::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/ListMenus.php:7
* @route '/titanpro/menus'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\ListMenus::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/ListMenus.php:7
* @route '/titanpro/menus'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\ListMenus::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/ListMenus.php:7
* @route '/titanpro/menus'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\ListMenus::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/ListMenus.php:7
* @route '/titanpro/menus'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\ListMenus::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/ListMenus.php:7
* @route '/titanpro/menus'
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
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\CreateMenu::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/CreateMenu.php:7
* @route '/titanpro/menus/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/titanpro/menus/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\CreateMenu::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/CreateMenu.php:7
* @route '/titanpro/menus/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\CreateMenu::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/CreateMenu.php:7
* @route '/titanpro/menus/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\CreateMenu::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/CreateMenu.php:7
* @route '/titanpro/menus/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\CreateMenu::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/CreateMenu.php:7
* @route '/titanpro/menus/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\CreateMenu::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/CreateMenu.php:7
* @route '/titanpro/menus/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\CreateMenu::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/CreateMenu.php:7
* @route '/titanpro/menus/create'
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
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\EditMenu::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/EditMenu.php:7
* @route '/titanpro/menus/{record}/edit'
*/
export const edit = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/titanpro/menus/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\EditMenu::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/EditMenu.php:7
* @route '/titanpro/menus/{record}/edit'
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
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\EditMenu::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/EditMenu.php:7
* @route '/titanpro/menus/{record}/edit'
*/
edit.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\EditMenu::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/EditMenu.php:7
* @route '/titanpro/menus/{record}/edit'
*/
edit.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\EditMenu::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/EditMenu.php:7
* @route '/titanpro/menus/{record}/edit'
*/
const editForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\EditMenu::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/EditMenu.php:7
* @route '/titanpro/menus/{record}/edit'
*/
editForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\EditMenu::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/EditMenu.php:7
* @route '/titanpro/menus/{record}/edit'
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
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\MenuBuilder::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/MenuBuilder.php:7
* @route '/titanpro/menus/{record}/build'
*/
export const build = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: build.url(args, options),
    method: 'get',
})

build.definition = {
    methods: ["get","head"],
    url: '/titanpro/menus/{record}/build',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\MenuBuilder::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/MenuBuilder.php:7
* @route '/titanpro/menus/{record}/build'
*/
build.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return build.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\MenuBuilder::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/MenuBuilder.php:7
* @route '/titanpro/menus/{record}/build'
*/
build.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: build.url(args, options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\MenuBuilder::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/MenuBuilder.php:7
* @route '/titanpro/menus/{record}/build'
*/
build.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: build.url(args, options),
    method: 'head',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\MenuBuilder::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/MenuBuilder.php:7
* @route '/titanpro/menus/{record}/build'
*/
const buildForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: build.url(args, options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\MenuBuilder::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/MenuBuilder.php:7
* @route '/titanpro/menus/{record}/build'
*/
buildForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: build.url(args, options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\MenuBuilder::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/MenuBuilder.php:7
* @route '/titanpro/menus/{record}/build'
*/
buildForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: build.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

build.form = buildForm

const menus = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    edit: Object.assign(edit, edit),
    build: Object.assign(build, build),
}

export default menus