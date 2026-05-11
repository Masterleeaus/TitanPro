import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\EditMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/EditMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/{record}/edit'
*/
const EditMarketingCampaign = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditMarketingCampaign.url(args, options),
    method: 'get',
})

EditMarketingCampaign.definition = {
    methods: ["get","head"],
    url: '/titannexus/marketing-campaigns/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\EditMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/EditMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/{record}/edit'
*/
EditMarketingCampaign.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditMarketingCampaign.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\EditMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/EditMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/{record}/edit'
*/
EditMarketingCampaign.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditMarketingCampaign.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\EditMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/EditMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/{record}/edit'
*/
EditMarketingCampaign.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditMarketingCampaign.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\EditMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/EditMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/{record}/edit'
*/
const EditMarketingCampaignForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditMarketingCampaign.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\EditMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/EditMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/{record}/edit'
*/
EditMarketingCampaignForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditMarketingCampaign.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages\EditMarketingCampaign::__invoke
* @see app/Filament/TitanNexus/Resources/MarketingCampaignResource/Pages/EditMarketingCampaign.php:7
* @route '/titannexus/marketing-campaigns/{record}/edit'
*/
EditMarketingCampaignForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditMarketingCampaign.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditMarketingCampaign.form = EditMarketingCampaignForm

export default EditMarketingCampaign