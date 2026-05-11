import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanQuotes\Resources\EstimateResource\Pages\CreateEstimate::__invoke
* @see app/Filament/TitanQuotes/Resources/EstimateResource/Pages/CreateEstimate.php:7
* @route '/titanquotes/estimates/create'
*/
const CreateEstimate = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateEstimate.url(options),
    method: 'get',
})

CreateEstimate.definition = {
    methods: ["get","head"],
    url: '/titanquotes/estimates/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanQuotes\Resources\EstimateResource\Pages\CreateEstimate::__invoke
* @see app/Filament/TitanQuotes/Resources/EstimateResource/Pages/CreateEstimate.php:7
* @route '/titanquotes/estimates/create'
*/
CreateEstimate.url = (options?: RouteQueryOptions) => {
    return CreateEstimate.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanQuotes\Resources\EstimateResource\Pages\CreateEstimate::__invoke
* @see app/Filament/TitanQuotes/Resources/EstimateResource/Pages/CreateEstimate.php:7
* @route '/titanquotes/estimates/create'
*/
CreateEstimate.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateEstimate.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Resources\EstimateResource\Pages\CreateEstimate::__invoke
* @see app/Filament/TitanQuotes/Resources/EstimateResource/Pages/CreateEstimate.php:7
* @route '/titanquotes/estimates/create'
*/
CreateEstimate.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateEstimate.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanQuotes\Resources\EstimateResource\Pages\CreateEstimate::__invoke
* @see app/Filament/TitanQuotes/Resources/EstimateResource/Pages/CreateEstimate.php:7
* @route '/titanquotes/estimates/create'
*/
const CreateEstimateForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateEstimate.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Resources\EstimateResource\Pages\CreateEstimate::__invoke
* @see app/Filament/TitanQuotes/Resources/EstimateResource/Pages/CreateEstimate.php:7
* @route '/titanquotes/estimates/create'
*/
CreateEstimateForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateEstimate.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Resources\EstimateResource\Pages\CreateEstimate::__invoke
* @see app/Filament/TitanQuotes/Resources/EstimateResource/Pages/CreateEstimate.php:7
* @route '/titanquotes/estimates/create'
*/
CreateEstimateForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateEstimate.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateEstimate.form = CreateEstimateForm

export default CreateEstimate