import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/titanpro/job-checklist-items/{record}/edit'
*/
const EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437c = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437c.url(args, options),
    method: 'get',
})

EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437c.definition = {
    methods: ["get","head"],
    url: '/titanpro/job-checklist-items/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/titanpro/job-checklist-items/{record}/edit'
*/
EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437c.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437c.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/titanpro/job-checklist-items/{record}/edit'
*/
EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437c.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437c.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/titanpro/job-checklist-items/{record}/edit'
*/
EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437c.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437c.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/titanpro/job-checklist-items/{record}/edit'
*/
const EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437cForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437c.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/titanpro/job-checklist-items/{record}/edit'
*/
EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437cForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437c.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/titanpro/job-checklist-items/{record}/edit'
*/
EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437cForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437c.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437c.form = EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437cForm
/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/titanstudio/job-checklist-items/{record}/edit'
*/
const EditJobChecklistItem9f14988d72da421f112b20f2772b9616 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJobChecklistItem9f14988d72da421f112b20f2772b9616.url(args, options),
    method: 'get',
})

EditJobChecklistItem9f14988d72da421f112b20f2772b9616.definition = {
    methods: ["get","head"],
    url: '/titanstudio/job-checklist-items/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/titanstudio/job-checklist-items/{record}/edit'
*/
EditJobChecklistItem9f14988d72da421f112b20f2772b9616.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditJobChecklistItem9f14988d72da421f112b20f2772b9616.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/titanstudio/job-checklist-items/{record}/edit'
*/
EditJobChecklistItem9f14988d72da421f112b20f2772b9616.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJobChecklistItem9f14988d72da421f112b20f2772b9616.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/titanstudio/job-checklist-items/{record}/edit'
*/
EditJobChecklistItem9f14988d72da421f112b20f2772b9616.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditJobChecklistItem9f14988d72da421f112b20f2772b9616.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/titanstudio/job-checklist-items/{record}/edit'
*/
const EditJobChecklistItem9f14988d72da421f112b20f2772b9616Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobChecklistItem9f14988d72da421f112b20f2772b9616.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/titanstudio/job-checklist-items/{record}/edit'
*/
EditJobChecklistItem9f14988d72da421f112b20f2772b9616Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobChecklistItem9f14988d72da421f112b20f2772b9616.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobChecklistItemResource\Pages\EditJobChecklistItem::__invoke
* @see app/Filament/Resources/JobChecklistItemResource/Pages/EditJobChecklistItem.php:7
* @route '/titanstudio/job-checklist-items/{record}/edit'
*/
EditJobChecklistItem9f14988d72da421f112b20f2772b9616Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobChecklistItem9f14988d72da421f112b20f2772b9616.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditJobChecklistItem9f14988d72da421f112b20f2772b9616.form = EditJobChecklistItem9f14988d72da421f112b20f2772b9616Form

const EditJobChecklistItem = {
    '/titanpro/job-checklist-items/{record}/edit': EditJobChecklistItemd7ed884a8d3fe3b6f6ac0f73c966437c,
    '/titanstudio/job-checklist-items/{record}/edit': EditJobChecklistItem9f14988d72da421f112b20f2772b9616,
}

export default EditJobChecklistItem