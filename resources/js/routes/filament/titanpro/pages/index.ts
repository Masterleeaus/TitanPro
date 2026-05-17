import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanpro'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/titanpro',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanpro'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanpro'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanpro'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanpro'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanpro'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanpro'
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
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/titanpro/c-r-m-core-overview'
*/
export const cRMCoreOverview = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: cRMCoreOverview.url(options),
    method: 'get',
})

cRMCoreOverview.definition = {
    methods: ["get","head"],
    url: '/titanpro/c-r-m-core-overview',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/titanpro/c-r-m-core-overview'
*/
cRMCoreOverview.url = (options?: RouteQueryOptions) => {
    return cRMCoreOverview.definition.url + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/titanpro/c-r-m-core-overview'
*/
cRMCoreOverview.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: cRMCoreOverview.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/titanpro/c-r-m-core-overview'
*/
cRMCoreOverview.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: cRMCoreOverview.url(options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/titanpro/c-r-m-core-overview'
*/
const cRMCoreOverviewForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: cRMCoreOverview.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/titanpro/c-r-m-core-overview'
*/
cRMCoreOverviewForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: cRMCoreOverview.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/titanpro/c-r-m-core-overview'
*/
cRMCoreOverviewForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: cRMCoreOverview.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

cRMCoreOverview.form = cRMCoreOverviewForm

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanpro/my-profile'
*/
export const myProfile = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: myProfile.url(options),
    method: 'get',
})

myProfile.definition = {
    methods: ["get","head"],
    url: '/titanpro/my-profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanpro/my-profile'
*/
myProfile.url = (options?: RouteQueryOptions) => {
    return myProfile.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanpro/my-profile'
*/
myProfile.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanpro/my-profile'
*/
myProfile.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: myProfile.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanpro/my-profile'
*/
const myProfileForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanpro/my-profile'
*/
myProfileForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanpro/my-profile'
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
* @see \NoteBrainsLab\FilamentMenuManager\Pages\MenuManagerPage::__invoke
* @see vendor/notebrainslab/filament-menu-manager/src/Pages/MenuManagerPage.php:7
* @route '/titanpro/menu-manager-page'
*/
export const menuManagerPage = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: menuManagerPage.url(options),
    method: 'get',
})

menuManagerPage.definition = {
    methods: ["get","head"],
    url: '/titanpro/menu-manager-page',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \NoteBrainsLab\FilamentMenuManager\Pages\MenuManagerPage::__invoke
* @see vendor/notebrainslab/filament-menu-manager/src/Pages/MenuManagerPage.php:7
* @route '/titanpro/menu-manager-page'
*/
menuManagerPage.url = (options?: RouteQueryOptions) => {
    return menuManagerPage.definition.url + queryParams(options)
}

/**
* @see \NoteBrainsLab\FilamentMenuManager\Pages\MenuManagerPage::__invoke
* @see vendor/notebrainslab/filament-menu-manager/src/Pages/MenuManagerPage.php:7
* @route '/titanpro/menu-manager-page'
*/
menuManagerPage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: menuManagerPage.url(options),
    method: 'get',
})

/**
* @see \NoteBrainsLab\FilamentMenuManager\Pages\MenuManagerPage::__invoke
* @see vendor/notebrainslab/filament-menu-manager/src/Pages/MenuManagerPage.php:7
* @route '/titanpro/menu-manager-page'
*/
menuManagerPage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: menuManagerPage.url(options),
    method: 'head',
})

/**
* @see \NoteBrainsLab\FilamentMenuManager\Pages\MenuManagerPage::__invoke
* @see vendor/notebrainslab/filament-menu-manager/src/Pages/MenuManagerPage.php:7
* @route '/titanpro/menu-manager-page'
*/
const menuManagerPageForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: menuManagerPage.url(options),
    method: 'get',
})

/**
* @see \NoteBrainsLab\FilamentMenuManager\Pages\MenuManagerPage::__invoke
* @see vendor/notebrainslab/filament-menu-manager/src/Pages/MenuManagerPage.php:7
* @route '/titanpro/menu-manager-page'
*/
menuManagerPageForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: menuManagerPage.url(options),
    method: 'get',
})

/**
* @see \NoteBrainsLab\FilamentMenuManager\Pages\MenuManagerPage::__invoke
* @see vendor/notebrainslab/filament-menu-manager/src/Pages/MenuManagerPage.php:7
* @route '/titanpro/menu-manager-page'
*/
menuManagerPageForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: menuManagerPage.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

menuManagerPage.form = menuManagerPageForm

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/titanpro/site-settings'
*/
export const siteSettings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: siteSettings.url(options),
    method: 'get',
})

siteSettings.definition = {
    methods: ["get","head"],
    url: '/titanpro/site-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/titanpro/site-settings'
*/
siteSettings.url = (options?: RouteQueryOptions) => {
    return siteSettings.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/titanpro/site-settings'
*/
siteSettings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: siteSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/titanpro/site-settings'
*/
siteSettings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: siteSettings.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/titanpro/site-settings'
*/
const siteSettingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: siteSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/titanpro/site-settings'
*/
siteSettingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: siteSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/titanpro/site-settings'
*/
siteSettingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: siteSettings.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

siteSettings.form = siteSettingsForm

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SocialMenuSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SocialMenuSettings.php:7
* @route '/titanpro/social-menu-settings'
*/
export const socialMenuSettings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: socialMenuSettings.url(options),
    method: 'get',
})

socialMenuSettings.definition = {
    methods: ["get","head"],
    url: '/titanpro/social-menu-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SocialMenuSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SocialMenuSettings.php:7
* @route '/titanpro/social-menu-settings'
*/
socialMenuSettings.url = (options?: RouteQueryOptions) => {
    return socialMenuSettings.definition.url + queryParams(options)
}

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SocialMenuSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SocialMenuSettings.php:7
* @route '/titanpro/social-menu-settings'
*/
socialMenuSettings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: socialMenuSettings.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SocialMenuSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SocialMenuSettings.php:7
* @route '/titanpro/social-menu-settings'
*/
socialMenuSettings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: socialMenuSettings.url(options),
    method: 'head',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SocialMenuSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SocialMenuSettings.php:7
* @route '/titanpro/social-menu-settings'
*/
const socialMenuSettingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: socialMenuSettings.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SocialMenuSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SocialMenuSettings.php:7
* @route '/titanpro/social-menu-settings'
*/
socialMenuSettingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: socialMenuSettings.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SocialMenuSettings::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SocialMenuSettings.php:7
* @route '/titanpro/social-menu-settings'
*/
socialMenuSettingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: socialMenuSettings.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

socialMenuSettings.form = socialMenuSettingsForm

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SettingsHub::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SettingsHub.php:7
* @route '/titanpro/settings-hub'
*/
export const settingsHub = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: settingsHub.url(options),
    method: 'get',
})

settingsHub.definition = {
    methods: ["get","head"],
    url: '/titanpro/settings-hub',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SettingsHub::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SettingsHub.php:7
* @route '/titanpro/settings-hub'
*/
settingsHub.url = (options?: RouteQueryOptions) => {
    return settingsHub.definition.url + queryParams(options)
}

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SettingsHub::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SettingsHub.php:7
* @route '/titanpro/settings-hub'
*/
settingsHub.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: settingsHub.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SettingsHub::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SettingsHub.php:7
* @route '/titanpro/settings-hub'
*/
settingsHub.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: settingsHub.url(options),
    method: 'head',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SettingsHub::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SettingsHub.php:7
* @route '/titanpro/settings-hub'
*/
const settingsHubForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: settingsHub.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SettingsHub::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SettingsHub.php:7
* @route '/titanpro/settings-hub'
*/
settingsHubForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: settingsHub.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentSettingsHub\Pages\SettingsHub::__invoke
* @see vendor/tomatophp/filament-settings-hub/src/Pages/SettingsHub.php:7
* @route '/titanpro/settings-hub'
*/
settingsHubForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: settingsHub.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

settingsHub.form = settingsHubForm

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/titanpro/operations-reports'
*/
export const operationsReports = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: operationsReports.url(options),
    method: 'get',
})

operationsReports.definition = {
    methods: ["get","head"],
    url: '/titanpro/operations-reports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/titanpro/operations-reports'
*/
operationsReports.url = (options?: RouteQueryOptions) => {
    return operationsReports.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/titanpro/operations-reports'
*/
operationsReports.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: operationsReports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/titanpro/operations-reports'
*/
operationsReports.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: operationsReports.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/titanpro/operations-reports'
*/
const operationsReportsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: operationsReports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/titanpro/operations-reports'
*/
operationsReportsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: operationsReports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/titanpro/operations-reports'
*/
operationsReportsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: operationsReports.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

operationsReports.form = operationsReportsForm

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/titanpro/reports'
*/
export const reports = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reports.url(options),
    method: 'get',
})

reports.definition = {
    methods: ["get","head"],
    url: '/titanpro/reports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/titanpro/reports'
*/
reports.url = (options?: RouteQueryOptions) => {
    return reports.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/titanpro/reports'
*/
reports.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/titanpro/reports'
*/
reports.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: reports.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/titanpro/reports'
*/
const reportsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/titanpro/reports'
*/
reportsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/titanpro/reports'
*/
reportsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reports.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

reports.form = reportsForm

/**
* @see \App\Filament\Pages\ThemeManager::__invoke
* @see app/Filament/Pages/ThemeManager.php:7
* @route '/titanpro/theme-manager'
*/
export const themeManager = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: themeManager.url(options),
    method: 'get',
})

themeManager.definition = {
    methods: ["get","head"],
    url: '/titanpro/theme-manager',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\ThemeManager::__invoke
* @see app/Filament/Pages/ThemeManager.php:7
* @route '/titanpro/theme-manager'
*/
themeManager.url = (options?: RouteQueryOptions) => {
    return themeManager.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\ThemeManager::__invoke
* @see app/Filament/Pages/ThemeManager.php:7
* @route '/titanpro/theme-manager'
*/
themeManager.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: themeManager.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\ThemeManager::__invoke
* @see app/Filament/Pages/ThemeManager.php:7
* @route '/titanpro/theme-manager'
*/
themeManager.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: themeManager.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\ThemeManager::__invoke
* @see app/Filament/Pages/ThemeManager.php:7
* @route '/titanpro/theme-manager'
*/
const themeManagerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: themeManager.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\ThemeManager::__invoke
* @see app/Filament/Pages/ThemeManager.php:7
* @route '/titanpro/theme-manager'
*/
themeManagerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: themeManager.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\ThemeManager::__invoke
* @see app/Filament/Pages/ThemeManager.php:7
* @route '/titanpro/theme-manager'
*/
themeManagerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: themeManager.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

themeManager.form = themeManagerForm

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanpro/ui-studio'
*/
export const uiStudio = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: uiStudio.url(options),
    method: 'get',
})

uiStudio.definition = {
    methods: ["get","head"],
    url: '/titanpro/ui-studio',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanpro/ui-studio'
*/
uiStudio.url = (options?: RouteQueryOptions) => {
    return uiStudio.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanpro/ui-studio'
*/
uiStudio.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: uiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanpro/ui-studio'
*/
uiStudio.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: uiStudio.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanpro/ui-studio'
*/
const uiStudioForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: uiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanpro/ui-studio'
*/
uiStudioForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: uiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanpro/ui-studio'
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
    cRMCoreOverview: Object.assign(cRMCoreOverview, cRMCoreOverview),
    myProfile: Object.assign(myProfile, myProfile),
    menuManagerPage: Object.assign(menuManagerPage, menuManagerPage),
    siteSettings: Object.assign(siteSettings, siteSettings),
    socialMenuSettings: Object.assign(socialMenuSettings, socialMenuSettings),
    settingsHub: Object.assign(settingsHub, settingsHub),
    operationsReports: Object.assign(operationsReports, operationsReports),
    reports: Object.assign(reports, reports),
    themeManager: Object.assign(themeManager, themeManager),
    uiStudio: Object.assign(uiStudio, uiStudio),
}

export default pages