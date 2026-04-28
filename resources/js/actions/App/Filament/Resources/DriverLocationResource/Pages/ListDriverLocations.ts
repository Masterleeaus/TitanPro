import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\ListDriverLocations::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/ListDriverLocations.php:7
* @route '/admin/driver-locations'
*/
const ListDriverLocations = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListDriverLocations.url(options),
    method: 'get',
})

ListDriverLocations.definition = {
    methods: ["get","head"],
    url: '/admin/driver-locations',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\ListDriverLocations::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/ListDriverLocations.php:7
* @route '/admin/driver-locations'
*/
ListDriverLocations.url = (options?: RouteQueryOptions) => {
    return ListDriverLocations.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\ListDriverLocations::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/ListDriverLocations.php:7
* @route '/admin/driver-locations'
*/
ListDriverLocations.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListDriverLocations.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\ListDriverLocations::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/ListDriverLocations.php:7
* @route '/admin/driver-locations'
*/
ListDriverLocations.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListDriverLocations.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\ListDriverLocations::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/ListDriverLocations.php:7
* @route '/admin/driver-locations'
*/
const ListDriverLocationsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListDriverLocations.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\ListDriverLocations::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/ListDriverLocations.php:7
* @route '/admin/driver-locations'
*/
ListDriverLocationsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListDriverLocations.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\ListDriverLocations::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/ListDriverLocations.php:7
* @route '/admin/driver-locations'
*/
ListDriverLocationsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListDriverLocations.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListDriverLocations.form = ListDriverLocationsForm

export default ListDriverLocations