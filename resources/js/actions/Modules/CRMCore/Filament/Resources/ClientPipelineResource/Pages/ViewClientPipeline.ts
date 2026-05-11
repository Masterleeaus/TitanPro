import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ViewClientPipeline::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ViewClientPipeline.php:7
* @route '/admin/client-pipelines/{record}'
*/
const ViewClientPipeline = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewClientPipeline.url(args, options),
    method: 'get',
})

ViewClientPipeline.definition = {
    methods: ["get","head"],
    url: '/admin/client-pipelines/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ViewClientPipeline::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ViewClientPipeline.php:7
* @route '/admin/client-pipelines/{record}'
*/
ViewClientPipeline.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ViewClientPipeline.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ViewClientPipeline::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ViewClientPipeline.php:7
* @route '/admin/client-pipelines/{record}'
*/
ViewClientPipeline.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewClientPipeline.url(args, options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ViewClientPipeline::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ViewClientPipeline.php:7
* @route '/admin/client-pipelines/{record}'
*/
ViewClientPipeline.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewClientPipeline.url(args, options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ViewClientPipeline::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ViewClientPipeline.php:7
* @route '/admin/client-pipelines/{record}'
*/
const ViewClientPipelineForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewClientPipeline.url(args, options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ViewClientPipeline::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ViewClientPipeline.php:7
* @route '/admin/client-pipelines/{record}'
*/
ViewClientPipelineForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewClientPipeline.url(args, options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages\ViewClientPipeline::__invoke
* @see Modules/CRMCore/Filament/Resources/ClientPipelineResource/Pages/ViewClientPipeline.php:7
* @route '/admin/client-pipelines/{record}'
*/
ViewClientPipelineForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewClientPipeline.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ViewClientPipeline.form = ViewClientPipelineForm

export default ViewClientPipeline