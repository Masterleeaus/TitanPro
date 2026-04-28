import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\AttachmentResource\Pages\ListAttachments::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/ListAttachments.php:7
* @route '/admin/attachments'
*/
const ListAttachments = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListAttachments.url(options),
    method: 'get',
})

ListAttachments.definition = {
    methods: ["get","head"],
    url: '/admin/attachments',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\ListAttachments::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/ListAttachments.php:7
* @route '/admin/attachments'
*/
ListAttachments.url = (options?: RouteQueryOptions) => {
    return ListAttachments.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\ListAttachments::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/ListAttachments.php:7
* @route '/admin/attachments'
*/
ListAttachments.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListAttachments.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\ListAttachments::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/ListAttachments.php:7
* @route '/admin/attachments'
*/
ListAttachments.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListAttachments.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\ListAttachments::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/ListAttachments.php:7
* @route '/admin/attachments'
*/
const ListAttachmentsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListAttachments.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\ListAttachments::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/ListAttachments.php:7
* @route '/admin/attachments'
*/
ListAttachmentsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListAttachments.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\ListAttachments::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/ListAttachments.php:7
* @route '/admin/attachments'
*/
ListAttachmentsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListAttachments.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListAttachments.form = ListAttachmentsForm

export default ListAttachments