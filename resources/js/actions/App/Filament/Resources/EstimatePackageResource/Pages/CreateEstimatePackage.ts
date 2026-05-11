import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\CreateEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/CreateEstimatePackage.php:7
* @route '/admin/estimate-packages/create'
*/
const CreateEstimatePackage = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateEstimatePackage.url(options),
    method: 'get',
})

CreateEstimatePackage.definition = {
    methods: ["get","head"],
    url: '/admin/estimate-packages/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\CreateEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/CreateEstimatePackage.php:7
* @route '/admin/estimate-packages/create'
*/
CreateEstimatePackage.url = (options?: RouteQueryOptions) => {
    return CreateEstimatePackage.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\CreateEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/CreateEstimatePackage.php:7
* @route '/admin/estimate-packages/create'
*/
CreateEstimatePackage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateEstimatePackage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\CreateEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/CreateEstimatePackage.php:7
* @route '/admin/estimate-packages/create'
*/
CreateEstimatePackage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateEstimatePackage.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\CreateEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/CreateEstimatePackage.php:7
* @route '/admin/estimate-packages/create'
*/
const CreateEstimatePackageForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateEstimatePackage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\CreateEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/CreateEstimatePackage.php:7
* @route '/admin/estimate-packages/create'
*/
CreateEstimatePackageForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateEstimatePackage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\CreateEstimatePackage::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/CreateEstimatePackage.php:7
* @route '/admin/estimate-packages/create'
*/
CreateEstimatePackageForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateEstimatePackage.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateEstimatePackage.form = CreateEstimatePackageForm

export default CreateEstimatePackage