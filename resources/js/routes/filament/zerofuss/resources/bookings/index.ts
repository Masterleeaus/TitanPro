import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\ZeroFuss\Resources\BookingResource\Pages\ListBookings::__invoke
* @see app/Filament/ZeroFuss/Resources/BookingResource/Pages/ListBookings.php:7
* @route '/zerofuss/bookings'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/zerofuss/bookings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroFuss\Resources\BookingResource\Pages\ListBookings::__invoke
* @see app/Filament/ZeroFuss/Resources/BookingResource/Pages/ListBookings.php:7
* @route '/zerofuss/bookings'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\ZeroFuss\Resources\BookingResource\Pages\ListBookings::__invoke
* @see app/Filament/ZeroFuss/Resources/BookingResource/Pages/ListBookings.php:7
* @route '/zerofuss/bookings'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroFuss\Resources\BookingResource\Pages\ListBookings::__invoke
* @see app/Filament/ZeroFuss/Resources/BookingResource/Pages/ListBookings.php:7
* @route '/zerofuss/bookings'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Filament\ZeroFuss\Resources\BookingResource\Pages\ListBookings::__invoke
* @see app/Filament/ZeroFuss/Resources/BookingResource/Pages/ListBookings.php:7
* @route '/zerofuss/bookings'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroFuss\Resources\BookingResource\Pages\ListBookings::__invoke
* @see app/Filament/ZeroFuss/Resources/BookingResource/Pages/ListBookings.php:7
* @route '/zerofuss/bookings'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroFuss\Resources\BookingResource\Pages\ListBookings::__invoke
* @see app/Filament/ZeroFuss/Resources/BookingResource/Pages/ListBookings.php:7
* @route '/zerofuss/bookings'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

const bookings = {
    index: Object.assign(index, index),
}

export default bookings