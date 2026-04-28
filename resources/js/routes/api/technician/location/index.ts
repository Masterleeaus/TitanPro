import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Technician\LocationController::store
* @see app/Http/Controllers/Technician/LocationController.php:13
* @route '/api/technician/location'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/technician/location',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Technician\LocationController::store
* @see app/Http/Controllers/Technician/LocationController.php:13
* @route '/api/technician/location'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Technician\LocationController::store
* @see app/Http/Controllers/Technician/LocationController.php:13
* @route '/api/technician/location'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Technician\LocationController::store
* @see app/Http/Controllers/Technician/LocationController.php:13
* @route '/api/technician/location'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Technician\LocationController::store
* @see app/Http/Controllers/Technician/LocationController.php:13
* @route '/api/technician/location'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

const location = {
    store: Object.assign(store, store),
}

export default location