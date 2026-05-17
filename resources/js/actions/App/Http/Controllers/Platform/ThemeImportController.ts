import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Platform\ThemeImportController::__invoke
* @see app/Http/Controllers/Platform/ThemeImportController.php:22
* @route '/theme/import/{token}'
*/
const ThemeImportController = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ThemeImportController.url(args, options),
    method: 'get',
})

ThemeImportController.definition = {
    methods: ["get","head"],
    url: '/theme/import/{token}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Platform\ThemeImportController::__invoke
* @see app/Http/Controllers/Platform/ThemeImportController.php:22
* @route '/theme/import/{token}'
*/
ThemeImportController.url = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { token: args }
    }

    if (Array.isArray(args)) {
        args = {
            token: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        token: args.token,
    }

    return ThemeImportController.definition.url
            .replace('{token}', parsedArgs.token.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Platform\ThemeImportController::__invoke
* @see app/Http/Controllers/Platform/ThemeImportController.php:22
* @route '/theme/import/{token}'
*/
ThemeImportController.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ThemeImportController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\ThemeImportController::__invoke
* @see app/Http/Controllers/Platform/ThemeImportController.php:22
* @route '/theme/import/{token}'
*/
ThemeImportController.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ThemeImportController.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Platform\ThemeImportController::__invoke
* @see app/Http/Controllers/Platform/ThemeImportController.php:22
* @route '/theme/import/{token}'
*/
const ThemeImportControllerForm = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ThemeImportController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\ThemeImportController::__invoke
* @see app/Http/Controllers/Platform/ThemeImportController.php:22
* @route '/theme/import/{token}'
*/
ThemeImportControllerForm.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ThemeImportController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\ThemeImportController::__invoke
* @see app/Http/Controllers/Platform/ThemeImportController.php:22
* @route '/theme/import/{token}'
*/
ThemeImportControllerForm.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ThemeImportController.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ThemeImportController.form = ThemeImportControllerForm

export default ThemeImportController