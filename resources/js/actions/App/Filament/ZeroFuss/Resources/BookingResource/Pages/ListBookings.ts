import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\ZeroFuss\Resources\BookingResource\Pages\ListBookings::__invoke
* @see app/Filament/ZeroFuss/Resources/BookingResource/Pages/ListBookings.php:7
* @route '/zerofuss/bookings'
*/
const ListBookings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListBookings.url(options),
    method: 'get',
})

ListBookings.definition = {
    methods: ["get","head"],
    url: '/zerofuss/bookings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroFuss\Resources\BookingResource\Pages\ListBookings::__invoke
* @see app/Filament/ZeroFuss/Resources/BookingResource/Pages/ListBookings.php:7
* @route '/zerofuss/bookings'
*/
ListBookings.url = (options?: RouteQueryOptions) => {
    return ListBookings.definition.url + queryParams(options)
}

/**
* @see \App\Filament\ZeroFuss\Resources\BookingResource\Pages\ListBookings::__invoke
* @see app/Filament/ZeroFuss/Resources/BookingResource/Pages/ListBookings.php:7
* @route '/zerofuss/bookings'
*/
ListBookings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListBookings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroFuss\Resources\BookingResource\Pages\ListBookings::__invoke
* @see app/Filament/ZeroFuss/Resources/BookingResource/Pages/ListBookings.php:7
* @route '/zerofuss/bookings'
*/
ListBookings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListBookings.url(options),
    method: 'head',
})

/**
* @see \App\Filament\ZeroFuss\Resources\BookingResource\Pages\ListBookings::__invoke
* @see app/Filament/ZeroFuss/Resources/BookingResource/Pages/ListBookings.php:7
* @route '/zerofuss/bookings'
*/
const ListBookingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListBookings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroFuss\Resources\BookingResource\Pages\ListBookings::__invoke
* @see app/Filament/ZeroFuss/Resources/BookingResource/Pages/ListBookings.php:7
* @route '/zerofuss/bookings'
*/
ListBookingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListBookings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroFuss\Resources\BookingResource\Pages\ListBookings::__invoke
* @see app/Filament/ZeroFuss/Resources/BookingResource/Pages/ListBookings.php:7
* @route '/zerofuss/bookings'
*/
ListBookingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListBookings.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListBookings.form = ListBookingsForm

export default ListBookings