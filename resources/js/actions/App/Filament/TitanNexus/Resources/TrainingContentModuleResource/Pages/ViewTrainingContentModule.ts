import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\ViewTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/ViewTrainingContentModule.php:7
* @route '/titannexus/training-content/{record}'
*/
const ViewTrainingContentModule = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewTrainingContentModule.url(args, options),
    method: 'get',
})

ViewTrainingContentModule.definition = {
    methods: ["get","head"],
    url: '/titannexus/training-content/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\ViewTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/ViewTrainingContentModule.php:7
* @route '/titannexus/training-content/{record}'
*/
ViewTrainingContentModule.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ViewTrainingContentModule.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\ViewTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/ViewTrainingContentModule.php:7
* @route '/titannexus/training-content/{record}'
*/
ViewTrainingContentModule.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewTrainingContentModule.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\ViewTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/ViewTrainingContentModule.php:7
* @route '/titannexus/training-content/{record}'
*/
ViewTrainingContentModule.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewTrainingContentModule.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\ViewTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/ViewTrainingContentModule.php:7
* @route '/titannexus/training-content/{record}'
*/
const ViewTrainingContentModuleForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewTrainingContentModule.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\ViewTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/ViewTrainingContentModule.php:7
* @route '/titannexus/training-content/{record}'
*/
ViewTrainingContentModuleForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewTrainingContentModule.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\ViewTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/ViewTrainingContentModule.php:7
* @route '/titannexus/training-content/{record}'
*/
ViewTrainingContentModuleForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewTrainingContentModule.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ViewTrainingContentModule.form = ViewTrainingContentModuleForm

export default ViewTrainingContentModule