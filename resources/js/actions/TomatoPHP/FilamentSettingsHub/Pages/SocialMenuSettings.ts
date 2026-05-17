import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SocialMenuSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SocialMenuSettings.php:7
* @route '/titanpro/social-menu-settings'
*/
const SocialMenuSettings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SocialMenuSettings.url(options),
    method: 'get',
})

SocialMenuSettings.definition = {
    methods: ["get","head"],
    url: '/titanpro/social-menu-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SocialMenuSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SocialMenuSettings.php:7
* @route '/titanpro/social-menu-settings'
*/
SocialMenuSettings.url = (options?: RouteQueryOptions) => {
    return SocialMenuSettings.definition.url + queryParams(options)
}

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SocialMenuSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SocialMenuSettings.php:7
* @route '/titanpro/social-menu-settings'
*/
SocialMenuSettings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SocialMenuSettings.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SocialMenuSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SocialMenuSettings.php:7
* @route '/titanpro/social-menu-settings'
*/
SocialMenuSettings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: SocialMenuSettings.url(options),
    method: 'head',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SocialMenuSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SocialMenuSettings.php:7
* @route '/titanpro/social-menu-settings'
*/
const SocialMenuSettingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SocialMenuSettings.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SocialMenuSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SocialMenuSettings.php:7
* @route '/titanpro/social-menu-settings'
*/
SocialMenuSettingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SocialMenuSettings.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SocialMenuSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SocialMenuSettings.php:7
* @route '/titanpro/social-menu-settings'
*/
SocialMenuSettingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SocialMenuSettings.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

SocialMenuSettings.form = SocialMenuSettingsForm

export default SocialMenuSettings