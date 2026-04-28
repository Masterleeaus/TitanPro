import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\CreateJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/CreateJobChecklistItem.php:7
* @route '/admin/job-checklist-items/create'
*/
const CreateJobChecklistItem = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateJobChecklistItem.url(options),
    method: 'get',
})

CreateJobChecklistItem.definition = {
    methods: ["get","head"],
    url: '/admin/job-checklist-items/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\CreateJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/CreateJobChecklistItem.php:7
* @route '/admin/job-checklist-items/create'
*/
CreateJobChecklistItem.url = (options?: RouteQueryOptions) => {
    return CreateJobChecklistItem.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\CreateJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/CreateJobChecklistItem.php:7
* @route '/admin/job-checklist-items/create'
*/
CreateJobChecklistItem.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateJobChecklistItem.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\CreateJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/CreateJobChecklistItem.php:7
* @route '/admin/job-checklist-items/create'
*/
CreateJobChecklistItem.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateJobChecklistItem.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\CreateJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/CreateJobChecklistItem.php:7
* @route '/admin/job-checklist-items/create'
*/
const CreateJobChecklistItemForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateJobChecklistItem.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\CreateJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/CreateJobChecklistItem.php:7
* @route '/admin/job-checklist-items/create'
*/
CreateJobChecklistItemForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateJobChecklistItem.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\CreateJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/CreateJobChecklistItem.php:7
* @route '/admin/job-checklist-items/create'
*/
CreateJobChecklistItemForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateJobChecklistItem.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateJobChecklistItem.form = CreateJobChecklistItemForm

export default CreateJobChecklistItem