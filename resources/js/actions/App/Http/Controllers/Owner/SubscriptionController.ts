import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Owner\SubscriptionController::index
* @see app/Http/Controllers/Owner/SubscriptionController.php:23
* @route '/owner/subscription'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/owner/subscription',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::index
* @see app/Http/Controllers/Owner/SubscriptionController.php:23
* @route '/owner/subscription'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::index
* @see app/Http/Controllers/Owner/SubscriptionController.php:23
* @route '/owner/subscription'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::index
* @see app/Http/Controllers/Owner/SubscriptionController.php:23
* @route '/owner/subscription'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::index
* @see app/Http/Controllers/Owner/SubscriptionController.php:23
* @route '/owner/subscription'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::index
* @see app/Http/Controllers/Owner/SubscriptionController.php:23
* @route '/owner/subscription'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::index
* @see app/Http/Controllers/Owner/SubscriptionController.php:23
* @route '/owner/subscription'
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
* @see \App\Http\Controllers\Owner\SubscriptionController::checkout
* @see app/Http/Controllers/Owner/SubscriptionController.php:60
* @route '/owner/subscription/checkout'
*/
export const checkout = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkout.url(options),
    method: 'post',
})

checkout.definition = {
    methods: ["post"],
    url: '/owner/subscription/checkout',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::checkout
* @see app/Http/Controllers/Owner/SubscriptionController.php:60
* @route '/owner/subscription/checkout'
*/
checkout.url = (options?: RouteQueryOptions) => {
    return checkout.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::checkout
* @see app/Http/Controllers/Owner/SubscriptionController.php:60
* @route '/owner/subscription/checkout'
*/
checkout.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkout.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::checkout
* @see app/Http/Controllers/Owner/SubscriptionController.php:60
* @route '/owner/subscription/checkout'
*/
const checkoutForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: checkout.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::checkout
* @see app/Http/Controllers/Owner/SubscriptionController.php:60
* @route '/owner/subscription/checkout'
*/
checkoutForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: checkout.url(options),
    method: 'post',
})

checkout.form = checkoutForm

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::success
* @see app/Http/Controllers/Owner/SubscriptionController.php:86
* @route '/owner/subscription/success'
*/
export const success = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: success.url(options),
    method: 'get',
})

success.definition = {
    methods: ["get","head"],
    url: '/owner/subscription/success',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::success
* @see app/Http/Controllers/Owner/SubscriptionController.php:86
* @route '/owner/subscription/success'
*/
success.url = (options?: RouteQueryOptions) => {
    return success.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::success
* @see app/Http/Controllers/Owner/SubscriptionController.php:86
* @route '/owner/subscription/success'
*/
success.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: success.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::success
* @see app/Http/Controllers/Owner/SubscriptionController.php:86
* @route '/owner/subscription/success'
*/
success.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: success.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::success
* @see app/Http/Controllers/Owner/SubscriptionController.php:86
* @route '/owner/subscription/success'
*/
const successForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: success.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::success
* @see app/Http/Controllers/Owner/SubscriptionController.php:86
* @route '/owner/subscription/success'
*/
successForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: success.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::success
* @see app/Http/Controllers/Owner/SubscriptionController.php:86
* @route '/owner/subscription/success'
*/
successForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: success.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

success.form = successForm

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::expired
* @see app/Http/Controllers/Owner/SubscriptionController.php:47
* @route '/owner/subscription/expired'
*/
export const expired = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: expired.url(options),
    method: 'get',
})

expired.definition = {
    methods: ["get","head"],
    url: '/owner/subscription/expired',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::expired
* @see app/Http/Controllers/Owner/SubscriptionController.php:47
* @route '/owner/subscription/expired'
*/
expired.url = (options?: RouteQueryOptions) => {
    return expired.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::expired
* @see app/Http/Controllers/Owner/SubscriptionController.php:47
* @route '/owner/subscription/expired'
*/
expired.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: expired.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::expired
* @see app/Http/Controllers/Owner/SubscriptionController.php:47
* @route '/owner/subscription/expired'
*/
expired.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: expired.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::expired
* @see app/Http/Controllers/Owner/SubscriptionController.php:47
* @route '/owner/subscription/expired'
*/
const expiredForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: expired.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::expired
* @see app/Http/Controllers/Owner/SubscriptionController.php:47
* @route '/owner/subscription/expired'
*/
expiredForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: expired.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\SubscriptionController::expired
* @see app/Http/Controllers/Owner/SubscriptionController.php:47
* @route '/owner/subscription/expired'
*/
expiredForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: expired.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

expired.form = expiredForm

const SubscriptionController = { index, checkout, success, expired }

export default SubscriptionController