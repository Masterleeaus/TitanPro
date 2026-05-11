import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\EditTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/EditTrainingContentModule.php:7
* @route '/titannexus/training-content/{record}/edit'
*/
const EditTrainingContentModule = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditTrainingContentModule.url(args, options),
    method: 'get',
})

EditTrainingContentModule.definition = {
    methods: ["get","head"],
    url: '/titannexus/training-content/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\EditTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/EditTrainingContentModule.php:7
* @route '/titannexus/training-content/{record}/edit'
*/
EditTrainingContentModule.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditTrainingContentModule.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\EditTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/EditTrainingContentModule.php:7
* @route '/titannexus/training-content/{record}/edit'
*/
EditTrainingContentModule.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditTrainingContentModule.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\EditTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/EditTrainingContentModule.php:7
* @route '/titannexus/training-content/{record}/edit'
*/
EditTrainingContentModule.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditTrainingContentModule.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\EditTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/EditTrainingContentModule.php:7
* @route '/titannexus/training-content/{record}/edit'
*/
const EditTrainingContentModuleForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditTrainingContentModule.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\EditTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/EditTrainingContentModule.php:7
* @route '/titannexus/training-content/{record}/edit'
*/
EditTrainingContentModuleForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditTrainingContentModule.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\EditTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/EditTrainingContentModule.php:7
* @route '/titannexus/training-content/{record}/edit'
*/
EditTrainingContentModuleForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditTrainingContentModule.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditTrainingContentModule.form = EditTrainingContentModuleForm

export default EditTrainingContentModule