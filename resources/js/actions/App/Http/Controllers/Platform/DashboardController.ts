import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Platform\DashboardController::index
* @see app/Http/Controllers/Platform/DashboardController.php:17
* @route '/platform/dashboard'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/platform/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Platform\DashboardController::index
* @see app/Http/Controllers/Platform/DashboardController.php:17
* @route '/platform/dashboard'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Platform\DashboardController::index
* @see app/Http/Controllers/Platform/DashboardController.php:17
* @route '/platform/dashboard'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\DashboardController::index
* @see app/Http/Controllers/Platform/DashboardController.php:17
* @route '/platform/dashboard'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Platform\DashboardController::index
* @see app/Http/Controllers/Platform/DashboardController.php:17
* @route '/platform/dashboard'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\DashboardController::index
* @see app/Http/Controllers/Platform/DashboardController.php:17
* @route '/platform/dashboard'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\DashboardController::index
* @see app/Http/Controllers/Platform/DashboardController.php:17
* @route '/platform/dashboard'
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
* @see \App\Http\Controllers\Platform\DashboardController::updateOrganization
* @see app/Http/Controllers/Platform/DashboardController.php:67
* @route '/platform/organizations/{organization}'
*/
export const updateOrganization = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateOrganization.url(args, options),
    method: 'patch',
})

updateOrganization.definition = {
    methods: ["patch"],
    url: '/platform/organizations/{organization}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Platform\DashboardController::updateOrganization
* @see app/Http/Controllers/Platform/DashboardController.php:67
* @route '/platform/organizations/{organization}'
*/
updateOrganization.url = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { organization: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { organization: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            organization: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        organization: typeof args.organization === 'object'
        ? args.organization.id
        : args.organization,
    }

    return updateOrganization.definition.url
            .replace('{organization}', parsedArgs.organization.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Platform\DashboardController::updateOrganization
* @see app/Http/Controllers/Platform/DashboardController.php:67
* @route '/platform/organizations/{organization}'
*/
updateOrganization.patch = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateOrganization.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Platform\DashboardController::updateOrganization
* @see app/Http/Controllers/Platform/DashboardController.php:67
* @route '/platform/organizations/{organization}'
*/
const updateOrganizationForm = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updateOrganization.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Platform\DashboardController::updateOrganization
* @see app/Http/Controllers/Platform/DashboardController.php:67
* @route '/platform/organizations/{organization}'
*/
updateOrganizationForm.patch = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updateOrganization.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

updateOrganization.form = updateOrganizationForm

/**
* @see \App\Http\Controllers\Platform\DashboardController::updateSubscription
* @see app/Http/Controllers/Platform/DashboardController.php:82
* @route '/platform/organizations/{organization}/subscription'
*/
export const updateSubscription = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateSubscription.url(args, options),
    method: 'patch',
})

updateSubscription.definition = {
    methods: ["patch"],
    url: '/platform/organizations/{organization}/subscription',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Platform\DashboardController::updateSubscription
* @see app/Http/Controllers/Platform/DashboardController.php:82
* @route '/platform/organizations/{organization}/subscription'
*/
updateSubscription.url = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { organization: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { organization: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            organization: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        organization: typeof args.organization === 'object'
        ? args.organization.id
        : args.organization,
    }

    return updateSubscription.definition.url
            .replace('{organization}', parsedArgs.organization.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Platform\DashboardController::updateSubscription
* @see app/Http/Controllers/Platform/DashboardController.php:82
* @route '/platform/organizations/{organization}/subscription'
*/
updateSubscription.patch = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateSubscription.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Platform\DashboardController::updateSubscription
* @see app/Http/Controllers/Platform/DashboardController.php:82
* @route '/platform/organizations/{organization}/subscription'
*/
const updateSubscriptionForm = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updateSubscription.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Platform\DashboardController::updateSubscription
* @see app/Http/Controllers/Platform/DashboardController.php:82
* @route '/platform/organizations/{organization}/subscription'
*/
updateSubscriptionForm.patch = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updateSubscription.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

updateSubscription.form = updateSubscriptionForm

/**
* @see \App\Http\Controllers\Platform\DashboardController::extendTrial
* @see app/Http/Controllers/Platform/DashboardController.php:106
* @route '/platform/organizations/{organization}/extend-trial'
*/
export const extendTrial = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: extendTrial.url(args, options),
    method: 'post',
})

extendTrial.definition = {
    methods: ["post"],
    url: '/platform/organizations/{organization}/extend-trial',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Platform\DashboardController::extendTrial
* @see app/Http/Controllers/Platform/DashboardController.php:106
* @route '/platform/organizations/{organization}/extend-trial'
*/
extendTrial.url = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { organization: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { organization: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            organization: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        organization: typeof args.organization === 'object'
        ? args.organization.id
        : args.organization,
    }

    return extendTrial.definition.url
            .replace('{organization}', parsedArgs.organization.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Platform\DashboardController::extendTrial
* @see app/Http/Controllers/Platform/DashboardController.php:106
* @route '/platform/organizations/{organization}/extend-trial'
*/
extendTrial.post = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: extendTrial.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Platform\DashboardController::extendTrial
* @see app/Http/Controllers/Platform/DashboardController.php:106
* @route '/platform/organizations/{organization}/extend-trial'
*/
const extendTrialForm = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: extendTrial.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Platform\DashboardController::extendTrial
* @see app/Http/Controllers/Platform/DashboardController.php:106
* @route '/platform/organizations/{organization}/extend-trial'
*/
extendTrialForm.post = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: extendTrial.url(args, options),
    method: 'post',
})

extendTrial.form = extendTrialForm

/**
* @see \App\Http\Controllers\Platform\DashboardController::activate
* @see app/Http/Controllers/Platform/DashboardController.php:127
* @route '/platform/organizations/{organization}/activate'
*/
export const activate = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: activate.url(args, options),
    method: 'post',
})

activate.definition = {
    methods: ["post"],
    url: '/platform/organizations/{organization}/activate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Platform\DashboardController::activate
* @see app/Http/Controllers/Platform/DashboardController.php:127
* @route '/platform/organizations/{organization}/activate'
*/
activate.url = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { organization: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { organization: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            organization: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        organization: typeof args.organization === 'object'
        ? args.organization.id
        : args.organization,
    }

    return activate.definition.url
            .replace('{organization}', parsedArgs.organization.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Platform\DashboardController::activate
* @see app/Http/Controllers/Platform/DashboardController.php:127
* @route '/platform/organizations/{organization}/activate'
*/
activate.post = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: activate.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Platform\DashboardController::activate
* @see app/Http/Controllers/Platform/DashboardController.php:127
* @route '/platform/organizations/{organization}/activate'
*/
const activateForm = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: activate.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Platform\DashboardController::activate
* @see app/Http/Controllers/Platform/DashboardController.php:127
* @route '/platform/organizations/{organization}/activate'
*/
activateForm.post = (args: { organization: number | { id: number } } | [organization: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: activate.url(args, options),
    method: 'post',
})

activate.form = activateForm

const DashboardController = { index, updateOrganization, updateSubscription, extendTrial, activate }

export default DashboardController