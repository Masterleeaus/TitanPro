import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Owner\CalendarController::events
* @see app/Http/Controllers/Owner/CalendarController.php:22
* @route '/owner/calendar/events'
*/
export const events = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: events.url(options),
    method: 'get',
})

events.definition = {
    methods: ["get","head"],
    url: '/owner/calendar/events',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\CalendarController::events
* @see app/Http/Controllers/Owner/CalendarController.php:22
* @route '/owner/calendar/events'
*/
events.url = (options?: RouteQueryOptions) => {
    return events.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\CalendarController::events
* @see app/Http/Controllers/Owner/CalendarController.php:22
* @route '/owner/calendar/events'
*/
events.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: events.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\CalendarController::events
* @see app/Http/Controllers/Owner/CalendarController.php:22
* @route '/owner/calendar/events'
*/
events.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: events.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\CalendarController::events
* @see app/Http/Controllers/Owner/CalendarController.php:22
* @route '/owner/calendar/events'
*/
const eventsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: events.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\CalendarController::events
* @see app/Http/Controllers/Owner/CalendarController.php:22
* @route '/owner/calendar/events'
*/
eventsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: events.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\CalendarController::events
* @see app/Http/Controllers/Owner/CalendarController.php:22
* @route '/owner/calendar/events'
*/
eventsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: events.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

events.form = eventsForm

const calendar = {
    events: Object.assign(events, events),
}

export default calendar