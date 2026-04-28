import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\ListDealProjects::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/ListDealProjects.php:7
* @route '/admin/deal-projects'
*/
const ListDealProjects = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListDealProjects.url(options),
    method: 'get',
})

ListDealProjects.definition = {
    methods: ["get","head"],
    url: '/admin/deal-projects',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\ListDealProjects::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/ListDealProjects.php:7
* @route '/admin/deal-projects'
*/
ListDealProjects.url = (options?: RouteQueryOptions) => {
    return ListDealProjects.definition.url + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\ListDealProjects::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/ListDealProjects.php:7
* @route '/admin/deal-projects'
*/
ListDealProjects.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListDealProjects.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\ListDealProjects::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/ListDealProjects.php:7
* @route '/admin/deal-projects'
*/
ListDealProjects.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListDealProjects.url(options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\ListDealProjects::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/ListDealProjects.php:7
* @route '/admin/deal-projects'
*/
const ListDealProjectsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListDealProjects.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\ListDealProjects::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/ListDealProjects.php:7
* @route '/admin/deal-projects'
*/
ListDealProjectsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListDealProjects.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\ListDealProjects::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/ListDealProjects.php:7
* @route '/admin/deal-projects'
*/
ListDealProjectsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListDealProjects.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListDealProjects.form = ListDealProjectsForm

export default ListDealProjects