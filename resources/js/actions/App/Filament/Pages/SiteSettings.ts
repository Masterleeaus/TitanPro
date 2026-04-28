import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/admin/site-settings'
*/
const SiteSettings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SiteSettings.url(options),
    method: 'get',
})

SiteSettings.definition = {
    methods: ["get","head"],
    url: '/admin/site-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/admin/site-settings'
*/
SiteSettings.url = (options?: RouteQueryOptions) => {
    return SiteSettings.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/admin/site-settings'
*/
SiteSettings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SiteSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/admin/site-settings'
*/
SiteSettings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: SiteSettings.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/admin/site-settings'
*/
const SiteSettingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SiteSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/admin/site-settings'
*/
SiteSettingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SiteSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/admin/site-settings'
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