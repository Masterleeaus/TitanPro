import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\ListModules::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/ListModules.php:7
* @route '/titanpro/modules'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/titanpro/modules',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\ListModules::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/ListModules.php:7
* @route '/titanpro/modules'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\ListModules::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/ListModules.php:7
* @route '/titanpro/modules'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\ListModules::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/ListModules.php:7
* @route '/titanpro/modules'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\ListModules::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/ListModules.php:7
* @route '/titanpro/modules'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\ListModules::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/ListModules.php:7
* @route '/titanpro/modules'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\ListModules::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/ListModules.php:7
* @route '/titanpro/modules'
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
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\EditModule::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/EditModule.php:7
* @route '/titanpro/modules/{record}/edit'
*/
export const edit = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/titanpro/modules/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\EditModule::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/EditModule.php:7
* @route '/titanpro/modules/{record}/edit'
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
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\EditModule::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/EditModule.php:7
* @route '/titanpro/modules/{record}/edit'
*/
edit.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\EditModule::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/EditModule.php:7
* @route '/titanpro/modules/{record}/edit'
*/
edit.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\EditModule::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/EditModule.php:7
* @route '/titanpro/modules/{record}/edit'
*/
const editForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\EditModule::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/EditModule.php:7
* @route '/titanpro/modules/{record}/edit'
*/
editForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\EditModule::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/EditModule.php:7
* @route '/titanpro/modules/{record}/edit'
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

const modules = {
    index: Object.assign(index, index),
    edit: Object.assign(edit, edit),
}

export default modules