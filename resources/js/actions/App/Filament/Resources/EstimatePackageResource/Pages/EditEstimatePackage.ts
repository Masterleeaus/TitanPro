import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/admin/estimate-packages/{record}/edit'
*/
const EditEstimatePackage = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditEstimatePackage.url(args, options),
    method: 'get',
})

EditEstimatePackage.definition = {
    methods: ["get","head"],
    url: '/admin/estimate-packages/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/admin/estimate-packages/{record}/edit'
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
* @see \App\Filament\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/admin/estimate-packages/{record}/edit'
*/
EditEstimatePackage.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditEstimatePackage.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/admin/estimate-packages/{record}/edit'
*/
EditEstimatePackage.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditEstimatePackage.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/admin/estimate-packages/{record}/edit'
*/
const EditEstimatePackageForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditEstimatePackage.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/admin/estimate-packages/{record}/edit'
*/
EditEstimatePackageForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditEstimatePackage.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\EditEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php:7
* @route '/admin/estimate-packages/{record}/edit'
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