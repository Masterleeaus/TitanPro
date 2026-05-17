import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\EditMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/EditMessageTemplate.php:7
* @route '/titanpro/message-templates/{record}/edit'
*/
const EditMessageTemplatec73612087d0955c4768bdd07b2f0e8ef = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditMessageTemplatec73612087d0955c4768bdd07b2f0e8ef.url(args, options),
    method: 'get',
})

EditMessageTemplatec73612087d0955c4768bdd07b2f0e8ef.definition = {
    methods: ["get","head"],
    url: '/titanpro/message-templates/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\EditMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/EditMessageTemplate.php:7
* @route '/titanpro/message-templates/{record}/edit'
*/
EditMessageTemplatec73612087d0955c4768bdd07b2f0e8ef.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditMessageTemplatec73612087d0955c4768bdd07b2f0e8ef.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\EditMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/EditMessageTemplate.php:7
* @route '/titanpro/message-templates/{record}/edit'
*/
EditMessageTemplatec73612087d0955c4768bdd07b2f0e8ef.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditMessageTemplatec73612087d0955c4768bdd07b2f0e8ef.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\EditMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/EditMessageTemplate.php:7
* @route '/titanpro/message-templates/{record}/edit'
*/
EditMessageTemplatec73612087d0955c4768bdd07b2f0e8ef.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditMessageTemplatec73612087d0955c4768bdd07b2f0e8ef.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\EditMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/EditMessageTemplate.php:7
* @route '/titanpro/message-templates/{record}/edit'
*/
const EditMessageTemplatec73612087d0955c4768bdd07b2f0e8efForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditMessageTemplatec73612087d0955c4768bdd07b2f0e8ef.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\EditMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/EditMessageTemplate.php:7
* @route '/titanpro/message-templates/{record}/edit'
*/
EditMessageTemplatec73612087d0955c4768bdd07b2f0e8efForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditMessageTemplatec73612087d0955c4768bdd07b2f0e8ef.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\EditMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/EditMessageTemplate.php:7
* @route '/titanpro/message-templates/{record}/edit'
*/
EditMessageTemplatec73612087d0955c4768bdd07b2f0e8efForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditMessageTemplatec73612087d0955c4768bdd07b2f0e8ef.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditMessageTemplatec73612087d0955c4768bdd07b2f0e8ef.form = EditMessageTemplatec73612087d0955c4768bdd07b2f0e8efForm
/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\EditMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/EditMessageTemplate.php:7
* @route '/titanstudio/message-templates/{record}/edit'
*/
const EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2e = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2e.url(args, options),
    method: 'get',
})

EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2e.definition = {
    methods: ["get","head"],
    url: '/titanstudio/message-templates/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\EditMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/EditMessageTemplate.php:7
* @route '/titanstudio/message-templates/{record}/edit'
*/
EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2e.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2e.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\EditMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/EditMessageTemplate.php:7
* @route '/titanstudio/message-templates/{record}/edit'
*/
EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2e.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2e.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\EditMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/EditMessageTemplate.php:7
* @route '/titanstudio/message-templates/{record}/edit'
*/
EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2e.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2e.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\EditMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/EditMessageTemplate.php:7
* @route '/titanstudio/message-templates/{record}/edit'
*/
const EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2eForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2e.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\EditMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/EditMessageTemplate.php:7
* @route '/titanstudio/message-templates/{record}/edit'
*/
EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2eForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2e.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\MessageTemplateResource\Pages\EditMessageTemplate::__invoke
* @see app/Filament/Resources/MessageTemplateResource/Pages/EditMessageTemplate.php:7
* @route '/titanstudio/message-templates/{record}/edit'
*/
EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2eForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2e.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2e.form = EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2eForm

const EditMessageTemplate = {
    '/titanpro/message-templates/{record}/edit': EditMessageTemplatec73612087d0955c4768bdd07b2f0e8ef,
    '/titanstudio/message-templates/{record}/edit': EditMessageTemplate972201cdf97d40b0afdeabcd5b242b2e,
}

export default EditMessageTemplate