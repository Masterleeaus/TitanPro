import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/admin/job-checklist-items'
*/
const ListJobChecklistItems = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobChecklistItems.url(options),
    method: 'get',
})

ListJobChecklistItems.definition = {
    methods: ["get","head"],
    url: '/admin/job-checklist-items',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/admin/job-checklist-items'
*/
ListJobChecklistItems.url = (options?: RouteQueryOptions) => {
    return ListJobChecklistItems.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/admin/job-checklist-items'
*/
ListJobChecklistItems.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobChecklistItems.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/admin/job-checklist-items'
*/
ListJobChecklistItems.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListJobChecklistItems.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/admin/job-checklist-items'
*/
const ListJobChecklistItemsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobChecklistItems.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/admin/job-checklist-items'
*/
ListJobChecklistItemsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobChecklistItems.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/admin/job-checklist-items'
*/
ListJobChecklistItemsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobChecklistItems.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListJobChecklistItems.form = ListJobChecklistItemsForm

export default ListJobChecklistItems