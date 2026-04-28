import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Owner\BillingController::index
* @see app/Http/Controllers/Owner/BillingController.php:14
* @route '/owner/billing'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/owner/billing',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\BillingController::index
* @see app/Http/Controllers/Owner/BillingController.php:14
* @route '/owner/billing'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\BillingController::index
* @see app/Http/Controllers/Owner/BillingController.php:14
* @route '/owner/billing'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\BillingController::index
* @see app/Http/Controllers/Owner/BillingController.php:14
* @route '/owner/billing'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\BillingController::index
* @see app/Http/Controllers/Owner/BillingController.php:14
* @route '/owner/billing'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\BillingController::index
* @see app/Http/Controllers/Owner/BillingController.php:14
* @route '/owner/billing'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\BillingController::index
* @see app/Http/Controllers/Owner/BillingController.php:14
* @route '/owner/billing'
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

const BillingController = { index }

export default BillingController