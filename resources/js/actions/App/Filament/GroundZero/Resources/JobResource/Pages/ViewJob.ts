import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.ts
* @see \App\Filament\GroundZero\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.php:7
* @route '/groundzero/jobs/{record}'
========
* @see \App\Filament\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/ViewJob.php:7
* @route '/titanpro/jobs/{record}'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/ViewJob.ts
*/
const ViewJob = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewJob.url(args, options),
    method: 'get',
})

ViewJob.definition = {
    methods: ["get","head"],
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.ts
    url: '/groundzero/jobs/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\GroundZero\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.php:7
* @route '/groundzero/jobs/{record}'
========
    url: '/titanpro/jobs/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/ViewJob.php:7
* @route '/titanpro/jobs/{record}'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/ViewJob.ts
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
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.ts
* @see \App\Filament\GroundZero\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.php:7
* @route '/groundzero/jobs/{record}'
========
* @see \App\Filament\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/ViewJob.php:7
* @route '/titanpro/jobs/{record}'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/ViewJob.ts
*/
ViewJob.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewJob.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.ts
* @see \App\Filament\GroundZero\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.php:7
* @route '/groundzero/jobs/{record}'
========
* @see \App\Filament\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/ViewJob.php:7
* @route '/titanpro/jobs/{record}'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/ViewJob.ts
*/
ViewJob.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewJob.url(args, options),
    method: 'head',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.ts
* @see \App\Filament\GroundZero\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.php:7
* @route '/groundzero/jobs/{record}'
========
* @see \App\Filament\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/ViewJob.php:7
* @route '/titanpro/jobs/{record}'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/ViewJob.ts
*/
const ViewJobForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewJob.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.ts
* @see \App\Filament\GroundZero\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.php:7
* @route '/groundzero/jobs/{record}'
========
* @see \App\Filament\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/ViewJob.php:7
* @route '/titanpro/jobs/{record}'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/ViewJob.ts
*/
ViewJobForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewJob.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.ts
* @see \App\Filament\GroundZero\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.php:7
* @route '/groundzero/jobs/{record}'
========
* @see \App\Filament\Resources\JobResource\Pages\ViewJob::__invoke
* @see app/Filament/Resources/JobResource/Pages/ViewJob.php:7
* @route '/titanpro/jobs/{record}'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/ViewJob.ts
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