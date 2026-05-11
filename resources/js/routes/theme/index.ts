import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import preview16e44b from './preview'
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

/**
* @see \App\Http\Controllers\Platform\ThemeImportController::__invoke
* @see app/Http/Controllers/Platform/ThemeImportController.php:22
* @route '/theme/import/{token}'
*/
export const importMethod = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: importMethod.url(args, options),
    method: 'get',
})

importMethod.definition = {
    methods: ["get","head"],
    url: '/theme/import/{token}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Platform\ThemeImportController::__invoke
* @see app/Http/Controllers/Platform/ThemeImportController.php:22
* @route '/theme/import/{token}'
*/
importMethod.url = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return importMethod.definition.url
            .replace('{token}', parsedArgs.token.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Platform\ThemeImportController::__invoke
* @see app/Http/Controllers/Platform/ThemeImportController.php:22
* @route '/theme/import/{token}'
*/
importMethod.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: importMethod.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\ThemeImportController::__invoke
* @see app/Http/Controllers/Platform/ThemeImportController.php:22
* @route '/theme/import/{token}'
*/
importMethod.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: importMethod.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Platform\ThemeImportController::__invoke
* @see app/Http/Controllers/Platform/ThemeImportController.php:22
* @route '/theme/import/{token}'
*/
const importMethodForm = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: importMethod.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\ThemeImportController::__invoke
* @see app/Http/Controllers/Platform/ThemeImportController.php:22
* @route '/theme/import/{token}'
*/
importMethodForm.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: importMethod.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\ThemeImportController::__invoke
* @see app/Http/Controllers/Platform/ThemeImportController.php:22
* @route '/theme/import/{token}'
*/
importMethodForm.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: importMethod.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

importMethod.form = importMethodForm

const theme = {
    preview: Object.assign(preview, preview16e44b),
    screenshot: Object.assign(screenshot, screenshot),
    import: Object.assign(importMethod, importMethod),
}

export default theme