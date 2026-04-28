import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\CreateDriverLocation::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/CreateDriverLocation.php:7
* @route '/admin/driver-locations/create'
*/
const CreateDriverLocation = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateDriverLocation.url(options),
    method: 'get',
})

CreateDriverLocation.definition = {
    methods: ["get","head"],
    url: '/admin/driver-locations/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\CreateDriverLocation::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/CreateDriverLocation.php:7
* @route '/admin/driver-locations/create'
*/
CreateDriverLocation.url = (options?: RouteQueryOptions) => {
    return CreateDriverLocation.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\CreateDriverLocation::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/CreateDriverLocation.php:7
* @route '/admin/driver-locations/create'
*/
CreateDriverLocation.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateDriverLocation.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\CreateDriverLocation::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/CreateDriverLocation.php:7
* @route '/admin/driver-locations/create'
*/
CreateDriverLocation.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateDriverLocation.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\CreateDriverLocation::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/CreateDriverLocation.php:7
* @route '/admin/driver-locations/create'
*/
const CreateDriverLocationForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateDriverLocation.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\CreateDriverLocation::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/CreateDriverLocation.php:7
* @route '/admin/driver-locations/create'
*/
CreateDriverLocationForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateDriverLocation.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\CreateDriverLocation::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/CreateDriverLocation.php:7
* @route '/admin/driver-locations/create'
*/
CreateDriverLocationForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateDriverLocation.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateDriverLocation.form = CreateDriverLocationForm

export default CreateDriverLocation