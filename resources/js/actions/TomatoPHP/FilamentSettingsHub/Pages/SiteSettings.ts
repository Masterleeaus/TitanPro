import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SiteSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SiteSettings.php:7
* @route '/titanpro/site-settings'
*/
const SiteSettings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SiteSettings.url(options),
    method: 'get',
})

SiteSettings.definition = {
    methods: ["get","head"],
    url: '/titanpro/site-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SiteSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SiteSettings.php:7
* @route '/titanpro/site-settings'
*/
SiteSettings.url = (options?: RouteQueryOptions) => {
    return SiteSettings.definition.url + queryParams(options)
}

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SiteSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SiteSettings.php:7
* @route '/titanpro/site-settings'
*/
SiteSettings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SiteSettings.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SiteSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SiteSettings.php:7
* @route '/titanpro/site-settings'
*/
SiteSettings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: SiteSettings.url(options),
    method: 'head',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SiteSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SiteSettings.php:7
* @route '/titanpro/site-settings'
*/
const SiteSettingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SiteSettings.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SiteSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SiteSettings.php:7
* @route '/titanpro/site-settings'
*/
SiteSettingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SiteSettings.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SiteSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SiteSettings.php:7
* @route '/titanpro/site-settings'
*/
SiteSettingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SiteSettings.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

SiteSettings.form = SiteSettingsForm

export default SiteSettings