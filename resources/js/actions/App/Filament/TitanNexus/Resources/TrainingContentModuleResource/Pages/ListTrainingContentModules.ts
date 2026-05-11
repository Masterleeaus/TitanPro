import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\ListTrainingContentModules::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/ListTrainingContentModules.php:7
* @route '/titannexus/training-content'
*/
const ListTrainingContentModules = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListTrainingContentModules.url(options),
    method: 'get',
})

ListTrainingContentModules.definition = {
    methods: ["get","head"],
    url: '/titannexus/training-content',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\ListTrainingContentModules::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/ListTrainingContentModules.php:7
* @route '/titannexus/training-content'
*/
ListTrainingContentModules.url = (options?: RouteQueryOptions) => {
    return ListTrainingContentModules.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\ListTrainingContentModules::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/ListTrainingContentModules.php:7
* @route '/titannexus/training-content'
*/
ListTrainingContentModules.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListTrainingContentModules.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\ListTrainingContentModules::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/ListTrainingContentModules.php:7
* @route '/titannexus/training-content'
*/
ListTrainingContentModules.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListTrainingContentModules.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\ListTrainingContentModules::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/ListTrainingContentModules.php:7
* @route '/titannexus/training-content'
*/
const ListTrainingContentModulesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListTrainingContentModules.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\ListTrainingContentModules::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/ListTrainingContentModules.php:7
* @route '/titannexus/training-content'
*/
ListTrainingContentModulesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListTrainingContentModules.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages\ListTrainingContentModules::__invoke
* @see app/Filament/TitanNexus/Resources/TrainingContentModuleResource/Pages/ListTrainingContentModules.php:7
* @route '/titannexus/training-content'
*/
ListTrainingContentModulesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListTrainingContentModules.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListTrainingContentModules.form = ListTrainingContentModulesForm

export default ListTrainingContentModules