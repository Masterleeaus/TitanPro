import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\EditModule::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/EditModule.php:7
* @route '/titanpro/modules/{record}/edit'
*/
const EditModule = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditModule.url(args, options),
    method: 'get',
})

EditModule.definition = {
    methods: ["get","head"],
    url: '/titanpro/modules/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\EditModule::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/EditModule.php:7
* @route '/titanpro/modules/{record}/edit'
*/
EditModule.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditModule.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\EditModule::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/EditModule.php:7
* @route '/titanpro/modules/{record}/edit'
*/
EditModule.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditModule.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\EditModule::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/EditModule.php:7
* @route '/titanpro/modules/{record}/edit'
*/
EditModule.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditModule.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\EditModule::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/EditModule.php:7
* @route '/titanpro/modules/{record}/edit'
*/
const EditModuleForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditModule.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\EditModule::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/EditModule.php:7
* @route '/titanpro/modules/{record}/edit'
*/
EditModuleForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditModule.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\EditModule::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/EditModule.php:7
* @route '/titanpro/modules/{record}/edit'
*/
EditModuleForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditModule.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditModule.form = EditModuleForm

export default EditModule