import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\CreateMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/CreateMessageTemplate.php:7
* @route '/admin/message-templates/create'
*/
const CreateMessageTemplate = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateMessageTemplate.url(options),
    method: 'get',
})

CreateMessageTemplate.definition = {
    methods: ["get","head"],
    url: '/admin/message-templates/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\CreateMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/CreateMessageTemplate.php:7
* @route '/admin/message-templates/create'
*/
CreateMessageTemplate.url = (options?: RouteQueryOptions) => {
    return CreateMessageTemplate.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\CreateMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/CreateMessageTemplate.php:7
* @route '/admin/message-templates/create'
*/
CreateMessageTemplate.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateMessageTemplate.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\CreateMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/CreateMessageTemplate.php:7
* @route '/admin/message-templates/create'
*/
CreateMessageTemplate.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateMessageTemplate.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\CreateMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/CreateMessageTemplate.php:7
* @route '/admin/message-templates/create'
*/
const CreateMessageTemplateForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateMessageTemplate.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\CreateMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/CreateMessageTemplate.php:7
* @route '/admin/message-templates/create'
*/
CreateMessageTemplateForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateMessageTemplate.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\CreateMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/CreateMessageTemplate.php:7
* @route '/admin/message-templates/create'
*/
CreateMessageTemplateForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateMessageTemplate.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateMessageTemplate.form = CreateMessageTemplateForm

export default CreateMessageTemplate