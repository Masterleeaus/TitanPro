import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::exit
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:56
* @route '/theme-preview/exit'
*/
export const exit = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exit.url(options),
    method: 'get',
})

exit.definition = {
    methods: ["get","head"],
    url: '/theme-preview/exit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::exit
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:56
* @route '/theme-preview/exit'
*/
exit.url = (options?: RouteQueryOptions) => {
    return exit.definition.url + queryParams(options)
}

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::exit
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:56
* @route '/theme-preview/exit'
*/
exit.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exit.url(options),
    method: 'get',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::exit
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:56
* @route '/theme-preview/exit'
*/
exit.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exit.url(options),
    method: 'head',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::exit
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:56
* @route '/theme-preview/exit'
*/
const exitForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exit.url(options),
    method: 'get',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::exit
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:56
* @route '/theme-preview/exit'
*/
exitForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exit.url(options),
    method: 'get',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::exit
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:56
* @route '/theme-preview/exit'
*/
exitForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exit.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

exit.form = exitForm

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::activate
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:75
* @route '/theme-preview/{slug}/activate'
*/
export const activate = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: activate.url(args, options),
    method: 'post',
})

activate.definition = {
    methods: ["post"],
    url: '/theme-preview/{slug}/activate',
} satisfies RouteDefinition<["post"]>

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::activate
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:75
* @route '/theme-preview/{slug}/activate'
*/
activate.url = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { slug: args }
    }

    if (Array.isArray(args)) {
        args = {
            slug: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        slug: args.slug,
    }

    return activate.definition.url
            .replace('{slug}', parsedArgs.slug.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::activate
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:75
* @route '/theme-preview/{slug}/activate'
*/
activate.post = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: activate.url(args, options),
    method: 'post',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::activate
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:75
* @route '/theme-preview/{slug}/activate'
*/
const activateForm = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: activate.url(args, options),
    method: 'post',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::activate
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:75
* @route '/theme-preview/{slug}/activate'
*/
activateForm.post = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: activate.url(args, options),
    method: 'post',
})

activate.form = activateForm

const preview = {
    exit: Object.assign(exit, exit),
    activate: Object.assign(activate, activate),
}

export default preview