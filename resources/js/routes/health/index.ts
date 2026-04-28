import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\HealthController::ready
* @see app/Http/Controllers/HealthController.php:25
* @route '/health/ready'
*/
export const ready = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ready.url(options),
    method: 'get',
})

ready.definition = {
    methods: ["get","head"],
    url: '/health/ready',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\HealthController::ready
* @see app/Http/Controllers/HealthController.php:25
* @route '/health/ready'
*/
ready.url = (options?: RouteQueryOptions) => {
    return ready.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\HealthController::ready
* @see app/Http/Controllers/HealthController.php:25
* @route '/health/ready'
*/
ready.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ready.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\HealthController::ready
* @see app/Http/Controllers/HealthController.php:25
* @route '/health/ready'
*/
ready.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ready.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\HealthController::ready
* @see app/Http/Controllers/HealthController.php:25
* @route '/health/ready'
*/
const readyForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ready.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\HealthController::ready
* @see app/Http/Controllers/HealthController.php:25
* @route '/health/ready'
*/
readyForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ready.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\HealthController::ready
* @see app/Http/Controllers/HealthController.php:25
* @route '/health/ready'
*/
readyForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ready.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ready.form = readyForm

const health = {
    ready: Object.assign(ready, ready),
}

export default health