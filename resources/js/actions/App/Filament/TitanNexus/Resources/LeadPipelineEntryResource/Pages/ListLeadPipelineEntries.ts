import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\ListLeadPipelineEntries::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/ListLeadPipelineEntries.php:7
* @route '/titannexus/lead-pipeline'
*/
const ListLeadPipelineEntries = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLeadPipelineEntries.url(options),
    method: 'get',
})

ListLeadPipelineEntries.definition = {
    methods: ["get","head"],
    url: '/titannexus/lead-pipeline',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\ListLeadPipelineEntries::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/ListLeadPipelineEntries.php:7
* @route '/titannexus/lead-pipeline'
*/
ListLeadPipelineEntries.url = (options?: RouteQueryOptions) => {
    return ListLeadPipelineEntries.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\ListLeadPipelineEntries::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/ListLeadPipelineEntries.php:7
* @route '/titannexus/lead-pipeline'
*/
ListLeadPipelineEntries.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLeadPipelineEntries.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\ListLeadPipelineEntries::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/ListLeadPipelineEntries.php:7
* @route '/titannexus/lead-pipeline'
*/
ListLeadPipelineEntries.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListLeadPipelineEntries.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\ListLeadPipelineEntries::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/ListLeadPipelineEntries.php:7
* @route '/titannexus/lead-pipeline'
*/
const ListLeadPipelineEntriesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLeadPipelineEntries.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\ListLeadPipelineEntries::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/ListLeadPipelineEntries.php:7
* @route '/titannexus/lead-pipeline'
*/
ListLeadPipelineEntriesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLeadPipelineEntries.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\ListLeadPipelineEntries::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/ListLeadPipelineEntries.php:7
* @route '/titannexus/lead-pipeline'
*/
ListLeadPipelineEntriesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLeadPipelineEntries.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListLeadPipelineEntries.form = ListLeadPipelineEntriesForm

export default ListLeadPipelineEntries