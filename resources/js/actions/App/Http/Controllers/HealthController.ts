import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\HealthController::liveness
* @see app/Http/Controllers/HealthController.php:16
* @route '/health'
*/
export const liveness = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: liveness.url(options),
    method: 'get',
})

liveness.definition = {
    methods: ["get","head"],
    url: '/health',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\HealthController::liveness
* @see app/Http/Controllers/HealthController.php:16
* @route '/health'
*/
liveness.url = (options?: RouteQueryOptions) => {
    return liveness.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\HealthController::liveness
* @see app/Http/Controllers/HealthController.php:16
* @route '/health'
*/
liveness.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: liveness.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\HealthController::liveness
* @see app/Http/Controllers/HealthController.php:16
* @route '/health'
*/
liveness.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: liveness.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\HealthController::liveness
* @see app/Http/Controllers/HealthController.php:16
* @route '/health'
*/
const livenessForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: liveness.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\HealthController::liveness
* @see app/Http/Controllers/HealthController.php:16
* @route '/health'
*/
livenessForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: liveness.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\HealthController::liveness
* @see app/Http/Controllers/HealthController.php:16
* @route '/health'
*/
livenessForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: liveness.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

liveness.form = livenessForm

/**
* @see \App\Http\Controllers\HealthController::readiness
* @see app/Http/Controllers/HealthController.php:25
* @route '/health/ready'
*/
export const readiness = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: readiness.url(options),
    method: 'get',
})

readiness.definition = {
    methods: ["get","head"],
    url: '/health/ready',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\HealthController::readiness
* @see app/Http/Controllers/HealthController.php:25
* @route '/health/ready'
*/
readiness.url = (options?: RouteQueryOptions) => {
    return readiness.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\HealthController::readiness
* @see app/Http/Controllers/HealthController.php:25
* @route '/health/ready'
*/
readiness.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: readiness.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\HealthController::readiness
* @see app/Http/Controllers/HealthController.php:25
* @route '/health/ready'
*/
readiness.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: readiness.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\HealthController::readiness
* @see app/Http/Controllers/HealthController.php:25
* @route '/health/ready'
*/
const readinessForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: readiness.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\HealthController::readiness
* @see app/Http/Controllers/HealthController.php:25
* @route '/health/ready'
*/
readinessForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: readiness.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\HealthController::readiness
* @see app/Http/Controllers/HealthController.php:25
* @route '/health/ready'
*/
readinessForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: readiness.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

readiness.form = readinessForm

const HealthController = { liveness, readiness }

export default HealthController