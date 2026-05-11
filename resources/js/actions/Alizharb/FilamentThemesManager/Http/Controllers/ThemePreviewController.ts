import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::preview
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:20
* @route '/theme-preview/{slug}'
*/
export const preview = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: preview.url(args, options),
    method: 'get',
})

preview.definition = {
    methods: ["get","head"],
    url: '/theme-preview/{slug}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::preview
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:20
* @route '/theme-preview/{slug}'
*/
preview.url = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return preview.definition.url
            .replace('{slug}', parsedArgs.slug.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::preview
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:20
* @route '/theme-preview/{slug}'
*/
preview.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: preview.url(args, options),
    method: 'get',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::preview
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:20
* @route '/theme-preview/{slug}'
*/
preview.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: preview.url(args, options),
    method: 'head',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::preview
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:20
* @route '/theme-preview/{slug}'
*/
const previewForm = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: preview.url(args, options),
    method: 'get',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::preview
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:20
* @route '/theme-preview/{slug}'
*/
previewForm.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: preview.url(args, options),
    method: 'get',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::preview
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:20
* @route '/theme-preview/{slug}'
*/
previewForm.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: preview.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

preview.form = previewForm

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::exitPreview
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:56
* @route '/theme-preview/exit'
*/
export const exitPreview = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exitPreview.url(options),
    method: 'get',
})

exitPreview.definition = {
    methods: ["get","head"],
    url: '/theme-preview/exit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::exitPreview
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:56
* @route '/theme-preview/exit'
*/
exitPreview.url = (options?: RouteQueryOptions) => {
    return exitPreview.definition.url + queryParams(options)
}

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::exitPreview
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:56
* @route '/theme-preview/exit'
*/
exitPreview.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exitPreview.url(options),
    method: 'get',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::exitPreview
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:56
* @route '/theme-preview/exit'
*/
exitPreview.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exitPreview.url(options),
    method: 'head',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::exitPreview
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:56
* @route '/theme-preview/exit'
*/
const exitPreviewForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exitPreview.url(options),
    method: 'get',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::exitPreview
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:56
* @route '/theme-preview/exit'
*/
exitPreviewForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exitPreview.url(options),
    method: 'get',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::exitPreview
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:56
* @route '/theme-preview/exit'
*/
exitPreviewForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exitPreview.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

exitPreview.form = exitPreviewForm

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

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::screenshot
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:96
* @route '/theme-preview/{slug}/screenshot'
*/
export const screenshot = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: screenshot.url(args, options),
    method: 'get',
})

screenshot.definition = {
    methods: ["get","head"],
    url: '/theme-preview/{slug}/screenshot',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::screenshot
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:96
* @route '/theme-preview/{slug}/screenshot'
*/
screenshot.url = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return screenshot.definition.url
            .replace('{slug}', parsedArgs.slug.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::screenshot
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:96
* @route '/theme-preview/{slug}/screenshot'
*/
screenshot.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: screenshot.url(args, options),
    method: 'get',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::screenshot
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:96
* @route '/theme-preview/{slug}/screenshot'
*/
screenshot.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: screenshot.url(args, options),
    method: 'head',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::screenshot
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:96
* @route '/theme-preview/{slug}/screenshot'
*/
const screenshotForm = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: screenshot.url(args, options),
    method: 'get',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::screenshot
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:96
* @route '/theme-preview/{slug}/screenshot'
*/
screenshotForm.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: screenshot.url(args, options),
    method: 'get',
})

/**
* @see \Alizharb\FilamentThemesManager\Http\Controllers\ThemePreviewController::screenshot
* @see vendor/alizharb/filament-themes-manager/src/Http/Controllers/ThemePreviewController.php:96
* @route '/theme-preview/{slug}/screenshot'
*/
screenshotForm.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: screenshot.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

screenshot.form = screenshotForm

const ThemePreviewController = { preview, exitPreview, activate, screenshot }

export default ThemePreviewController