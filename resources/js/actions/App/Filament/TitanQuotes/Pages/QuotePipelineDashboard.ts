import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\TitanQuotes\Pages\QuotePipelineDashboard::__invoke
* @see app/Filament/TitanQuotes/Pages/QuotePipelineDashboard.php:7
* @route '/titanquotes/quote-pipeline'
*/
const QuotePipelineDashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: QuotePipelineDashboard.url(options),
    method: 'get',
})

QuotePipelineDashboard.definition = {
    methods: ["get","head"],
    url: '/titanquotes/quote-pipeline',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanQuotes\Pages\QuotePipelineDashboard::__invoke
* @see app/Filament/TitanQuotes/Pages/QuotePipelineDashboard.php:7
* @route '/titanquotes/quote-pipeline'
*/
QuotePipelineDashboard.url = (options?: RouteQueryOptions) => {
    return QuotePipelineDashboard.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanQuotes\Pages\QuotePipelineDashboard::__invoke
* @see app/Filament/TitanQuotes/Pages/QuotePipelineDashboard.php:7
* @route '/titanquotes/quote-pipeline'
*/
QuotePipelineDashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: QuotePipelineDashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Pages\QuotePipelineDashboard::__invoke
* @see app/Filament/TitanQuotes/Pages/QuotePipelineDashboard.php:7
* @route '/titanquotes/quote-pipeline'
*/
QuotePipelineDashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: QuotePipelineDashboard.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanQuotes\Pages\QuotePipelineDashboard::__invoke
* @see app/Filament/TitanQuotes/Pages/QuotePipelineDashboard.php:7
* @route '/titanquotes/quote-pipeline'
*/
const QuotePipelineDashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: QuotePipelineDashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Pages\QuotePipelineDashboard::__invoke
* @see app/Filament/TitanQuotes/Pages/QuotePipelineDashboard.php:7
* @route '/titanquotes/quote-pipeline'
*/
QuotePipelineDashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: QuotePipelineDashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Pages\QuotePipelineDashboard::__invoke
* @see app/Filament/TitanQuotes/Pages/QuotePipelineDashboard.php:7
* @route '/titanquotes/quote-pipeline'
*/
QuotePipelineDashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: QuotePipelineDashboard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

QuotePipelineDashboard.form = QuotePipelineDashboardForm

export default QuotePipelineDashboard