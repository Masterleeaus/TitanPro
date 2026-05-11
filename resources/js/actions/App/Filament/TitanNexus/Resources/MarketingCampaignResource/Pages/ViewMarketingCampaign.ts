import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\ViewMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/ViewMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/{record}'
*/
const ViewMarketingCampaign = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewMarketingCampaign.url(args, options),
    method: 'get',
})

ViewMarketingCampaign.definition = {
    methods: ["get","head"],
    url: '/titannexus/marketing-campaigns/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\ViewMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/ViewMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/{record}'
*/
ViewMarketingCampaign.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ViewMarketingCampaign.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\ViewMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/ViewMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/{record}'
*/
ViewMarketingCampaign.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewMarketingCampaign.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\ViewMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/ViewMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/{record}'
*/
ViewMarketingCampaign.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewMarketingCampaign.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\ViewMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/ViewMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/{record}'
*/
const ViewMarketingCampaignForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewMarketingCampaign.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\ViewMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/ViewMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/{record}'
*/
ViewMarketingCampaignForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewMarketingCampaign.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\ViewMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/ViewMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/{record}'
*/
ViewMarketingCampaignForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewMarketingCampaign.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ViewMarketingCampaign.form = ViewMarketingCampaignForm

export default ViewMarketingCampaign