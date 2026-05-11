import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/admin'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/admin',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/admin'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/admin'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/admin'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/admin'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/admin'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/admin'
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
* @route '/admin/c-r-m-core-overview'
*/
export const cRMCoreOverview = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: cRMCoreOverview.url(options),
    method: 'get',
})

cRMCoreOverview.definition = {
    methods: ["get","head"],
    url: '/admin/c-r-m-core-overview',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/admin/c-r-m-core-overview'
*/
cRMCoreOverview.url = (options?: RouteQueryOptions) => {
    return cRMCoreOverview.definition.url + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/admin/c-r-m-core-overview'
*/
cRMCoreOverview.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: cRMCoreOverview.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/admin/c-r-m-core-overview'
*/
cRMCoreOverview.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: cRMCoreOverview.url(options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/admin/c-r-m-core-overview'
*/
const cRMCoreOverviewForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: cRMCoreOverview.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/admin/c-r-m-core-overview'
*/
cRMCoreOverviewForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: cRMCoreOverview.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/admin/c-r-m-core-overview'
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
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/admin/operations-reports'
*/
export const operationsReports = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: operationsReports.url(options),
    method: 'get',
})

operationsReports.definition = {
    methods: ["get","head"],
    url: '/admin/operations-reports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/admin/operations-reports'
*/
operationsReports.url = (options?: RouteQueryOptions) => {
    return operationsReports.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/admin/operations-reports'
*/
operationsReports.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: operationsReports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/admin/operations-reports'
*/
operationsReports.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: operationsReports.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/admin/operations-reports'
*/
const operationsReportsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: operationsReports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/admin/operations-reports'
*/
operationsReportsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: operationsReports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/admin/operations-reports'
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
* @route '/admin/reports'
*/
export const reports = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reports.url(options),
    method: 'get',
})

reports.definition = {
    methods: ["get","head"],
    url: '/admin/reports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/admin/reports'
*/
reports.url = (options?: RouteQueryOptions) => {
    return reports.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/admin/reports'
*/
reports.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/admin/reports'
*/
reports.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: reports.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/admin/reports'
*/
const reportsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/admin/reports'
*/
reportsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/admin/reports'
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
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/admin/site-settings'
*/
export const siteSettings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: siteSettings.url(options),
    method: 'get',
})

siteSettings.definition = {
    methods: ["get","head"],
    url: '/admin/site-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/admin/site-settings'
*/
siteSettings.url = (options?: RouteQueryOptions) => {
    return siteSettings.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/admin/site-settings'
*/
siteSettings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: siteSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/admin/site-settings'
*/
siteSettings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: siteSettings.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/admin/site-settings'
*/
const siteSettingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: siteSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/admin/site-settings'
*/
siteSettingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: siteSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\SiteSettings::__invoke
* @see app/Filament/Pages/SiteSettings.php:7
* @route '/admin/site-settings'
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

const pages = {
    dashboard: Object.assign(dashboard, dashboard),
    cRMCoreOverview: Object.assign(cRMCoreOverview, cRMCoreOverview),
    operationsReports: Object.assign(operationsReports, operationsReports),
    reports: Object.assign(reports, reports),
    siteSettings: Object.assign(siteSettings, siteSettings),
}

export default pages