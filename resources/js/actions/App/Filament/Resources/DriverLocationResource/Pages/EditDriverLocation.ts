import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\EditDriverLocation::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/EditDriverLocation.php:7
* @route '/admin/driver-locations/{record}/edit'
*/
const EditDriverLocation = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditDriverLocation.url(args, options),
    method: 'get',
})

EditDriverLocation.definition = {
    methods: ["get","head"],
    url: '/admin/driver-locations/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\EditDriverLocation::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/EditDriverLocation.php:7
* @route '/admin/driver-locations/{record}/edit'
*/
EditDriverLocation.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { record: args }
    }

    if (Array.isArray(args)) {
        args = {
            record: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        record: args.record,
    }

    return EditDriverLocation.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\EditDriverLocation::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/EditDriverLocation.php:7
* @route '/admin/driver-locations/{record}/edit'
*/
EditDriverLocation.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditDriverLocation.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\EditDriverLocation::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/EditDriverLocation.php:7
* @route '/admin/driver-locations/{record}/edit'
*/
EditDriverLocation.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditDriverLocation.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\EditDriverLocation::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/EditDriverLocation.php:7
* @route '/admin/driver-locations/{record}/edit'
*/
const EditDriverLocationForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditDriverLocation.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\EditDriverLocation::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/EditDriverLocation.php:7
* @route '/admin/driver-locations/{record}/edit'
*/
EditDriverLocationForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditDriverLocation.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\DriverLocationResource\Pages\EditDriverLocation::__invoke
* @see app/Filament/Resources/DriverLocationResource/Pages/EditDriverLocation.php:7
* @route '/admin/driver-locations/{record}/edit'
*/
EditDriverLocationForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditDriverLocation.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditDriverLocation.form = EditDriverLocationForm

export default EditDriverLocation