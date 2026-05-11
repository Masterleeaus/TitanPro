import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/ViewJob.php:7
* @route '/admin/jobs/{record}'
*/
const ViewJob = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewJob.url(args, options),
    method: 'get',
})

ViewJob.definition = {
    methods: ["get","head"],
    url: '/admin/jobs/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/ViewJob.php:7
* @route '/admin/jobs/{record}'
*/
ViewJob.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ViewJob.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/ViewJob.php:7
* @route '/admin/jobs/{record}'
*/
ViewJob.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewJob.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/ViewJob.php:7
* @route '/admin/jobs/{record}'
*/
ViewJob.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewJob.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/ViewJob.php:7
* @route '/admin/jobs/{record}'
*/
const ViewJobForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewJob.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/ViewJob.php:7
* @route '/admin/jobs/{record}'
*/
ViewJobForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewJob.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/ViewJob.php:7
* @route '/admin/jobs/{record}'
*/
ViewJobForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewJob.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ViewJob.form = ViewJobForm

export default ViewJob