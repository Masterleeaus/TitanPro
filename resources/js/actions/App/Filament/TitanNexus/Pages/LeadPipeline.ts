import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Pages\LeadPipeline::__invoke
* @see app/Filament/TitanNexus/Pages/LeadPipeline.php:7
* @route '/titannexus/lead-pipeline'
*/
const LeadPipeline = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: LeadPipeline.url(options),
    method: 'get',
})

LeadPipeline.definition = {
    methods: ["get","head"],
    url: '/titannexus/lead-pipeline',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Pages\LeadPipeline::__invoke
* @see app/Filament/TitanNexus/Pages/LeadPipeline.php:7
* @route '/titannexus/lead-pipeline'
*/
LeadPipeline.url = (options?: RouteQueryOptions) => {
    return LeadPipeline.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Pages\LeadPipeline::__invoke
* @see app/Filament/TitanNexus/Pages/LeadPipeline.php:7
* @route '/titannexus/lead-pipeline'
*/
LeadPipeline.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: LeadPipeline.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\LeadPipeline::__invoke
* @see app/Filament/TitanNexus/Pages/LeadPipeline.php:7
* @route '/titannexus/lead-pipeline'
*/
LeadPipeline.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: LeadPipeline.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Pages\LeadPipeline::__invoke
* @see app/Filament/TitanNexus/Pages/LeadPipeline.php:7
* @route '/titannexus/lead-pipeline'
*/
const LeadPipelineForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: LeadPipeline.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\LeadPipeline::__invoke
* @see app/Filament/TitanNexus/Pages/LeadPipeline.php:7
* @route '/titannexus/lead-pipeline'
*/
LeadPipelineForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: LeadPipeline.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\LeadPipeline::__invoke
* @see app/Filament/TitanNexus/Pages/LeadPipeline.php:7
* @route '/titannexus/lead-pipeline'
*/
LeadPipelineForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: LeadPipeline.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

LeadPipeline.form = LeadPipelineForm

export default LeadPipeline