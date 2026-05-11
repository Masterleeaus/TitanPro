import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/admin/job-checklist-items/{record}/edit'
*/
const EditJobChecklistItem = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJobChecklistItem.url(args, options),
    method: 'get',
})

EditJobChecklistItem.definition = {
    methods: ["get","head"],
    url: '/admin/job-checklist-items/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/admin/job-checklist-items/{record}/edit'
*/
EditJobChecklistItem.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditJobChecklistItem.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/admin/job-checklist-items/{record}/edit'
*/
EditJobChecklistItem.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJobChecklistItem.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/admin/job-checklist-items/{record}/edit'
*/
EditJobChecklistItem.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditJobChecklistItem.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/admin/job-checklist-items/{record}/edit'
*/
const EditJobChecklistItemForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobChecklistItem.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/admin/job-checklist-items/{record}/edit'
*/
EditJobChecklistItemForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobChecklistItem.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/admin/job-checklist-items/{record}/edit'
*/
EditJobChecklistItemForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobChecklistItem.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditJobChecklistItem.form = EditJobChecklistItemForm

export default EditJobChecklistItem