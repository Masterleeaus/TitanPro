import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/EditJob.php:7
* @route '/admin/jobs/{record}/edit'
*/
const EditJob = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJob.url(args, options),
    method: 'get',
})

EditJob.definition = {
    methods: ["get","head"],
    url: '/admin/jobs/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/EditJob.php:7
* @route '/admin/jobs/{record}/edit'
*/
EditJob.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditJob.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/EditJob.php:7
* @route '/admin/jobs/{record}/edit'
*/
EditJob.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditJob.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/EditJob.php:7
* @route '/admin/jobs/{record}/edit'
*/
EditJob.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditJob.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/EditJob.php:7
* @route '/admin/jobs/{record}/edit'
*/
const EditJobForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJob.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/EditJob.php:7
* @route '/admin/jobs/{record}/edit'
*/
EditJobForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJob.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobResource\Pages\EditJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/EditJob.php:7
* @route '/admin/jobs/{record}/edit'
*/
EditJobForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditJob.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditJob.form = EditJobForm

export default EditJob