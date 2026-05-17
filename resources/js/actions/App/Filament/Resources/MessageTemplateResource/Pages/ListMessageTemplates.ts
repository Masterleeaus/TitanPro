import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\ListMessageTemplates::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/ListMessageTemplates.php:7
* @route '/titanpro/message-templates'
*/
const ListMessageTemplates3b017a90190f86d924fd2f487547d28a = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListMessageTemplates3b017a90190f86d924fd2f487547d28a.url(options),
    method: 'get',
})

ListMessageTemplates3b017a90190f86d924fd2f487547d28a.definition = {
    methods: ["get","head"],
    url: '/titanpro/message-templates',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\ListMessageTemplates::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/ListMessageTemplates.php:7
* @route '/titanpro/message-templates'
*/
ListMessageTemplates3b017a90190f86d924fd2f487547d28a.url = (options?: RouteQueryOptions) => {
    return ListMessageTemplates3b017a90190f86d924fd2f487547d28a.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\ListMessageTemplates::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/ListMessageTemplates.php:7
* @route '/titanpro/message-templates'
*/
ListMessageTemplates3b017a90190f86d924fd2f487547d28a.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListMessageTemplates3b017a90190f86d924fd2f487547d28a.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\ListMessageTemplates::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/ListMessageTemplates.php:7
* @route '/titanpro/message-templates'
*/
ListMessageTemplates3b017a90190f86d924fd2f487547d28a.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListMessageTemplates3b017a90190f86d924fd2f487547d28a.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\ListMessageTemplates::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/ListMessageTemplates.php:7
* @route '/titanpro/message-templates'
*/
const ListMessageTemplates3b017a90190f86d924fd2f487547d28aForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMessageTemplates3b017a90190f86d924fd2f487547d28a.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\ListMessageTemplates::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/ListMessageTemplates.php:7
* @route '/titanpro/message-templates'
*/
ListMessageTemplates3b017a90190f86d924fd2f487547d28aForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMessageTemplates3b017a90190f86d924fd2f487547d28a.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\ListMessageTemplates::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/ListMessageTemplates.php:7
* @route '/titanpro/message-templates'
*/
ListMessageTemplates3b017a90190f86d924fd2f487547d28aForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMessageTemplates3b017a90190f86d924fd2f487547d28a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListMessageTemplates3b017a90190f86d924fd2f487547d28a.form = ListMessageTemplates3b017a90190f86d924fd2f487547d28aForm
/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\ListMessageTemplates::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/ListMessageTemplates.php:7
* @route '/titanstudio/message-templates'
*/
const ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075.url(options),
    method: 'get',
})

ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075.definition = {
    methods: ["get","head"],
    url: '/titanstudio/message-templates',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\ListMessageTemplates::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/ListMessageTemplates.php:7
* @route '/titanstudio/message-templates'
*/
ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075.url = (options?: RouteQueryOptions) => {
    return ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\ListMessageTemplates::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/ListMessageTemplates.php:7
* @route '/titanstudio/message-templates'
*/
ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\ListMessageTemplates::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/ListMessageTemplates.php:7
* @route '/titanstudio/message-templates'
*/
ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\ListMessageTemplates::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/ListMessageTemplates.php:7
* @route '/titanstudio/message-templates'
*/
const ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\ListMessageTemplates::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/ListMessageTemplates.php:7
* @route '/titanstudio/message-templates'
*/
ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\ListMessageTemplates::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/ListMessageTemplates.php:7
* @route '/titanstudio/message-templates'
*/
ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075.form = ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075Form

const ListMessageTemplates = {
    '/titanpro/message-templates': ListMessageTemplates3b017a90190f86d924fd2f487547d28a,
    '/titanstudio/message-templates': ListMessageTemplatesf9b2162c369a66d070697ec57c4d2075,
}

export default ListMessageTemplates