import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\CreateLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/CreateLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/create'
*/
const CreateLeadPipelineEntry = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLeadPipelineEntry.url(options),
    method: 'get',
})

CreateLeadPipelineEntry.definition = {
    methods: ["get","head"],
    url: '/titannexus/lead-pipeline/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\CreateLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/CreateLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/create'
*/
CreateLeadPipelineEntry.url = (options?: RouteQueryOptions) => {
    return CreateLeadPipelineEntry.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\CreateLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/CreateLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/create'
*/
CreateLeadPipelineEntry.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLeadPipelineEntry.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\CreateLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/CreateLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/create'
*/
CreateLeadPipelineEntry.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateLeadPipelineEntry.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\CreateLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/CreateLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/create'
*/
const CreateLeadPipelineEntryForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLeadPipelineEntry.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\CreateLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/CreateLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/create'
*/
CreateLeadPipelineEntryForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLeadPipelineEntry.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\CreateLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/CreateLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/create'
*/
CreateLeadPipelineEntryForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLeadPipelineEntry.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateLeadPipelineEntry.form = CreateLeadPipelineEntryForm

export default CreateLeadPipelineEntry