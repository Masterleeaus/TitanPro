import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\ViewLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/ViewLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/{record}'
*/
const ViewLeadPipelineEntry = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewLeadPipelineEntry.url(args, options),
    method: 'get',
})

ViewLeadPipelineEntry.definition = {
    methods: ["get","head"],
    url: '/titannexus/lead-pipeline/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\ViewLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/ViewLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/{record}'
*/
ViewLeadPipelineEntry.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ViewLeadPipelineEntry.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\ViewLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/ViewLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/{record}'
*/
ViewLeadPipelineEntry.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewLeadPipelineEntry.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\ViewLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/ViewLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/{record}'
*/
ViewLeadPipelineEntry.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewLeadPipelineEntry.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\ViewLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/ViewLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/{record}'
*/
const ViewLeadPipelineEntryForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewLeadPipelineEntry.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\ViewLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/ViewLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/{record}'
*/
ViewLeadPipelineEntryForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewLeadPipelineEntry.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\ViewLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/ViewLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/{record}'
*/
ViewLeadPipelineEntryForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewLeadPipelineEntry.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ViewLeadPipelineEntry.form = ViewLeadPipelineEntryForm

export default ViewLeadPipelineEntry