import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\AttachmentResource\Pages\EditAttachment::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/EditAttachment.php:7
* @route '/admin/attachments/{record}/edit'
*/
const EditAttachment = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditAttachment.url(args, options),
    method: 'get',
})

EditAttachment.definition = {
    methods: ["get","head"],
    url: '/admin/attachments/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\EditAttachment::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/EditAttachment.php:7
* @route '/admin/attachments/{record}/edit'
*/
EditAttachment.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { record: args }
    }

    if (Array.isArray(args)) {
        args = {
            record: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        record: args.record,
    }

    return EditAttachment.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\EditAttachment::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/EditAttachment.php:7
* @route '/admin/attachments/{record}/edit'
*/
EditAttachment.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditAttachment.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\EditAttachment::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/EditAttachment.php:7
* @route '/admin/attachments/{record}/edit'
*/
EditAttachment.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditAttachment.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\EditAttachment::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/EditAttachment.php:7
* @route '/admin/attachments/{record}/edit'
*/
const EditAttachmentForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditAttachment.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\EditAttachment::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/EditAttachment.php:7
* @route '/admin/attachments/{record}/edit'
*/
EditAttachmentForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditAttachment.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\AttachmentResource\Pages\EditAttachment::__invoke
* @see app/Filament/Resources/AttachmentResource/Pages/EditAttachment.php:7
* @route '/admin/attachments/{record}/edit'
*/
EditAttachmentForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditAttachment.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditAttachment.form = EditAttachmentForm

export default EditAttachment