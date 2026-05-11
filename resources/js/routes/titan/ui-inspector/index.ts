import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\UiInspectorController::index
* @see app/Http/Controllers/UiInspectorController.php:42
* @route '/titan/ui-inspector/overrides'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/titan/ui-inspector/overrides',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\UiInspectorController::index
* @see app/Http/Controllers/UiInspectorController.php:42
* @route '/titan/ui-inspector/overrides'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\UiInspectorController::index
* @see app/Http/Controllers/UiInspectorController.php:42
* @route '/titan/ui-inspector/overrides'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UiInspectorController::index
* @see app/Http/Controllers/UiInspectorController.php:42
* @route '/titan/ui-inspector/overrides'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\UiInspectorController::index
* @see app/Http/Controllers/UiInspectorController.php:42
* @route '/titan/ui-inspector/overrides'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UiInspectorController::index
* @see app/Http/Controllers/UiInspectorController.php:42
* @route '/titan/ui-inspector/overrides'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UiInspectorController::index
* @see app/Http/Controllers/UiInspectorController.php:42
* @route '/titan/ui-inspector/overrides'
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
* @see \App\Http\Controllers\UiInspectorController::upsert
* @see app/Http/Controllers/UiInspectorController.php:74
* @route '/titan/ui-inspector/overrides'
*/
export const upsert = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: upsert.url(options),
    method: 'post',
})

upsert.definition = {
    methods: ["post"],
    url: '/titan/ui-inspector/overrides',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\UiInspectorController::upsert
* @see app/Http/Controllers/UiInspectorController.php:74
* @route '/titan/ui-inspector/overrides'
*/
upsert.url = (options?: RouteQueryOptions) => {
    return upsert.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\UiInspectorController::upsert
* @see app/Http/Controllers/UiInspectorController.php:74
* @route '/titan/ui-inspector/overrides'
*/
upsert.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: upsert.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\UiInspectorController::upsert
* @see app/Http/Controllers/UiInspectorController.php:74
* @route '/titan/ui-inspector/overrides'
*/
const upsertForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: upsert.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\UiInspectorController::upsert
* @see app/Http/Controllers/UiInspectorController.php:74
* @route '/titan/ui-inspector/overrides'
*/
upsertForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: upsert.url(options),
    method: 'post',
})

upsert.form = upsertForm

/**
* @see \App\Http\Controllers\UiInspectorController::exportMethod
* @see app/Http/Controllers/UiInspectorController.php:50
* @route '/titan/ui-inspector/export'
*/
export const exportMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/titan/ui-inspector/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\UiInspectorController::exportMethod
* @see app/Http/Controllers/UiInspectorController.php:50
* @route '/titan/ui-inspector/export'
*/
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\UiInspectorController::exportMethod
* @see app/Http/Controllers/UiInspectorController.php:50
* @route '/titan/ui-inspector/export'
*/
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UiInspectorController::exportMethod
* @see app/Http/Controllers/UiInspectorController.php:50
* @route '/titan/ui-inspector/export'
*/
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\UiInspectorController::exportMethod
* @see app/Http/Controllers/UiInspectorController.php:50
* @route '/titan/ui-inspector/export'
*/
const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportMethod.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UiInspectorController::exportMethod
* @see app/Http/Controllers/UiInspectorController.php:50
* @route '/titan/ui-inspector/export'
*/
exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportMethod.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UiInspectorController::exportMethod
* @see app/Http/Controllers/UiInspectorController.php:50
* @route '/titan/ui-inspector/export'
*/
exportMethodForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportMethod.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

exportMethod.form = exportMethodForm

/**
* @see \App\Http\Controllers\UiInspectorController::importMethod
* @see app/Http/Controllers/UiInspectorController.php:105
* @route '/titan/ui-inspector/import'
*/
export const importMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/titan/ui-inspector/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\UiInspectorController::importMethod
* @see app/Http/Controllers/UiInspectorController.php:105
* @route '/titan/ui-inspector/import'
*/
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\UiInspectorController::importMethod
* @see app/Http/Controllers/UiInspectorController.php:105
* @route '/titan/ui-inspector/import'
*/
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\UiInspectorController::importMethod
* @see app/Http/Controllers/UiInspectorController.php:105
* @route '/titan/ui-inspector/import'
*/
const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\UiInspectorController::importMethod
* @see app/Http/Controllers/UiInspectorController.php:105
* @route '/titan/ui-inspector/import'
*/
importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

importMethod.form = importMethodForm

/**
* @see \App\Http\Controllers\UiInspectorController::reset
* @see app/Http/Controllers/UiInspectorController.php:135
* @route '/titan/ui-inspector/overrides/{key}'
*/
export const reset = (args: { key: string | number } | [key: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: reset.url(args, options),
    method: 'delete',
})

reset.definition = {
    methods: ["delete"],
    url: '/titan/ui-inspector/overrides/{key}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\UiInspectorController::reset
* @see app/Http/Controllers/UiInspectorController.php:135
* @route '/titan/ui-inspector/overrides/{key}'
*/
reset.url = (args: { key: string | number } | [key: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { key: args }
    }

    if (Array.isArray(args)) {
        args = {
            key: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        key: args.key,
    }

    return reset.definition.url
            .replace('{key}', parsedArgs.key.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\UiInspectorController::reset
* @see app/Http/Controllers/UiInspectorController.php:135
* @route '/titan/ui-inspector/overrides/{key}'
*/
reset.delete = (args: { key: string | number } | [key: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: reset.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\UiInspectorController::reset
* @see app/Http/Controllers/UiInspectorController.php:135
* @route '/titan/ui-inspector/overrides/{key}'
*/
const resetForm = (args: { key: string | number } | [key: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: reset.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\UiInspectorController::reset
* @see app/Http/Controllers/UiInspectorController.php:135
* @route '/titan/ui-inspector/overrides/{key}'
*/
resetForm.delete = (args: { key: string | number } | [key: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: reset.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

reset.form = resetForm

/**
* @see \App\Http\Controllers\UiInspectorController::resetAll
* @see app/Http/Controllers/UiInspectorController.php:143
* @route '/titan/ui-inspector/overrides'
*/
export const resetAll = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: resetAll.url(options),
    method: 'delete',
})

resetAll.definition = {
    methods: ["delete"],
    url: '/titan/ui-inspector/overrides',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\UiInspectorController::resetAll
* @see app/Http/Controllers/UiInspectorController.php:143
* @route '/titan/ui-inspector/overrides'
*/
resetAll.url = (options?: RouteQueryOptions) => {
    return resetAll.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\UiInspectorController::resetAll
* @see app/Http/Controllers/UiInspectorController.php:143
* @route '/titan/ui-inspector/overrides'
*/
resetAll.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: resetAll.url(options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\UiInspectorController::resetAll
* @see app/Http/Controllers/UiInspectorController.php:143
* @route '/titan/ui-inspector/overrides'
*/
const resetAllForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: resetAll.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\UiInspectorController::resetAll
* @see app/Http/Controllers/UiInspectorController.php:143
* @route '/titan/ui-inspector/overrides'
*/
resetAllForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: resetAll.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

resetAll.form = resetAllForm

const uiInspector = {
    index: Object.assign(index, index),
    upsert: Object.assign(upsert, upsert),
    export: Object.assign(exportMethod, exportMethod),
    import: Object.assign(importMethod, importMethod),
    reset: Object.assign(reset, reset),
    resetAll: Object.assign(resetAll, resetAll),
}

export default uiInspector