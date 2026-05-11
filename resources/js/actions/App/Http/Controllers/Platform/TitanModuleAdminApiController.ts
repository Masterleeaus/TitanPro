import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::sync
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:93
* @route '/admin/titan/modules/sync'
*/
export const sync = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sync.url(options),
    method: 'post',
})

sync.definition = {
    methods: ["post"],
    url: '/admin/titan/modules/sync',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::sync
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:93
* @route '/admin/titan/modules/sync'
*/
sync.url = (options?: RouteQueryOptions) => {
    return sync.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::sync
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:93
* @route '/admin/titan/modules/sync'
*/
sync.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sync.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::sync
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:93
* @route '/admin/titan/modules/sync'
*/
const syncForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: sync.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::sync
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:93
* @route '/admin/titan/modules/sync'
*/
syncForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: sync.url(options),
    method: 'post',
})

sync.form = syncForm

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::index
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:17
* @route '/admin/titan/modules'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/titan/modules',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::index
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:17
* @route '/admin/titan/modules'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::index
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:17
* @route '/admin/titan/modules'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::index
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:17
* @route '/admin/titan/modules'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::index
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:17
* @route '/admin/titan/modules'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::index
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:17
* @route '/admin/titan/modules'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::index
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:17
* @route '/admin/titan/modules'
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
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::enable
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:34
* @route '/admin/titan/modules/{module}/enable'
*/
export const enable = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: enable.url(args, options),
    method: 'post',
})

enable.definition = {
    methods: ["post"],
    url: '/admin/titan/modules/{module}/enable',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::enable
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:34
* @route '/admin/titan/modules/{module}/enable'
*/
enable.url = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { module: args }
    }

    if (Array.isArray(args)) {
        args = {
            module: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        module: args.module,
    }

    return enable.definition.url
            .replace('{module}', parsedArgs.module.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::enable
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:34
* @route '/admin/titan/modules/{module}/enable'
*/
enable.post = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: enable.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::enable
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:34
* @route '/admin/titan/modules/{module}/enable'
*/
const enableForm = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: enable.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::enable
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:34
* @route '/admin/titan/modules/{module}/enable'
*/
enableForm.post = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: enable.url(args, options),
    method: 'post',
})

enable.form = enableForm

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::disable
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:49
* @route '/admin/titan/modules/{module}/disable'
*/
export const disable = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: disable.url(args, options),
    method: 'post',
})

disable.definition = {
    methods: ["post"],
    url: '/admin/titan/modules/{module}/disable',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::disable
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:49
* @route '/admin/titan/modules/{module}/disable'
*/
disable.url = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { module: args }
    }

    if (Array.isArray(args)) {
        args = {
            module: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        module: args.module,
    }

    return disable.definition.url
            .replace('{module}', parsedArgs.module.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::disable
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:49
* @route '/admin/titan/modules/{module}/disable'
*/
disable.post = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: disable.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::disable
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:49
* @route '/admin/titan/modules/{module}/disable'
*/
const disableForm = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: disable.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::disable
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:49
* @route '/admin/titan/modules/{module}/disable'
*/
disableForm.post = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: disable.url(args, options),
    method: 'post',
})

disable.form = disableForm

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::health
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:64
* @route '/admin/titan/modules/{module}/health'
*/
export const health = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: health.url(args, options),
    method: 'get',
})

health.definition = {
    methods: ["get","head"],
    url: '/admin/titan/modules/{module}/health',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::health
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:64
* @route '/admin/titan/modules/{module}/health'
*/
health.url = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { module: args }
    }

    if (Array.isArray(args)) {
        args = {
            module: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        module: args.module,
    }

    return health.definition.url
            .replace('{module}', parsedArgs.module.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::health
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:64
* @route '/admin/titan/modules/{module}/health'
*/
health.get = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: health.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::health
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:64
* @route '/admin/titan/modules/{module}/health'
*/
health.head = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: health.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::health
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:64
* @route '/admin/titan/modules/{module}/health'
*/
const healthForm = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: health.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::health
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:64
* @route '/admin/titan/modules/{module}/health'
*/
healthForm.get = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: health.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::health
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:64
* @route '/admin/titan/modules/{module}/health'
*/
healthForm.head = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: health.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

health.form = healthForm

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::manifests
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:82
* @route '/admin/titan/modules/{module}/manifests'
*/
export const manifests = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: manifests.url(args, options),
    method: 'get',
})

manifests.definition = {
    methods: ["get","head"],
    url: '/admin/titan/modules/{module}/manifests',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::manifests
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:82
* @route '/admin/titan/modules/{module}/manifests'
*/
manifests.url = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { module: args }
    }

    if (Array.isArray(args)) {
        args = {
            module: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        module: args.module,
    }

    return manifests.definition.url
            .replace('{module}', parsedArgs.module.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::manifests
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:82
* @route '/admin/titan/modules/{module}/manifests'
*/
manifests.get = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: manifests.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::manifests
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:82
* @route '/admin/titan/modules/{module}/manifests'
*/
manifests.head = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: manifests.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::manifests
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:82
* @route '/admin/titan/modules/{module}/manifests'
*/
const manifestsForm = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: manifests.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::manifests
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:82
* @route '/admin/titan/modules/{module}/manifests'
*/
manifestsForm.get = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: manifests.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\TitanModuleAdminApiController::manifests
* @see app/Http/Controllers/Platform/TitanModuleAdminApiController.php:82
* @route '/admin/titan/modules/{module}/manifests'
*/
manifestsForm.head = (args: { module: string | number } | [module: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: manifests.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

manifests.form = manifestsForm

const TitanModuleAdminApiController = { sync, index, enable, disable, health, manifests }

export default TitanModuleAdminApiController