import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
export const alias = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: alias.url(options),
    method: 'get',
})

alias.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/owner/dispatch',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
alias.url = (options?: RouteQueryOptions) => {
    return alias.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
alias.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: alias.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
alias.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: alias.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
alias.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: alias.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
alias.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: alias.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
alias.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: alias.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
alias.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: alias.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
alias.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: alias.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
const aliasForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: alias.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
aliasForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: alias.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
aliasForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: alias.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
aliasForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: alias.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
aliasForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: alias.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
aliasForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: alias.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
aliasForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: alias.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
aliasForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: alias.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

alias.form = aliasForm

/**
* @see \App\Http\Controllers\Owner\DispatchController::technicians
* @see app/Http/Controllers/Owner/DispatchController.php:36
* @route '/owner/dispatch/technicians'
*/
export const technicians = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: technicians.url(options),
    method: 'get',
})

technicians.definition = {
    methods: ["get","head"],
    url: '/owner/dispatch/technicians',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\DispatchController::technicians
* @see app/Http/Controllers/Owner/DispatchController.php:36
* @route '/owner/dispatch/technicians'
*/
technicians.url = (options?: RouteQueryOptions) => {
    return technicians.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\DispatchController::technicians
* @see app/Http/Controllers/Owner/DispatchController.php:36
* @route '/owner/dispatch/technicians'
*/
technicians.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: technicians.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\DispatchController::technicians
* @see app/Http/Controllers/Owner/DispatchController.php:36
* @route '/owner/dispatch/technicians'
*/
technicians.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: technicians.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\DispatchController::technicians
* @see app/Http/Controllers/Owner/DispatchController.php:36
* @route '/owner/dispatch/technicians'
*/
const techniciansForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: technicians.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\DispatchController::technicians
* @see app/Http/Controllers/Owner/DispatchController.php:36
* @route '/owner/dispatch/technicians'
*/
techniciansForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: technicians.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\DispatchController::technicians
* @see app/Http/Controllers/Owner/DispatchController.php:36
* @route '/owner/dispatch/technicians'
*/
techniciansForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: technicians.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

technicians.form = techniciansForm

/**
* @see \App\Http\Controllers\Owner\DispatchController::trail
* @see app/Http/Controllers/Owner/DispatchController.php:110
* @route '/owner/dispatch/technicians/{user}/trail'
*/
export const trail = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trail.url(args, options),
    method: 'get',
})

trail.definition = {
    methods: ["get","head"],
    url: '/owner/dispatch/technicians/{user}/trail',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\DispatchController::trail
* @see app/Http/Controllers/Owner/DispatchController.php:110
* @route '/owner/dispatch/technicians/{user}/trail'
*/
trail.url = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { user: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            user: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        user: typeof args.user === 'object'
        ? args.user.id
        : args.user,
    }

    return trail.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\DispatchController::trail
* @see app/Http/Controllers/Owner/DispatchController.php:110
* @route '/owner/dispatch/technicians/{user}/trail'
*/
trail.get = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trail.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\DispatchController::trail
* @see app/Http/Controllers/Owner/DispatchController.php:110
* @route '/owner/dispatch/technicians/{user}/trail'
*/
trail.head = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: trail.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\DispatchController::trail
* @see app/Http/Controllers/Owner/DispatchController.php:110
* @route '/owner/dispatch/technicians/{user}/trail'
*/
const trailForm = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: trail.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\DispatchController::trail
* @see app/Http/Controllers/Owner/DispatchController.php:110
* @route '/owner/dispatch/technicians/{user}/trail'
*/
trailForm.get = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: trail.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\DispatchController::trail
* @see app/Http/Controllers/Owner/DispatchController.php:110
* @route '/owner/dispatch/technicians/{user}/trail'
*/
trailForm.head = (args: { user: number | { id: number } } | [user: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: trail.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

trail.form = trailForm

const dispatch = {
    technicians: Object.assign(technicians, technicians),
    trail: Object.assign(trail, trail),
}

export default dispatch