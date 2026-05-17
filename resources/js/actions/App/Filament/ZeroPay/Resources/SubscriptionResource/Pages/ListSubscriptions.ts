import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/zeropay/subscriptions'
*/
const ListSubscriptions = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSubscriptions.url(options),
    method: 'get',
})

ListSubscriptions.definition = {
    methods: ["get","head"],
    url: '/zeropay/subscriptions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/zeropay/subscriptions'
*/
ListSubscriptions.url = (options?: RouteQueryOptions) => {
    return ListSubscriptions.definition.url + queryParams(options)
}

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/zeropay/subscriptions'
*/
ListSubscriptions.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSubscriptions.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/zeropay/subscriptions'
*/
ListSubscriptions.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListSubscriptions.url(options),
    method: 'head',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/zeropay/subscriptions'
*/
const ListSubscriptionsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListSubscriptions.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/zeropay/subscriptions'
*/
ListSubscriptionsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListSubscriptions.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/zeropay/subscriptions'
*/
ListSubscriptionsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListSubscriptions.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListSubscriptions.form = ListSubscriptionsForm

export default ListSubscriptions