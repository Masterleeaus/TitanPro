import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.ts
* @see \App\Filament\GroundZero\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/groundzero/estimates/{record}/edit'
========
* @see \App\Filament\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/titanpro/estimates/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/EstimateResource/Pages/EditEstimate.ts
*/
const EditEstimate = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditEstimate.url(args, options),
    method: 'get',
})

EditEstimate.definition = {
    methods: ["get","head"],
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.ts
    url: '/groundzero/estimates/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\GroundZero\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/groundzero/estimates/{record}/edit'
========
    url: '/titanpro/estimates/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/titanpro/estimates/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/EstimateResource/Pages/EditEstimate.ts
*/
EditEstimate.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditEstimate.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.ts
* @see \App\Filament\GroundZero\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/groundzero/estimates/{record}/edit'
========
* @see \App\Filament\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/titanpro/estimates/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/EstimateResource/Pages/EditEstimate.ts
*/
EditEstimate.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditEstimate.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.ts
* @see \App\Filament\GroundZero\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/groundzero/estimates/{record}/edit'
========
* @see \App\Filament\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/titanpro/estimates/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/EstimateResource/Pages/EditEstimate.ts
*/
EditEstimate.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditEstimate.url(args, options),
    method: 'head',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.ts
* @see \App\Filament\GroundZero\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/groundzero/estimates/{record}/edit'
========
* @see \App\Filament\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/titanpro/estimates/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/EstimateResource/Pages/EditEstimate.ts
*/
const EditEstimateForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditEstimate.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.ts
* @see \App\Filament\GroundZero\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/groundzero/estimates/{record}/edit'
========
* @see \App\Filament\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/titanpro/estimates/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/EstimateResource/Pages/EditEstimate.ts
*/
EditEstimateForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditEstimate.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.ts
* @see \App\Filament\GroundZero\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/groundzero/estimates/{record}/edit'
========
* @see \App\Filament\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/titanpro/estimates/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/EstimateResource/Pages/EditEstimate.ts
*/
EditEstimateForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditEstimate.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditEstimate.form = EditEstimateForm

export default EditEstimate