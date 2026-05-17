import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanquotes'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/titanquotes',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanquotes'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanquotes'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanquotes'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanquotes'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanquotes'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanquotes'
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
* @route '/titanquotes/my-profile'
*/
export const myProfile = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: myProfile.url(options),
    method: 'get',
})

myProfile.definition = {
    methods: ["get","head"],
    url: '/titanquotes/my-profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanquotes/my-profile'
*/
myProfile.url = (options?: RouteQueryOptions) => {
    return myProfile.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanquotes/my-profile'
*/
myProfile.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanquotes/my-profile'
*/
myProfile.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: myProfile.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanquotes/my-profile'
*/
const myProfileForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanquotes/my-profile'
*/
myProfileForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanquotes/my-profile'
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
* @see \App\Filament\TitanQuotes\Pages\QuotePipelineDashboard::__invoke
* @see app/Filament/TitanQuotes/Pages/QuotePipelineDashboard.php:7
* @route '/titanquotes/quote-pipeline-dashboard'
*/
export const quotePipelineDashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: quotePipelineDashboard.url(options),
    method: 'get',
})

quotePipelineDashboard.definition = {
    methods: ["get","head"],
    url: '/titanquotes/quote-pipeline-dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanQuotes\Pages\QuotePipelineDashboard::__invoke
* @see app/Filament/TitanQuotes/Pages/QuotePipelineDashboard.php:7
* @route '/titanquotes/quote-pipeline-dashboard'
*/
quotePipelineDashboard.url = (options?: RouteQueryOptions) => {
    return quotePipelineDashboard.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanQuotes\Pages\QuotePipelineDashboard::__invoke
* @see app/Filament/TitanQuotes/Pages/QuotePipelineDashboard.php:7
* @route '/titanquotes/quote-pipeline-dashboard'
*/
quotePipelineDashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: quotePipelineDashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Pages\QuotePipelineDashboard::__invoke
* @see app/Filament/TitanQuotes/Pages/QuotePipelineDashboard.php:7
* @route '/titanquotes/quote-pipeline-dashboard'
*/
quotePipelineDashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: quotePipelineDashboard.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanQuotes\Pages\QuotePipelineDashboard::__invoke
* @see app/Filament/TitanQuotes/Pages/QuotePipelineDashboard.php:7
* @route '/titanquotes/quote-pipeline-dashboard'
*/
const quotePipelineDashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: quotePipelineDashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Pages\QuotePipelineDashboard::__invoke
* @see app/Filament/TitanQuotes/Pages/QuotePipelineDashboard.php:7
* @route '/titanquotes/quote-pipeline-dashboard'
*/
quotePipelineDashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: quotePipelineDashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Pages\QuotePipelineDashboard::__invoke
* @see app/Filament/TitanQuotes/Pages/QuotePipelineDashboard.php:7
* @route '/titanquotes/quote-pipeline-dashboard'
*/
quotePipelineDashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: quotePipelineDashboard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

quotePipelineDashboard.form = quotePipelineDashboardForm

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanquotes/ui-studio'
*/
export const uiStudio = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: uiStudio.url(options),
    method: 'get',
})

uiStudio.definition = {
    methods: ["get","head"],
    url: '/titanquotes/ui-studio',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanquotes/ui-studio'
*/
uiStudio.url = (options?: RouteQueryOptions) => {
    return uiStudio.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanquotes/ui-studio'
*/
uiStudio.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: uiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanquotes/ui-studio'
*/
uiStudio.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: uiStudio.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanquotes/ui-studio'
*/
const uiStudioForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: uiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanquotes/ui-studio'
*/
uiStudioForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: uiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanquotes/ui-studio'
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
    quotePipelineDashboard: Object.assign(quotePipelineDashboard, quotePipelineDashboard),
    uiStudio: Object.assign(uiStudio, uiStudio),
}

export default pages