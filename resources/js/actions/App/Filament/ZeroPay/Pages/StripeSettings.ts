import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\ZeroPay\Pages\StripeSettings::__invoke
* @see app/Filament/ZeroPay/Pages/StripeSettings.php:7
* @route '/zeropay/stripe-settings'
*/
const StripeSettings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: StripeSettings.url(options),
    method: 'get',
})

StripeSettings.definition = {
    methods: ["get","head"],
    url: '/zeropay/stripe-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroPay\Pages\StripeSettings::__invoke
* @see app/Filament/ZeroPay/Pages/StripeSettings.php:7
* @route '/zeropay/stripe-settings'
*/
StripeSettings.url = (options?: RouteQueryOptions) => {
    return StripeSettings.definition.url + queryParams(options)
}

/**
* @see \App\Filament\ZeroPay\Pages\StripeSettings::__invoke
* @see app/Filament/ZeroPay/Pages/StripeSettings.php:7
* @route '/zeropay/stripe-settings'
*/
StripeSettings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: StripeSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Pages\StripeSettings::__invoke
* @see app/Filament/ZeroPay/Pages/StripeSettings.php:7
* @route '/zeropay/stripe-settings'
*/
StripeSettings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: StripeSettings.url(options),
    method: 'head',
})

/**
* @see \App\Filament\ZeroPay\Pages\StripeSettings::__invoke
* @see app/Filament/ZeroPay/Pages/StripeSettings.php:7
* @route '/zeropay/stripe-settings'
*/
const StripeSettingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: StripeSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Pages\StripeSettings::__invoke
* @see app/Filament/ZeroPay/Pages/StripeSettings.php:7
* @route '/zeropay/stripe-settings'
*/
StripeSettingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: StripeSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Pages\StripeSettings::__invoke
* @see app/Filament/ZeroPay/Pages/StripeSettings.php:7
* @route '/zeropay/stripe-settings'
*/
StripeSettingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: StripeSettings.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

StripeSettings.form = StripeSettingsForm

export default StripeSettings