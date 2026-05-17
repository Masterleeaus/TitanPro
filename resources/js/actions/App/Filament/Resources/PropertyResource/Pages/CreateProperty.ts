import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\PropertyResource\Pages\CreateProperty::__invoke
* @see app/Filament/Resources/PropertyResource/Pages/CreateProperty.php:7
* @route '/titanpro/properties/create'
*/
const CreateProperty = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateProperty.url(options),
    method: 'get',
})

CreateProperty.definition = {
    methods: ["get","head"],
    url: '/titanpro/properties/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\PropertyResource\Pages\CreateProperty::__invoke
* @see app/Filament/Resources/PropertyResource/Pages/CreateProperty.php:7
* @route '/titanpro/properties/create'
*/
CreateProperty.url = (options?: RouteQueryOptions) => {
    return CreateProperty.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\PropertyResource\Pages\CreateProperty::__invoke
* @see app/Filament/Resources/PropertyResource/Pages/CreateProperty.php:7
* @route '/titanpro/properties/create'
*/
CreateProperty.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateProperty.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\PropertyResource\Pages\CreateProperty::__invoke
* @see app/Filament/Resources/PropertyResource/Pages/CreateProperty.php:7
* @route '/titanpro/properties/create'
*/
CreateProperty.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateProperty.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\PropertyResource\Pages\CreateProperty::__invoke
* @see app/Filament/Resources/PropertyResource/Pages/CreateProperty.php:7
* @route '/titanpro/properties/create'
*/
const CreatePropertyForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateProperty.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\PropertyResource\Pages\CreateProperty::__invoke
* @see app/Filament/Resources/PropertyResource/Pages/CreateProperty.php:7
* @route '/titanpro/properties/create'
*/
CreatePropertyForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateProperty.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\PropertyResource\Pages\CreateProperty::__invoke
* @see app/Filament/Resources/PropertyResource/Pages/CreateProperty.php:7
* @route '/titanpro/properties/create'
*/
CreatePropertyForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateProperty.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateProperty.form = CreatePropertyForm

export default CreateProperty