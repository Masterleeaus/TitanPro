import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/EditEstimatePackage.ts
* @see \App\Filament\TitanQuotes\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/titanquotes/estimate-packages/{record}/edit'
========
* @see \App\Filament\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/titanpro/estimate-packages/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.ts
*/
const EditEstimatePackage = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditEstimatePackage.url(args, options),
    method: 'get',
})

EditEstimatePackage.definition = {
    methods: ["get","head"],
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/EditEstimatePackage.ts
    url: '/titanquotes/estimate-packages/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanQuotes\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/titanquotes/estimate-packages/{record}/edit'
========
    url: '/titanpro/estimate-packages/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/titanpro/estimate-packages/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.ts
*/
EditEstimatePackage.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditEstimatePackage.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/EditEstimatePackage.ts
* @see \App\Filament\TitanQuotes\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/titanquotes/estimate-packages/{record}/edit'
========
* @see \App\Filament\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/titanpro/estimate-packages/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.ts
*/
EditEstimatePackage.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditEstimatePackage.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/EditEstimatePackage.ts
* @see \App\Filament\TitanQuotes\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/titanquotes/estimate-packages/{record}/edit'
========
* @see \App\Filament\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/titanpro/estimate-packages/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.ts
*/
EditEstimatePackage.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditEstimatePackage.url(args, options),
    method: 'head',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/EditEstimatePackage.ts
* @see \App\Filament\TitanQuotes\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/titanquotes/estimate-packages/{record}/edit'
========
* @see \App\Filament\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/titanpro/estimate-packages/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.ts
*/
const EditEstimatePackageForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditEstimatePackage.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/EditEstimatePackage.ts
* @see \App\Filament\TitanQuotes\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/titanquotes/estimate-packages/{record}/edit'
========
* @see \App\Filament\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/titanpro/estimate-packages/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.ts
*/
EditEstimatePackageForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditEstimatePackage.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/EditEstimatePackage.ts
* @see \App\Filament\TitanQuotes\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/titanquotes/estimate-packages/{record}/edit'
========
* @see \App\Filament\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/titanpro/estimate-packages/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.ts
*/
EditEstimatePackageForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditEstimatePackage.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditEstimatePackage.form = EditEstimatePackageForm

export default EditEstimatePackage