import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\JobMessageResource\Pages\CreateJobMessage::__invoke
* @see app/Filament/Resources/JobMessageResource/Pages/CreateJobMessage.php:7
* @route '/admin/job-messages/create'
*/
const CreateJobMessage = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateJobMessage.url(options),
    method: 'get',
})

CreateJobMessage.definition = {
    methods: ["get","head"],
    url: '/admin/job-messages/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobMessageResource\Pages\CreateJobMessage::__invoke
* @see app/Filament/Resources/JobMessageResource/Pages/CreateJobMessage.php:7
* @route '/admin/job-messages/create'
*/
CreateJobMessage.url = (options?: RouteQueryOptions) => {
    return CreateJobMessage.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobMessageResource\Pages\CreateJobMessage::__invoke
* @see app/Filament/Resources/JobMessageResource/Pages/CreateJobMessage.php:7
* @route '/admin/job-messages/create'
*/
CreateJobMessage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateJobMessage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobMessageResource\Pages\CreateJobMessage::__invoke
* @see app/Filament/Resources/JobMessageResource/Pages/CreateJobMessage.php:7
* @route '/admin/job-messages/create'
*/
CreateJobMessage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateJobMessage.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobMessageResource\Pages\CreateJobMessage::__invoke
* @see app/Filament/Resources/JobMessageResource/Pages/CreateJobMessage.php:7
* @route '/admin/job-messages/create'
*/
const CreateJobMessageForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateJobMessage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobMessageResource\Pages\CreateJobMessage::__invoke
* @see app/Filament/Resources/JobMessageResource/Pages/CreateJobMessage.php:7
* @route '/admin/job-messages/create'
*/
CreateJobMessageForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateJobMessage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobMessageResource\Pages\CreateJobMessage::__invoke
* @see app/Filament/Resources/JobMessageResource/Pages/CreateJobMessage.php:7
* @route '/admin/job-messages/create'
*/
CreateJobMessageForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateJobMessage.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateJobMessage.form = CreateJobMessageForm

export default CreateJobMessage