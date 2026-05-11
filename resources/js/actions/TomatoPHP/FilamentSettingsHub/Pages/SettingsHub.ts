import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SettingsHub::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SettingsHub.php:7
* @route '/titanpro/settings-hub'
*/
const SettingsHub = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SettingsHub.url(options),
    method: 'get',
})

SettingsHub.definition = {
    methods: ["get","head"],
    url: '/titanpro/settings-hub',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SettingsHub::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SettingsHub.php:7
* @route '/titanpro/settings-hub'
*/
SettingsHub.url = (options?: RouteQueryOptions) => {
    return SettingsHub.definition.url + queryParams(options)
}

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SettingsHub::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SettingsHub.php:7
* @route '/titanpro/settings-hub'
*/
SettingsHub.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SettingsHub.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SettingsHub::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SettingsHub.php:7
* @route '/titanpro/settings-hub'
*/
SettingsHub.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: SettingsHub.url(options),
    method: 'head',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SettingsHub::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SettingsHub.php:7
* @route '/titanpro/settings-hub'
*/
const SettingsHubForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SettingsHub.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SettingsHub::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SettingsHub.php:7
* @route '/titanpro/settings-hub'
*/
SettingsHubForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SettingsHub.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SettingsHub::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SettingsHub.php:7
* @route '/titanpro/settings-hub'
*/
SettingsHubForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SettingsHub.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

SettingsHub.form = SettingsHubForm

export default SettingsHub