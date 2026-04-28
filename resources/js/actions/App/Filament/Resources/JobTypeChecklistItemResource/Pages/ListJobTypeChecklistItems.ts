import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\ListJobTypeChecklistItems::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/ListJobTypeChecklistItems.php:7
* @route '/admin/job-type-checklist-items'
*/
const ListJobTypeChecklistItems = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobTypeChecklistItems.url(options),
    method: 'get',
})

ListJobTypeChecklistItems.definition = {
    methods: ["get","head"],
    url: '/admin/job-type-checklist-items',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\ListJobTypeChecklistItems::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/ListJobTypeChecklistItems.php:7
* @route '/admin/job-type-checklist-items'
*/
ListJobTypeChecklistItems.url = (options?: RouteQueryOptions) => {
    return ListJobTypeChecklistItems.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\ListJobTypeChecklistItems::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/ListJobTypeChecklistItems.php:7
* @route '/admin/job-type-checklist-items'
*/
ListJobTypeChecklistItems.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobTypeChecklistItems.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\ListJobTypeChecklistItems::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/ListJobTypeChecklistItems.php:7
* @route '/admin/job-type-checklist-items'
*/
ListJobTypeChecklistItems.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListJobTypeChecklistItems.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\ListJobTypeChecklistItems::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/ListJobTypeChecklistItems.php:7
* @route '/admin/job-type-checklist-items'
*/
const ListJobTypeChecklistItemsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobTypeChecklistItems.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\ListJobTypeChecklistItems::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/ListJobTypeChecklistItems.php:7
* @route '/admin/job-type-checklist-items'
*/
ListJobTypeChecklistItemsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobTypeChecklistItems.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\ListJobTypeChecklistItems::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/ListJobTypeChecklistItems.php:7
* @route '/admin/job-type-checklist-items'
*/
ListJobTypeChecklistItemsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobTypeChecklistItems.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListJobTypeChecklistItems.form = ListJobTypeChecklistItemsForm

export default ListJobTypeChecklistItems