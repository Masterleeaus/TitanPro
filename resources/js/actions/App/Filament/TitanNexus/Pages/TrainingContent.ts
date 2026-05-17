import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Pages\TrainingContent::__invoke
* @see app/Filament/TitanNexus/Pages/TrainingContent.php:7
* @route '/titannexus/training-content'
*/
const TrainingContent = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: TrainingContent.url(options),
    method: 'get',
})

TrainingContent.definition = {
    methods: ["get","head"],
    url: '/titannexus/training-content',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Pages\TrainingContent::__invoke
* @see app/Filament/TitanNexus/Pages/TrainingContent.php:7
* @route '/titannexus/training-content'
*/
TrainingContent.url = (options?: RouteQueryOptions) => {
    return TrainingContent.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Pages\TrainingContent::__invoke
* @see app/Filament/TitanNexus/Pages/TrainingContent.php:7
* @route '/titannexus/training-content'
*/
TrainingContent.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: TrainingContent.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\TrainingContent::__invoke
* @see app/Filament/TitanNexus/Pages/TrainingContent.php:7
* @route '/titannexus/training-content'
*/
TrainingContent.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: TrainingContent.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Pages\TrainingContent::__invoke
* @see app/Filament/TitanNexus/Pages/TrainingContent.php:7
* @route '/titannexus/training-content'
*/
const TrainingContentForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: TrainingContent.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\TrainingContent::__invoke
* @see app/Filament/TitanNexus/Pages/TrainingContent.php:7
* @route '/titannexus/training-content'
*/
TrainingContentForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: TrainingContent.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\TrainingContent::__invoke
* @see app/Filament/TitanNexus/Pages/TrainingContent.php:7
* @route '/titannexus/training-content'
*/
TrainingContentForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: TrainingContent.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

TrainingContent.form = TrainingContentForm

export default TrainingContent