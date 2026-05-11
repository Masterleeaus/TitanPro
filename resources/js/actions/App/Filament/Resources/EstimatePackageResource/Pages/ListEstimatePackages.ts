import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\ListEstimatePackages::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/ListEstimatePackages.php:7
* @route '/admin/estimate-packages'
*/
const ListEstimatePackages = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListEstimatePackages.url(options),
    method: 'get',
})

ListEstimatePackages.definition = {
    methods: ["get","head"],
    url: '/admin/estimate-packages',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\ListEstimatePackages::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/ListEstimatePackages.php:7
* @route '/admin/estimate-packages'
*/
ListEstimatePackages.url = (options?: RouteQueryOptions) => {
    return ListEstimatePackages.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\ListEstimatePackages::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/ListEstimatePackages.php:7
* @route '/admin/estimate-packages'
*/
ListEstimatePackages.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListEstimatePackages.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\ListEstimatePackages::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/ListEstimatePackages.php:7
* @route '/admin/estimate-packages'
*/
ListEstimatePackages.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListEstimatePackages.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\ListEstimatePackages::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/ListEstimatePackages.php:7
* @route '/admin/estimate-packages'
*/
const ListEstimatePackagesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListEstimatePackages.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\ListEstimatePackages::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/ListEstimatePackages.php:7
* @route '/admin/estimate-packages'
*/
ListEstimatePackagesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListEstimatePackages.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\EstimatePackageResource\Pages\ListEstimatePackages::__invoke
* @see app/Filament/Resources/EstimatePackageResource/Pages/ListEstimatePackages.php:7
* @route '/admin/estimate-packages'
*/
ListEstimatePackagesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListEstimatePackages.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListEstimatePackages.form = ListEstimatePackagesForm

export default ListEstimatePackages