import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Pages\MarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Pages/MarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
const MarketingCampaigns = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MarketingCampaigns.url(options),
    method: 'get',
})

MarketingCampaigns.definition = {
    methods: ["get","head"],
    url: '/titannexus/marketing-campaigns',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Pages\MarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Pages/MarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
MarketingCampaigns.url = (options?: RouteQueryOptions) => {
    return MarketingCampaigns.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Pages\MarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Pages/MarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
MarketingCampaigns.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MarketingCampaigns.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\MarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Pages/MarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
MarketingCampaigns.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: MarketingCampaigns.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Pages\MarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Pages/MarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
const MarketingCampaignsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MarketingCampaigns.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\MarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Pages/MarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
MarketingCampaignsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MarketingCampaigns.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\MarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Pages/MarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
MarketingCampaignsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MarketingCampaigns.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

MarketingCampaigns.form = MarketingCampaignsForm

export default MarketingCampaigns