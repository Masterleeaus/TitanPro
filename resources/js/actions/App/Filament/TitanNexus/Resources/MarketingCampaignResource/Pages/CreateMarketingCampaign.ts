import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\CreateMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/CreateMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/create'
*/
const CreateMarketingCampaign = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateMarketingCampaign.url(options),
    method: 'get',
})

CreateMarketingCampaign.definition = {
    methods: ["get","head"],
    url: '/titannexus/marketing-campaigns/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\CreateMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/CreateMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/create'
*/
CreateMarketingCampaign.url = (options?: RouteQueryOptions) => {
    return CreateMarketingCampaign.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\CreateMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/CreateMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/create'
*/
CreateMarketingCampaign.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateMarketingCampaign.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\CreateMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/CreateMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/create'
*/
CreateMarketingCampaign.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateMarketingCampaign.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\CreateMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/CreateMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/create'
*/
const CreateMarketingCampaignForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateMarketingCampaign.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\CreateMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/CreateMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/create'
*/
CreateMarketingCampaignForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateMarketingCampaign.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\CreateMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/CreateMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/create'
*/
CreateMarketingCampaignForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateMarketingCampaign.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateMarketingCampaign.form = CreateMarketingCampaignForm

export default CreateMarketingCampaign