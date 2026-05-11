import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ListClientPipelines::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ListClientPipelines.php:7
* @route '/admin/client-pipelines'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/client-pipelines',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ListClientPipelines::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ListClientPipelines.php:7
* @route '/admin/client-pipelines'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ListClientPipelines::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ListClientPipelines.php:7
* @route '/admin/client-pipelines'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ListClientPipelines::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ListClientPipelines.php:7
* @route '/admin/client-pipelines'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ListClientPipelines::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ListClientPipelines.php:7
* @route '/admin/client-pipelines'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ListClientPipelines::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ListClientPipelines.php:7
* @route '/admin/client-pipelines'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ListClientPipelines::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ListClientPipelines.php:7
* @route '/admin/client-pipelines'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ViewClientPipeline::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ViewClientPipeline.php:7
* @route '/admin/client-pipelines/{record}'
*/
export const view = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: view.url(args, options),
    method: 'get',
})

view.definition = {
    methods: ["get","head"],
    url: '/admin/client-pipelines/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ViewClientPipeline::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ViewClientPipeline.php:7
* @route '/admin/client-pipelines/{record}'
*/
view.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return view.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ViewClientPipeline::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ViewClientPipeline.php:7
* @route '/admin/client-pipelines/{record}'
*/
view.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: view.url(args, options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ViewClientPipeline::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ViewClientPipeline.php:7
* @route '/admin/client-pipelines/{record}'
*/
view.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: view.url(args, options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ViewClientPipeline::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ViewClientPipeline.php:7
* @route '/admin/client-pipelines/{record}'
*/
const viewForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: view.url(args, options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ViewClientPipeline::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ViewClientPipeline.php:7
* @route '/admin/client-pipelines/{record}'
*/
viewForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: view.url(args, options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ViewClientPipeline::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ViewClientPipeline.php:7
* @route '/admin/client-pipelines/{record}'
*/
viewForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: view.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

view.form = viewForm

const clientPipelines = {
    index: Object.assign(index, index),
    view: Object.assign(view, view),
}

export default clientPipelines