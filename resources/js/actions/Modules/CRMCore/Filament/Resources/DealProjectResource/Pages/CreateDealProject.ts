import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\CreateDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/CreateDealProject.php:7
* @route '/admin/deal-projects/create'
*/
const CreateDealProject = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateDealProject.url(options),
    method: 'get',
})

CreateDealProject.definition = {
    methods: ["get","head"],
    url: '/admin/deal-projects/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\CreateDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/CreateDealProject.php:7
* @route '/admin/deal-projects/create'
*/
CreateDealProject.url = (options?: RouteQueryOptions) => {
    return CreateDealProject.definition.url + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\CreateDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/CreateDealProject.php:7
* @route '/admin/deal-projects/create'
*/
CreateDealProject.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateDealProject.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\CreateDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/CreateDealProject.php:7
* @route '/admin/deal-projects/create'
*/
CreateDealProject.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateDealProject.url(options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\CreateDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/CreateDealProject.php:7
* @route '/admin/deal-projects/create'
*/
const CreateDealProjectForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateDealProject.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\CreateDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/CreateDealProject.php:7
* @route '/admin/deal-projects/create'
*/
CreateDealProjectForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateDealProject.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\CreateDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/CreateDealProject.php:7
* @route '/admin/deal-projects/create'
*/
CreateDealProjectForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateDealProject.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateDealProject.form = CreateDealProjectForm

export default CreateDealProject