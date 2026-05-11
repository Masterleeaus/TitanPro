import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\GroundZero\Resources\EstimateResource\Pages\ListEstimates::__invoke
* @see app/Filament/GroundZero/Resources/EstimateResource/Pages/ListEstimates.php:7
* @route '/groundzero/estimates'
*/
const ListEstimates = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListEstimates.url(options),
    method: 'get',
})

ListEstimates.definition = {
    methods: ["get","head"],
    url: '/groundzero/estimates',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\GroundZero\Resources\EstimateResource\Pages\ListEstimates::__invoke
* @see app/Filament/GroundZero/Resources/EstimateResource/Pages/ListEstimates.php:7
* @route '/groundzero/estimates'
*/
ListEstimates.url = (options?: RouteQueryOptions) => {
    return ListEstimates.definition.url + queryParams(options)
}

/**
* @see \App\Filament\GroundZero\Resources\EstimateResource\Pages\ListEstimates::__invoke
* @see app/Filament/GroundZero/Resources/EstimateResource/Pages/ListEstimates.php:7
* @route '/groundzero/estimates'
*/
ListEstimates.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListEstimates.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Resources\EstimateResource\Pages\ListEstimates::__invoke
* @see app/Filament/GroundZero/Resources/EstimateResource/Pages/ListEstimates.php:7
* @route '/groundzero/estimates'
*/
ListEstimates.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListEstimates.url(options),
    method: 'head',
})

/**
* @see \App\Filament\GroundZero\Resources\EstimateResource\Pages\ListEstimates::__invoke
* @see app/Filament/GroundZero/Resources/EstimateResource/Pages/ListEstimates.php:7
* @route '/groundzero/estimates'
*/
const ListEstimatesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListEstimates.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Resources\EstimateResource\Pages\ListEstimates::__invoke
* @see app/Filament/GroundZero/Resources/EstimateResource/Pages/ListEstimates.php:7
* @route '/groundzero/estimates'
*/
ListEstimatesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListEstimates.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Resources\EstimateResource\Pages\ListEstimates::__invoke
* @see app/Filament/GroundZero/Resources/EstimateResource/Pages/ListEstimates.php:7
* @route '/groundzero/estimates'
*/
ListEstimatesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListEstimates.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListEstimates.form = ListEstimatesForm

export default ListEstimates