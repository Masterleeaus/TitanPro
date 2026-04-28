import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ListClientPipelines::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ListClientPipelines.php:7
* @route '/admin/client-pipelines'
*/
const ListClientPipelines = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListClientPipelines.url(options),
    method: 'get',
})

ListClientPipelines.definition = {
    methods: ["get","head"],
    url: '/admin/client-pipelines',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ListClientPipelines::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ListClientPipelines.php:7
* @route '/admin/client-pipelines'
*/
ListClientPipelines.url = (options?: RouteQueryOptions) => {
    return ListClientPipelines.definition.url + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ListClientPipelines::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ListClientPipelines.php:7
* @route '/admin/client-pipelines'
*/
ListClientPipelines.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListClientPipelines.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ListClientPipelines::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ListClientPipelines.php:7
* @route '/admin/client-pipelines'
*/
ListClientPipelines.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListClientPipelines.url(options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ListClientPipelines::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ListClientPipelines.php:7
* @route '/admin/client-pipelines'
*/
const ListClientPipelinesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListClientPipelines.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ListClientPipelines::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ListClientPipelines.php:7
* @route '/admin/client-pipelines'
*/
ListClientPipelinesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListClientPipelines.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ListClientPipelines::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ListClientPipelines.php:7
* @route '/admin/client-pipelines'
*/
ListClientPipelinesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListClientPipelines.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListClientPipelines.form = ListClientPipelinesForm

export default ListClientPipelines