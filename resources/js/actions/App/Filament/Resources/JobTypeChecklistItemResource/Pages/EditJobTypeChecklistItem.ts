import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/titanpro/job-type-checklist-items/{record}/edit'
*/
const EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303.url(args, options),
    method: 'get',
})

EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303.definition = {
    methods: ["get","head"],
    url: '/titanpro/job-type-checklist-items/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/titanpro/job-type-checklist-items/{record}/edit'
*/
EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/titanpro/job-type-checklist-items/{record}/edit'
*/
EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/titanpro/job-type-checklist-items/{record}/edit'
*/
EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/titanpro/job-type-checklist-items/{record}/edit'
*/
const EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/titanpro/job-type-checklist-items/{record}/edit'
*/
EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/titanpro/job-type-checklist-items/{record}/edit'
*/
EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303.form = EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303Form
/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/titanstudio/job-type-checklist-items/{record}/edit'
*/
const EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8.url(args, options),
    method: 'get',
})

EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8.definition = {
    methods: ["get","head"],
    url: '/titanstudio/job-type-checklist-items/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/titanstudio/job-type-checklist-items/{record}/edit'
*/
EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/titanstudio/job-type-checklist-items/{record}/edit'
*/
EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/titanstudio/job-type-checklist-items/{record}/edit'
*/
EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/titanstudio/job-type-checklist-items/{record}/edit'
*/
const EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/titanstudio/job-type-checklist-items/{record}/edit'
*/
EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeChecklistItemResource\Pages\EditJobTypeChecklistItem::__invoke
* @see app/Filament/Resources/JobTypeChecklistItemResource/Pages/EditJobTypeChecklistItem.php:7
* @route '/titanstudio/job-type-checklist-items/{record}/edit'
*/
EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8.form = EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8Form

const EditJobTypeChecklistItem = {
    '/titanpro/job-type-checklist-items/{record}/edit': EditJobTypeChecklistItem27f7c8a01c3de60ed15ebac158c83303,
    '/titanstudio/job-type-checklist-items/{record}/edit': EditJobTypeChecklistItemcaa2cd9b2586890df583a93e702d76b8,
}

export default EditJobTypeChecklistItem