import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/admin/job-type-checklist-items/{record}/edit'
*/
const EditJobTypeChecklistItem = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJobTypeChecklistItem.url(args, options),
    method: 'get',
})

EditJobTypeChecklistItem.definition = {
    methods: ["get","head"],
    url: '/admin/job-type-checklist-items/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/admin/job-type-checklist-items/{record}/edit'
*/
EditJobTypeChecklistItem.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditJobTypeChecklistItem.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/admin/job-type-checklist-items/{record}/edit'
*/
EditJobTypeChecklistItem.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJobTypeChecklistItem.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/admin/job-type-checklist-items/{record}/edit'
*/
EditJobTypeChecklistItem.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditJobTypeChecklistItem.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/admin/job-type-checklist-items/{record}/edit'
*/
const EditJobTypeChecklistItemForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobTypeChecklistItem.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/admin/job-type-checklist-items/{record}/edit'
*/
EditJobTypeChecklistItemForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobTypeChecklistItem.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/admin/job-type-checklist-items/{record}/edit'
*/
EditJobTypeChecklistItemForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobTypeChecklistItem.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditJobTypeChecklistItem.form = EditJobTypeChecklistItemForm

export default EditJobTypeChecklistItem