import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\CreateTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/CreateTrainingContentModule.php:7
* @route '/titannexus/training-content/create'
*/
const CreateTrainingContentModule = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateTrainingContentModule.url(options),
    method: 'get',
})

CreateTrainingContentModule.definition = {
    methods: ["get","head"],
    url: '/titannexus/training-content/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\CreateTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/CreateTrainingContentModule.php:7
* @route '/titannexus/training-content/create'
*/
CreateTrainingContentModule.url = (options?: RouteQueryOptions) => {
    return CreateTrainingContentModule.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\CreateTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/CreateTrainingContentModule.php:7
* @route '/titannexus/training-content/create'
*/
CreateTrainingContentModule.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateTrainingContentModule.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\CreateTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/CreateTrainingContentModule.php:7
* @route '/titannexus/training-content/create'
*/
CreateTrainingContentModule.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateTrainingContentModule.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\CreateTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/CreateTrainingContentModule.php:7
* @route '/titannexus/training-content/create'
*/
const CreateTrainingContentModuleForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateTrainingContentModule.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\CreateTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/CreateTrainingContentModule.php:7
* @route '/titannexus/training-content/create'
*/
CreateTrainingContentModuleForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateTrainingContentModule.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\CreateTrainingContentModule::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/CreateTrainingContentModule.php:7
* @route '/titannexus/training-content/create'
*/
CreateTrainingContentModuleForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateTrainingContentModule.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateTrainingContentModule.form = CreateTrainingContentModuleForm

export default CreateTrainingContentModule