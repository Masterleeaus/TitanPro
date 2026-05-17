import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\GroundZero\Resources\PropertyResource\Pages\ListProperties::__invoke
* @see app/Filament/GroundZero/Resources/PropertyResource/Pages/ListProperties.php:7
* @route '/groundzero/properties'
*/
const ListProperties = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListProperties.url(options),
    method: 'get',
})

ListProperties.definition = {
    methods: ["get","head"],
    url: '/groundzero/properties',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\GroundZero\Resources\PropertyResource\Pages\ListProperties::__invoke
* @see app/Filament/GroundZero/Resources/PropertyResource/Pages/ListProperties.php:7
* @route '/groundzero/properties'
*/
ListProperties.url = (options?: RouteQueryOptions) => {
    return ListProperties.definition.url + queryParams(options)
}

/**
* @see \App\Filament\GroundZero\Resources\PropertyResource\Pages\ListProperties::__invoke
* @see app/Filament/GroundZero/Resources/PropertyResource/Pages/ListProperties.php:7
* @route '/groundzero/properties'
*/
ListProperties.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListProperties.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Resources\PropertyResource\Pages\ListProperties::__invoke
* @see app/Filament/GroundZero/Resources/PropertyResource/Pages/ListProperties.php:7
* @route '/groundzero/properties'
*/
ListProperties.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListProperties.url(options),
    method: 'head',
})

/**
* @see \App\Filament\GroundZero\Resources\PropertyResource\Pages\ListProperties::__invoke
* @see app/Filament/GroundZero/Resources/PropertyResource/Pages/ListProperties.php:7
* @route '/groundzero/properties'
*/
const ListPropertiesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListProperties.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Resources\PropertyResource\Pages\ListProperties::__invoke
* @see app/Filament/GroundZero/Resources/PropertyResource/Pages/ListProperties.php:7
* @route '/groundzero/properties'
*/
ListPropertiesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListProperties.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Resources\PropertyResource\Pages\ListProperties::__invoke
* @see app/Filament/GroundZero/Resources/PropertyResource/Pages/ListProperties.php:7
* @route '/groundzero/properties'
*/
ListPropertiesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListProperties.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListProperties.form = ListPropertiesForm

export default ListProperties