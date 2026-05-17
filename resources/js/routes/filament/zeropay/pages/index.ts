import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Filament\ZeroPay\Pages\Dashboard::__invoke
* @see app/Filament/ZeroPay/Pages/Dashboard.php:7
* @route '/zeropay'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/zeropay',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroPay\Pages\Dashboard::__invoke
* @see app/Filament/ZeroPay/Pages/Dashboard.php:7
* @route '/zeropay'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Filament\ZeroPay\Pages\Dashboard::__invoke
* @see app/Filament/ZeroPay/Pages/Dashboard.php:7
* @route '/zeropay'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Pages\Dashboard::__invoke
* @see app/Filament/ZeroPay/Pages/Dashboard.php:7
* @route '/zeropay'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Filament\ZeroPay\Pages\Dashboard::__invoke
* @see app/Filament/ZeroPay/Pages/Dashboard.php:7
* @route '/zeropay'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Pages\Dashboard::__invoke
* @see app/Filament/ZeroPay/Pages/Dashboard.php:7
* @route '/zeropay'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Pages\Dashboard::__invoke
* @see app/Filament/ZeroPay/Pages/Dashboard.php:7
* @route '/zeropay'
*/
dashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

dashboard.form = dashboardForm

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zeropay/my-profile'
*/
export const myProfile = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: myProfile.url(options),
    method: 'get',
})

myProfile.definition = {
    methods: ["get","head"],
    url: '/zeropay/my-profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zeropay/my-profile'
*/
myProfile.url = (options?: RouteQueryOptions) => {
    return myProfile.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zeropay/my-profile'
*/
myProfile.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zeropay/my-profile'
*/
myProfile.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: myProfile.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zeropay/my-profile'
*/
const myProfileForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zeropay/my-profile'
*/
myProfileForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zeropay/my-profile'
*/
myProfileForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: myProfile.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

myProfile.form = myProfileForm

/**
* @see \App\Filament\ZeroPay\Pages\StripeSettings::__invoke
* @see app/Filament/ZeroPay/Pages/StripeSettings.php:7
* @route '/zeropay/stripe-settings'
*/
export const stripeSettings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: stripeSettings.url(options),
    method: 'get',
})

stripeSettings.definition = {
    methods: ["get","head"],
    url: '/zeropay/stripe-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroPay\Pages\StripeSettings::__invoke
* @see app/Filament/ZeroPay/Pages/StripeSettings.php:7
* @route '/zeropay/stripe-settings'
*/
stripeSettings.url = (options?: RouteQueryOptions) => {
    return stripeSettings.definition.url + queryParams(options)
}

/**
* @see \App\Filament\ZeroPay\Pages\StripeSettings::__invoke
* @see app/Filament/ZeroPay/Pages/StripeSettings.php:7
* @route '/zeropay/stripe-settings'
*/
stripeSettings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: stripeSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Pages\StripeSettings::__invoke
* @see app/Filament/ZeroPay/Pages/StripeSettings.php:7
* @route '/zeropay/stripe-settings'
*/
stripeSettings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: stripeSettings.url(options),
    method: 'head',
})

/**
* @see \App\Filament\ZeroPay\Pages\StripeSettings::__invoke
* @see app/Filament/ZeroPay/Pages/StripeSettings.php:7
* @route '/zeropay/stripe-settings'
*/
const stripeSettingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: stripeSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Pages\StripeSettings::__invoke
* @see app/Filament/ZeroPay/Pages/StripeSettings.php:7
* @route '/zeropay/stripe-settings'
*/
stripeSettingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: stripeSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Pages\StripeSettings::__invoke
* @see app/Filament/ZeroPay/Pages/StripeSettings.php:7
* @route '/zeropay/stripe-settings'
*/
stripeSettingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: stripeSettings.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

stripeSettings.form = stripeSettingsForm

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/zeropay/ui-studio'
*/
export const uiStudio = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: uiStudio.url(options),
    method: 'get',
})

uiStudio.definition = {
    methods: ["get","head"],
    url: '/zeropay/ui-studio',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/zeropay/ui-studio'
*/
uiStudio.url = (options?: RouteQueryOptions) => {
    return uiStudio.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/zeropay/ui-studio'
*/
uiStudio.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: uiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/zeropay/ui-studio'
*/
uiStudio.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: uiStudio.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/zeropay/ui-studio'
*/
const uiStudioForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: uiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/zeropay/ui-studio'
*/
uiStudioForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: uiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/zeropay/ui-studio'
*/
uiStudioForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: uiStudio.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

uiStudio.form = uiStudioForm

const pages = {
    dashboard: Object.assign(dashboard, dashboard),
    myProfile: Object.assign(myProfile, myProfile),
    stripeSettings: Object.assign(stripeSettings, stripeSettings),
    uiStudio: Object.assign(uiStudio, uiStudio),
}

export default pages