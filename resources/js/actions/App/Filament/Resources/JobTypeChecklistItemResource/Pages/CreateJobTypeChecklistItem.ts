import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\CreateJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/CreateJobTypeChecklistItem.php:7
* @route '/admin/job-type-checklist-items/create'
*/
const CreateJobTypeChecklistItem = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateJobTypeChecklistItem.url(options),
    method: 'get',
})

CreateJobTypeChecklistItem.definition = {
    methods: ["get","head"],
    url: '/admin/job-type-checklist-items/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\CreateJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/CreateJobTypeChecklistItem.php:7
* @route '/admin/job-type-checklist-items/create'
*/
CreateJobTypeChecklistItem.url = (options?: RouteQueryOptions) => {
    return CreateJobTypeChecklistItem.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\CreateJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/CreateJobTypeChecklistItem.php:7
* @route '/admin/job-type-checklist-items/create'
*/
CreateJobTypeChecklistItem.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateJobTypeChecklistItem.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\CreateJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/CreateJobTypeChecklistItem.php:7
* @route '/admin/job-type-checklist-items/create'
*/
CreateJobTypeChecklistItem.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateJobTypeChecklistItem.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\CreateJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/CreateJobTypeChecklistItem.php:7
* @route '/admin/job-type-checklist-items/create'
*/
const CreateJobTypeChecklistItemForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateJobTypeChecklistItem.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\CreateJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/CreateJobTypeChecklistItem.php:7
* @route '/admin/job-type-checklist-items/create'
*/
CreateJobTypeChecklistItemForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateJobTypeChecklistItem.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\CreateJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/CreateJobTypeChecklistItem.php:7
* @route '/admin/job-type-checklist-items/create'
*/
CreateJobTypeChecklistItemForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateJobTypeChecklistItem.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateJobTypeChecklistItem.form = CreateJobTypeChecklistItemForm

export default CreateJobTypeChecklistItem