import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.ts
* @see \App\Filament\GroundZero\Resources\PropertyResource\Pages\EditProperty::__invoke
* @see app/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.php:7
* @route '/groundzero/properties/{record}/edit'
========
* @see \App\Filament\Resources\PropertyResource\Pages\EditProperty::__invoke
* @see app/Filament/Resources/PropertyResource/Pages/EditProperty.php:7
* @route '/titanpro/properties/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/PropertyResource/Pages/EditProperty.ts
*/
const EditProperty = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditProperty.url(args, options),
    method: 'get',
})

EditProperty.definition = {
    methods: ["get","head"],
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.ts
    url: '/groundzero/properties/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\GroundZero\Resources\PropertyResource\Pages\EditProperty::__invoke
* @see app/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.php:7
* @route '/groundzero/properties/{record}/edit'
========
    url: '/titanpro/properties/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\PropertyResource\Pages\EditProperty::__invoke
* @see app/Filament/Resources/PropertyResource/Pages/EditProperty.php:7
* @route '/titanpro/properties/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/PropertyResource/Pages/EditProperty.ts
*/
EditProperty.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditProperty.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.ts
* @see \App\Filament\GroundZero\Resources\PropertyResource\Pages\EditProperty::__invoke
* @see app/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.php:7
* @route '/groundzero/properties/{record}/edit'
========
* @see \App\Filament\Resources\PropertyResource\Pages\EditProperty::__invoke
* @see app/Filament/Resources/PropertyResource/Pages/EditProperty.php:7
* @route '/titanpro/properties/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/PropertyResource/Pages/EditProperty.ts
*/
EditProperty.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditProperty.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.ts
* @see \App\Filament\GroundZero\Resources\PropertyResource\Pages\EditProperty::__invoke
* @see app/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.php:7
* @route '/groundzero/properties/{record}/edit'
========
* @see \App\Filament\Resources\PropertyResource\Pages\EditProperty::__invoke
* @see app/Filament/Resources/PropertyResource/Pages/EditProperty.php:7
* @route '/titanpro/properties/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/PropertyResource/Pages/EditProperty.ts
*/
EditProperty.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditProperty.url(args, options),
    method: 'head',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.ts
* @see \App\Filament\GroundZero\Resources\PropertyResource\Pages\EditProperty::__invoke
* @see app/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.php:7
* @route '/groundzero/properties/{record}/edit'
========
* @see \App\Filament\Resources\PropertyResource\Pages\EditProperty::__invoke
* @see app/Filament/Resources/PropertyResource/Pages/EditProperty.php:7
* @route '/titanpro/properties/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/PropertyResource/Pages/EditProperty.ts
*/
const EditPropertyForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditProperty.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.ts
* @see \App\Filament\GroundZero\Resources\PropertyResource\Pages\EditProperty::__invoke
* @see app/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.php:7
* @route '/groundzero/properties/{record}/edit'
========
* @see \App\Filament\Resources\PropertyResource\Pages\EditProperty::__invoke
* @see app/Filament/Resources/PropertyResource/Pages/EditProperty.php:7
* @route '/titanpro/properties/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/PropertyResource/Pages/EditProperty.ts
*/
EditPropertyForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditProperty.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.ts
* @see \App\Filament\GroundZero\Resources\PropertyResource\Pages\EditProperty::__invoke
* @see app/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.php:7
* @route '/groundzero/properties/{record}/edit'
========
* @see \App\Filament\Resources\PropertyResource\Pages\EditProperty::__invoke
* @see app/Filament/Resources/PropertyResource/Pages/EditProperty.php:7
* @route '/titanpro/properties/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/PropertyResource/Pages/EditProperty.ts
*/
EditPropertyForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditProperty.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditProperty.form = EditPropertyForm

export default EditProperty