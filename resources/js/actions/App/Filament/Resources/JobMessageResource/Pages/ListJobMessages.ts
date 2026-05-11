import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\JobMessageResource\Pages\ListJobMessages::__invoke
* @see app/Filament/Resources/JobMessageResource/Pages/ListJobMessages.php:7
* @route '/admin/job-messages'
*/
const ListJobMessages = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobMessages.url(options),
    method: 'get',
})

ListJobMessages.definition = {
    methods: ["get","head"],
    url: '/admin/job-messages',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobMessageResource\Pages\ListJobMessages::__invoke
* @see app/Filament/Resources/JobMessageResource/Pages/ListJobMessages.php:7
* @route '/admin/job-messages'
*/
ListJobMessages.url = (options?: RouteQueryOptions) => {
    return ListJobMessages.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobMessageResource\Pages\ListJobMessages::__invoke
* @see app/Filament/Resources/JobMessageResource/Pages/ListJobMessages.php:7
* @route '/admin/job-messages'
*/
ListJobMessages.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobMessages.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobMessageResource\Pages\ListJobMessages::__invoke
* @see app/Filament/Resources/JobMessageResource/Pages/ListJobMessages.php:7
* @route '/admin/job-messages'
*/
ListJobMessages.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListJobMessages.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobMessageResource\Pages\ListJobMessages::__invoke
* @see app/Filament/Resources/JobMessageResource/Pages/ListJobMessages.php:7
* @route '/admin/job-messages'
*/
const ListJobMessagesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobMessages.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobMessageResource\Pages\ListJobMessages::__invoke
* @see app/Filament/Resources/JobMessageResource/Pages/ListJobMessages.php:7
* @route '/admin/job-messages'
*/
ListJobMessagesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobMessages.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobMessageResource\Pages\ListJobMessages::__invoke
* @see app/Filament/Resources/JobMessageResource/Pages/ListJobMessages.php:7
* @route '/admin/job-messages'
*/
ListJobMessagesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobMessages.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListJobMessages.form = ListJobMessagesForm

export default ListJobMessages