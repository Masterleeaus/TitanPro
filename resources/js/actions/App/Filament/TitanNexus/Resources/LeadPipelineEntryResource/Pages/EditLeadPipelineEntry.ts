import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\EditLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/EditLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/{record}/edit'
*/
const EditLeadPipelineEntry = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLeadPipelineEntry.url(args, options),
    method: 'get',
})

EditLeadPipelineEntry.definition = {
    methods: ["get","head"],
    url: '/titannexus/lead-pipeline/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\EditLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/EditLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/{record}/edit'
*/
EditLeadPipelineEntry.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditLeadPipelineEntry.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\EditLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/EditLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/{record}/edit'
*/
EditLeadPipelineEntry.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLeadPipelineEntry.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\EditLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/EditLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/{record}/edit'
*/
EditLeadPipelineEntry.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditLeadPipelineEntry.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\EditLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/EditLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/{record}/edit'
*/
const EditLeadPipelineEntryForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLeadPipelineEntry.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\EditLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/EditLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/{record}/edit'
*/
EditLeadPipelineEntryForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLeadPipelineEntry.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages\EditLeadPipelineEntry::__invoke
* @see app/Filament/TitanNexus/Resources/LeadPipelineEntryResource/Pages/EditLeadPipelineEntry.php:7
* @route '/titannexus/lead-pipeline/{record}/edit'
*/
EditLeadPipelineEntryForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLeadPipelineEntry.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditLeadPipelineEntry.form = EditLeadPipelineEntryForm

export default EditLeadPipelineEntry