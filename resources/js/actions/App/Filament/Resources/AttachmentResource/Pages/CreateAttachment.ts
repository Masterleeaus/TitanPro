import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\AttachmentResource\Pages\CreateAttachment::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/CreateAttachment.php:7
* @route '/admin/attachments/create'
*/
const CreateAttachment = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateAttachment.url(options),
    method: 'get',
})

CreateAttachment.definition = {
    methods: ["get","head"],
    url: '/admin/attachments/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\CreateAttachment::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/CreateAttachment.php:7
* @route '/admin/attachments/create'
*/
CreateAttachment.url = (options?: RouteQueryOptions) => {
    return CreateAttachment.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\CreateAttachment::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/CreateAttachment.php:7
* @route '/admin/attachments/create'
*/
CreateAttachment.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateAttachment.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\CreateAttachment::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/CreateAttachment.php:7
* @route '/admin/attachments/create'
*/
CreateAttachment.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateAttachment.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\CreateAttachment::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/CreateAttachment.php:7
* @route '/admin/attachments/create'
*/
const CreateAttachmentForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateAttachment.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\CreateAttachment::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/CreateAttachment.php:7
* @route '/admin/attachments/create'
*/
CreateAttachmentForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateAttachment.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\CreateAttachment::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/CreateAttachment.php:7
* @route '/admin/attachments/create'
*/
CreateAttachmentForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateAttachment.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateAttachment.form = CreateAttachmentForm

export default CreateAttachment