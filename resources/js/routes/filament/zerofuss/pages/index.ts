import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Filament\ZeroFuss\Pages\Dashboard::__invoke
* @see app/Filament/ZeroFuss/Pages/Dashboard.php:7
* @route '/zerofuss'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/zerofuss',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroFuss\Pages\Dashboard::__invoke
* @see app/Filament/ZeroFuss/Pages/Dashboard.php:7
* @route '/zerofuss'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Filament\ZeroFuss\Pages\Dashboard::__invoke
* @see app/Filament/ZeroFuss/Pages/Dashboard.php:7
* @route '/zerofuss'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroFuss\Pages\Dashboard::__invoke
* @see app/Filament/ZeroFuss/Pages/Dashboard.php:7
* @route '/zerofuss'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Filament\ZeroFuss\Pages\Dashboard::__invoke
* @see app/Filament/ZeroFuss/Pages/Dashboard.php:7
* @route '/zerofuss'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroFuss\Pages\Dashboard::__invoke
* @see app/Filament/ZeroFuss/Pages/Dashboard.php:7
* @route '/zerofuss'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroFuss\Pages\Dashboard::__invoke
* @see app/Filament/ZeroFuss/Pages/Dashboard.php:7
* @route '/zerofuss'
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
* @route '/zerofuss/my-profile'
*/
export const myProfile = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: myProfile.url(options),
    method: 'get',
})

myProfile.definition = {
    methods: ["get","head"],
    url: '/zerofuss/my-profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zerofuss/my-profile'
*/
myProfile.url = (options?: RouteQueryOptions) => {
    return myProfile.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zerofuss/my-profile'
*/
myProfile.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zerofuss/my-profile'
*/
myProfile.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: myProfile.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zerofuss/my-profile'
*/
const myProfileForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zerofuss/my-profile'
*/
myProfileForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zerofuss/my-profile'
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

const pages = {
    dashboard: Object.assign(dashboard, dashboard),
    myProfile: Object.assign(myProfile, myProfile),
}

export default pages