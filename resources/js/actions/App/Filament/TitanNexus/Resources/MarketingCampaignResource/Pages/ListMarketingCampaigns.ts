import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\ListMarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/ListMarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
const ListMarketingCampaigns = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListMarketingCampaigns.url(options),
    method: 'get',
})

ListMarketingCampaigns.definition = {
    methods: ["get","head"],
    url: '/titannexus/marketing-campaigns',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\ListMarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/ListMarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
ListMarketingCampaigns.url = (options?: RouteQueryOptions) => {
    return ListMarketingCampaigns.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\ListMarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/ListMarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
ListMarketingCampaigns.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListMarketingCampaigns.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\ListMarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/ListMarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
ListMarketingCampaigns.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListMarketingCampaigns.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\ListMarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/ListMarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
const ListMarketingCampaignsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMarketingCampaigns.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\ListMarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/ListMarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
ListMarketingCampaignsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMarketingCampaigns.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\ListMarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/ListMarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
ListMarketingCampaignsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMarketingCampaigns.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListMarketingCampaigns.form = ListMarketingCampaignsForm

export default ListMarketingCampaigns