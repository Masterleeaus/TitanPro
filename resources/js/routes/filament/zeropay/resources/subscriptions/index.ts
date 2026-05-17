import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/zeropay/subscriptions'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/zeropay/subscriptions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/zeropay/subscriptions'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/zeropay/subscriptions'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/zeropay/subscriptions'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/zeropay/subscriptions'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/zeropay/subscriptions'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ListSubscriptions::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php:7
* @route '/zeropay/subscriptions'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ViewSubscription::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ViewSubscription.php:7
* @route '/zeropay/subscriptions/{record}'
*/
export const view = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: view.url(args, options),
    method: 'get',
})

view.definition = {
    methods: ["get","head"],
    url: '/zeropay/subscriptions/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ViewSubscription::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ViewSubscription.php:7
* @route '/zeropay/subscriptions/{record}'
*/
view.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return view.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ViewSubscription::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ViewSubscription.php:7
* @route '/zeropay/subscriptions/{record}'
*/
view.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: view.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ViewSubscription::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ViewSubscription.php:7
* @route '/zeropay/subscriptions/{record}'
*/
view.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: view.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ViewSubscription::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ViewSubscription.php:7
* @route '/zeropay/subscriptions/{record}'
*/
const viewForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: view.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ViewSubscription::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ViewSubscription.php:7
* @route '/zeropay/subscriptions/{record}'
*/
viewForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: view.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\ViewSubscription::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ViewSubscription.php:7
* @route '/zeropay/subscriptions/{record}'
*/
viewForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: view.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

view.form = viewForm

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\EditSubscription::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/EditSubscription.php:7
* @route '/zeropay/subscriptions/{record}/edit'
*/
export const edit = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/zeropay/subscriptions/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\EditSubscription::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/EditSubscription.php:7
* @route '/zeropay/subscriptions/{record}/edit'
*/
edit.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\EditSubscription::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/EditSubscription.php:7
* @route '/zeropay/subscriptions/{record}/edit'
*/
edit.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\EditSubscription::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/EditSubscription.php:7
* @route '/zeropay/subscriptions/{record}/edit'
*/
edit.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\EditSubscription::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/EditSubscription.php:7
* @route '/zeropay/subscriptions/{record}/edit'
*/
const editForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\EditSubscription::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/EditSubscription.php:7
* @route '/zeropay/subscriptions/{record}/edit'
*/
editForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\SubscriptionResource\Pages\EditSubscription::__invoke
* @see app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/EditSubscription.php:7
* @route '/zeropay/subscriptions/{record}/edit'
*/
editForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

const subscriptions = {
    index: Object.assign(index, index),
    view: Object.assign(view, view),
    edit: Object.assign(edit, edit),
}

export default subscriptions