import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanPro\Resources\SubscriptionResource\Pages\CreateSubscription::__invoke
* @see app/Filament/TitanPro/Resources/SubscriptionResource/Pages/CreateSubscription.php:7
* @route '/titanpro/subscriptions/create'
*/
const CreateSubscription = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateSubscription.url(options),
    method: 'get',
})

CreateSubscription.definition = {
    methods: ["get","head"],
    url: '/titanpro/subscriptions/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanPro\Resources\SubscriptionResource\Pages\CreateSubscription::__invoke
* @see app/Filament/TitanPro/Resources/SubscriptionResource/Pages/CreateSubscription.php:7
* @route '/titanpro/subscriptions/create'
*/
CreateSubscription.url = (options?: RouteQueryOptions) => {
    return CreateSubscription.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanPro\Resources\SubscriptionResource\Pages\CreateSubscription::__invoke
* @see app/Filament/TitanPro/Resources/SubscriptionResource/Pages/CreateSubscription.php:7
* @route '/titanpro/subscriptions/create'
*/
CreateSubscription.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateSubscription.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\SubscriptionResource\Pages\CreateSubscription::__invoke
* @see app/Filament/TitanPro/Resources/SubscriptionResource/Pages/CreateSubscription.php:7
* @route '/titanpro/subscriptions/create'
*/
CreateSubscription.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateSubscription.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanPro\Resources\SubscriptionResource\Pages\CreateSubscription::__invoke
* @see app/Filament/TitanPro/Resources/SubscriptionResource/Pages/CreateSubscription.php:7
* @route '/titanpro/subscriptions/create'
*/
const CreateSubscriptionForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateSubscription.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\SubscriptionResource\Pages\CreateSubscription::__invoke
* @see app/Filament/TitanPro/Resources/SubscriptionResource/Pages/CreateSubscription.php:7
* @route '/titanpro/subscriptions/create'
*/
CreateSubscriptionForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateSubscription.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\SubscriptionResource\Pages\CreateSubscription::__invoke
* @see app/Filament/TitanPro/Resources/SubscriptionResource/Pages/CreateSubscription.php:7
* @route '/titanpro/subscriptions/create'
*/
CreateSubscriptionForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateSubscription.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateSubscription.form = CreateSubscriptionForm

export default CreateSubscription