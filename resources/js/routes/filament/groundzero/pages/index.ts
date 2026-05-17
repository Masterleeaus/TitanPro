import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/groundzero'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/groundzero',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/groundzero'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/groundzero'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/groundzero'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/groundzero'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/groundzero'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/groundzero'
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
* @route '/groundzero/my-profile'
*/
export const myProfile = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: myProfile.url(options),
    method: 'get',
})

myProfile.definition = {
    methods: ["get","head"],
    url: '/groundzero/my-profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/groundzero/my-profile'
*/
myProfile.url = (options?: RouteQueryOptions) => {
    return myProfile.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/groundzero/my-profile'
*/
myProfile.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/groundzero/my-profile'
*/
myProfile.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: myProfile.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/groundzero/my-profile'
*/
const myProfileForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/groundzero/my-profile'
*/
myProfileForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/groundzero/my-profile'
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
* @see \App\Filament\GroundZero\Pages\CalendarPage::__invoke
* @see app/Filament/GroundZero/Pages/CalendarPage.php:7
* @route '/groundzero/calendar-page'
*/
export const calendarPage = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: calendarPage.url(options),
    method: 'get',
})

calendarPage.definition = {
    methods: ["get","head"],
    url: '/groundzero/calendar-page',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\GroundZero\Pages\CalendarPage::__invoke
* @see app/Filament/GroundZero/Pages/CalendarPage.php:7
* @route '/groundzero/calendar-page'
*/
calendarPage.url = (options?: RouteQueryOptions) => {
    return calendarPage.definition.url + queryParams(options)
}

/**
* @see \App\Filament\GroundZero\Pages\CalendarPage::__invoke
* @see app/Filament/GroundZero/Pages/CalendarPage.php:7
* @route '/groundzero/calendar-page'
*/
calendarPage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: calendarPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\CalendarPage::__invoke
* @see app/Filament/GroundZero/Pages/CalendarPage.php:7
* @route '/groundzero/calendar-page'
*/
calendarPage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: calendarPage.url(options),
    method: 'head',
})

/**
* @see \App\Filament\GroundZero\Pages\CalendarPage::__invoke
* @see app/Filament/GroundZero/Pages/CalendarPage.php:7
* @route '/groundzero/calendar-page'
*/
const calendarPageForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: calendarPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\CalendarPage::__invoke
* @see app/Filament/GroundZero/Pages/CalendarPage.php:7
* @route '/groundzero/calendar-page'
*/
calendarPageForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: calendarPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\CalendarPage::__invoke
* @see app/Filament/GroundZero/Pages/CalendarPage.php:7
* @route '/groundzero/calendar-page'
*/
calendarPageForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: calendarPage.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

calendarPage.form = calendarPageForm

/**
* @see \App\Filament\GroundZero\Pages\DispatchBoard::__invoke
* @see app/Filament/GroundZero/Pages/DispatchBoard.php:7
* @route '/groundzero/dispatch-board'
*/
export const dispatchBoard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dispatchBoard.url(options),
    method: 'get',
})

dispatchBoard.definition = {
    methods: ["get","head"],
    url: '/groundzero/dispatch-board',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\GroundZero\Pages\DispatchBoard::__invoke
* @see app/Filament/GroundZero/Pages/DispatchBoard.php:7
* @route '/groundzero/dispatch-board'
*/
dispatchBoard.url = (options?: RouteQueryOptions) => {
    return dispatchBoard.definition.url + queryParams(options)
}

/**
* @see \App\Filament\GroundZero\Pages\DispatchBoard::__invoke
* @see app/Filament/GroundZero/Pages/DispatchBoard.php:7
* @route '/groundzero/dispatch-board'
*/
dispatchBoard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dispatchBoard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\DispatchBoard::__invoke
* @see app/Filament/GroundZero/Pages/DispatchBoard.php:7
* @route '/groundzero/dispatch-board'
*/
dispatchBoard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dispatchBoard.url(options),
    method: 'head',
})

/**
* @see \App\Filament\GroundZero\Pages\DispatchBoard::__invoke
* @see app/Filament/GroundZero/Pages/DispatchBoard.php:7
* @route '/groundzero/dispatch-board'
*/
const dispatchBoardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dispatchBoard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\DispatchBoard::__invoke
* @see app/Filament/GroundZero/Pages/DispatchBoard.php:7
* @route '/groundzero/dispatch-board'
*/
dispatchBoardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dispatchBoard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\DispatchBoard::__invoke
* @see app/Filament/GroundZero/Pages/DispatchBoard.php:7
* @route '/groundzero/dispatch-board'
*/
dispatchBoardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dispatchBoard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

dispatchBoard.form = dispatchBoardForm

/**
* @see \App\Filament\GroundZero\Pages\ReportsPage::__invoke
* @see app/Filament/GroundZero/Pages/ReportsPage.php:7
* @route '/groundzero/reports-page'
*/
export const reportsPage = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reportsPage.url(options),
    method: 'get',
})

reportsPage.definition = {
    methods: ["get","head"],
    url: '/groundzero/reports-page',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\GroundZero\Pages\ReportsPage::__invoke
* @see app/Filament/GroundZero/Pages/ReportsPage.php:7
* @route '/groundzero/reports-page'
*/
reportsPage.url = (options?: RouteQueryOptions) => {
    return reportsPage.definition.url + queryParams(options)
}

/**
* @see \App\Filament\GroundZero\Pages\ReportsPage::__invoke
* @see app/Filament/GroundZero/Pages/ReportsPage.php:7
* @route '/groundzero/reports-page'
*/
reportsPage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reportsPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\ReportsPage::__invoke
* @see app/Filament/GroundZero/Pages/ReportsPage.php:7
* @route '/groundzero/reports-page'
*/
reportsPage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: reportsPage.url(options),
    method: 'head',
})

/**
* @see \App\Filament\GroundZero\Pages\ReportsPage::__invoke
* @see app/Filament/GroundZero/Pages/ReportsPage.php:7
* @route '/groundzero/reports-page'
*/
const reportsPageForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reportsPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\ReportsPage::__invoke
* @see app/Filament/GroundZero/Pages/ReportsPage.php:7
* @route '/groundzero/reports-page'
*/
reportsPageForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reportsPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\ReportsPage::__invoke
* @see app/Filament/GroundZero/Pages/ReportsPage.php:7
* @route '/groundzero/reports-page'
*/
reportsPageForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reportsPage.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

reportsPage.form = reportsPageForm

/**
* @see \App\Filament\GroundZero\Pages\SettingsPage::__invoke
* @see app/Filament/GroundZero/Pages/SettingsPage.php:7
* @route '/groundzero/settings-page'
*/
export const settingsPage = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: settingsPage.url(options),
    method: 'get',
})

settingsPage.definition = {
    methods: ["get","head"],
    url: '/groundzero/settings-page',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\GroundZero\Pages\SettingsPage::__invoke
* @see app/Filament/GroundZero/Pages/SettingsPage.php:7
* @route '/groundzero/settings-page'
*/
settingsPage.url = (options?: RouteQueryOptions) => {
    return settingsPage.definition.url + queryParams(options)
}

/**
* @see \App\Filament\GroundZero\Pages\SettingsPage::__invoke
* @see app/Filament/GroundZero/Pages/SettingsPage.php:7
* @route '/groundzero/settings-page'
*/
settingsPage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: settingsPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\SettingsPage::__invoke
* @see app/Filament/GroundZero/Pages/SettingsPage.php:7
* @route '/groundzero/settings-page'
*/
settingsPage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: settingsPage.url(options),
    method: 'head',
})

/**
* @see \App\Filament\GroundZero\Pages\SettingsPage::__invoke
* @see app/Filament/GroundZero/Pages/SettingsPage.php:7
* @route '/groundzero/settings-page'
*/
const settingsPageForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: settingsPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\SettingsPage::__invoke
* @see app/Filament/GroundZero/Pages/SettingsPage.php:7
* @route '/groundzero/settings-page'
*/
settingsPageForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: settingsPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\SettingsPage::__invoke
* @see app/Filament/GroundZero/Pages/SettingsPage.php:7
* @route '/groundzero/settings-page'
*/
settingsPageForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: settingsPage.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

settingsPage.form = settingsPageForm

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/groundzero/ui-studio'
*/
export const uiStudio = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: uiStudio.url(options),
    method: 'get',
})

uiStudio.definition = {
    methods: ["get","head"],
    url: '/groundzero/ui-studio',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/groundzero/ui-studio'
*/
uiStudio.url = (options?: RouteQueryOptions) => {
    return uiStudio.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/groundzero/ui-studio'
*/
uiStudio.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: uiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/groundzero/ui-studio'
*/
uiStudio.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: uiStudio.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/groundzero/ui-studio'
*/
const uiStudioForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: uiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/groundzero/ui-studio'
*/
uiStudioForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: uiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/groundzero/ui-studio'
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
    calendarPage: Object.assign(calendarPage, calendarPage),
    dispatchBoard: Object.assign(dispatchBoard, dispatchBoard),
    reportsPage: Object.assign(reportsPage, reportsPage),
    settingsPage: Object.assign(settingsPage, settingsPage),
    uiStudio: Object.assign(uiStudio, uiStudio),
}

export default pages