import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanPro\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/TitanPro/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/titanpro/subscriptions'
*/
const ListSubscriptions = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSubscriptions.url(options),
    method: 'get',
})

ListSubscriptions.definition = {
    methods: ["get","head"],
    url: '/titanpro/subscriptions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanPro\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/TitanPro/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/titanpro/subscriptions'
*/
ListSubscriptions.url = (options?: RouteQueryOptions) => {
    return ListSubscriptions.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanPro\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/TitanPro/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/titanpro/subscriptions'
*/
ListSubscriptions.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSubscriptions.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/TitanPro/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/titanpro/subscriptions'
*/
ListSubscriptions.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListSubscriptions.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanPro\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/TitanPro/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/titanpro/subscriptions'
*/
const ListSubscriptionsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListSubscriptions.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/TitanPro/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/titanpro/subscriptions'
*/
ListSubscriptionsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListSubscriptions.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/TitanPro/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/titanpro/subscriptions'
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