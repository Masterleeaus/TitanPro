import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\GroundZero\Pages\SettingsPage::__invoke
* @see app/Filament/GroundZero/Pages/SettingsPage.php:7
* @route '/groundzero/settings-page'
*/
const SettingsPage = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SettingsPage.url(options),
    method: 'get',
})

SettingsPage.definition = {
    methods: ["get","head"],
    url: '/groundzero/settings-page',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\GroundZero\Pages\SettingsPage::__invoke
* @see app/Filament/GroundZero/Pages/SettingsPage.php:7
* @route '/groundzero/settings-page'
*/
SettingsPage.url = (options?: RouteQueryOptions) => {
    return SettingsPage.definition.url + queryParams(options)
}

/**
* @see \App\Filament\GroundZero\Pages\SettingsPage::__invoke
* @see app/Filament/GroundZero/Pages/SettingsPage.php:7
* @route '/groundzero/settings-page'
*/
SettingsPage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SettingsPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\SettingsPage::__invoke
* @see app/Filament/GroundZero/Pages/SettingsPage.php:7
* @route '/groundzero/settings-page'
*/
SettingsPage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: SettingsPage.url(options),
    method: 'head',
})

/**
* @see \App\Filament\GroundZero\Pages\SettingsPage::__invoke
* @see app/Filament/GroundZero/Pages/SettingsPage.php:7
* @route '/groundzero/settings-page'
*/
const SettingsPageForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SettingsPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\SettingsPage::__invoke
* @see app/Filament/GroundZero/Pages/SettingsPage.php:7
* @route '/groundzero/settings-page'
*/
SettingsPageForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SettingsPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\SettingsPage::__invoke
* @see app/Filament/GroundZero/Pages/SettingsPage.php:7
* @route '/groundzero/settings-page'
*/
SettingsPageForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: SettingsPage.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

SettingsPage.form = SettingsPageForm

export default SettingsPage