import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\GroundZero\Pages\CalendarPage::__invoke
* @see app/Filament/GroundZero/Pages/CalendarPage.php:7
* @route '/groundzero/calendar-page'
*/
const CalendarPage = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CalendarPage.url(options),
    method: 'get',
})

CalendarPage.definition = {
    methods: ["get","head"],
    url: '/groundzero/calendar-page',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\GroundZero\Pages\CalendarPage::__invoke
* @see app/Filament/GroundZero/Pages/CalendarPage.php:7
* @route '/groundzero/calendar-page'
*/
CalendarPage.url = (options?: RouteQueryOptions) => {
    return CalendarPage.definition.url + queryParams(options)
}

/**
* @see \App\Filament\GroundZero\Pages\CalendarPage::__invoke
* @see app/Filament/GroundZero/Pages/CalendarPage.php:7
* @route '/groundzero/calendar-page'
*/
CalendarPage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CalendarPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\CalendarPage::__invoke
* @see app/Filament/GroundZero/Pages/CalendarPage.php:7
* @route '/groundzero/calendar-page'
*/
CalendarPage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CalendarPage.url(options),
    method: 'head',
})

/**
* @see \App\Filament\GroundZero\Pages\CalendarPage::__invoke
* @see app/Filament/GroundZero/Pages/CalendarPage.php:7
* @route '/groundzero/calendar-page'
*/
const CalendarPageForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CalendarPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\CalendarPage::__invoke
* @see app/Filament/GroundZero/Pages/CalendarPage.php:7
* @route '/groundzero/calendar-page'
*/
CalendarPageForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CalendarPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\CalendarPage::__invoke
* @see app/Filament/GroundZero/Pages/CalendarPage.php:7
* @route '/groundzero/calendar-page'
*/
CalendarPageForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CalendarPage.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CalendarPage.form = CalendarPageForm

export default CalendarPage