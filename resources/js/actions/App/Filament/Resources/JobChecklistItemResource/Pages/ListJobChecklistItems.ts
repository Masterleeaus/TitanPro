import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/titanpro/job-checklist-items'
*/
const ListJobChecklistItemsa85106c6682172312f3b2741e6370280 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobChecklistItemsa85106c6682172312f3b2741e6370280.url(options),
    method: 'get',
})

ListJobChecklistItemsa85106c6682172312f3b2741e6370280.definition = {
    methods: ["get","head"],
    url: '/titanpro/job-checklist-items',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/titanpro/job-checklist-items'
*/
ListJobChecklistItemsa85106c6682172312f3b2741e6370280.url = (options?: RouteQueryOptions) => {
    return ListJobChecklistItemsa85106c6682172312f3b2741e6370280.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/titanpro/job-checklist-items'
*/
ListJobChecklistItemsa85106c6682172312f3b2741e6370280.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobChecklistItemsa85106c6682172312f3b2741e6370280.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/titanpro/job-checklist-items'
*/
ListJobChecklistItemsa85106c6682172312f3b2741e6370280.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListJobChecklistItemsa85106c6682172312f3b2741e6370280.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/titanpro/job-checklist-items'
*/
const ListJobChecklistItemsa85106c6682172312f3b2741e6370280Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobChecklistItemsa85106c6682172312f3b2741e6370280.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/titanpro/job-checklist-items'
*/
ListJobChecklistItemsa85106c6682172312f3b2741e6370280Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobChecklistItemsa85106c6682172312f3b2741e6370280.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/titanpro/job-checklist-items'
*/
ListJobChecklistItemsa85106c6682172312f3b2741e6370280Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobChecklistItemsa85106c6682172312f3b2741e6370280.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListJobChecklistItemsa85106c6682172312f3b2741e6370280.form = ListJobChecklistItemsa85106c6682172312f3b2741e6370280Form
/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/titanstudio/job-checklist-items'
*/
const ListJobChecklistItemsbe523728c330a160b05623a9888b9e21 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobChecklistItemsbe523728c330a160b05623a9888b9e21.url(options),
    method: 'get',
})

ListJobChecklistItemsbe523728c330a160b05623a9888b9e21.definition = {
    methods: ["get","head"],
    url: '/titanstudio/job-checklist-items',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/titanstudio/job-checklist-items'
*/
ListJobChecklistItemsbe523728c330a160b05623a9888b9e21.url = (options?: RouteQueryOptions) => {
    return ListJobChecklistItemsbe523728c330a160b05623a9888b9e21.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/titanstudio/job-checklist-items'
*/
ListJobChecklistItemsbe523728c330a160b05623a9888b9e21.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobChecklistItemsbe523728c330a160b05623a9888b9e21.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/titanstudio/job-checklist-items'
*/
ListJobChecklistItemsbe523728c330a160b05623a9888b9e21.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListJobChecklistItemsbe523728c330a160b05623a9888b9e21.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/titanstudio/job-checklist-items'
*/
const ListJobChecklistItemsbe523728c330a160b05623a9888b9e21Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobChecklistItemsbe523728c330a160b05623a9888b9e21.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/titanstudio/job-checklist-items'
*/
ListJobChecklistItemsbe523728c330a160b05623a9888b9e21Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobChecklistItemsbe523728c330a160b05623a9888b9e21.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\ListJobChecklistItems::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/ListJobChecklistItems.php:7
* @route '/titanstudio/job-checklist-items'
*/
ListJobChecklistItemsbe523728c330a160b05623a9888b9e21Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobChecklistItemsbe523728c330a160b05623a9888b9e21.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListJobChecklistItemsbe523728c330a160b05623a9888b9e21.form = ListJobChecklistItemsbe523728c330a160b05623a9888b9e21Form

const ListJobChecklistItems = {
    '/titanpro/job-checklist-items': ListJobChecklistItemsa85106c6682172312f3b2741e6370280,
    '/titanstudio/job-checklist-items': ListJobChecklistItemsbe523728c330a160b05623a9888b9e21,
}

export default ListJobChecklistItems