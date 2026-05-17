import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.ts
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titansolo/jobs'
========
* @see \App\Filament\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titanpro/jobs'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/ListJobs.ts
*/
const ListJobs = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobs.url(options),
    method: 'get',
})

ListJobs.definition = {
    methods: ["get","head"],
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.ts
    url: '/titansolo/jobs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titansolo/jobs'
========
    url: '/titanpro/jobs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titanpro/jobs'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/ListJobs.ts
*/
ListJobs.url = (options?: RouteQueryOptions) => {
    return ListJobs.definition.url + queryParams(options)
}

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.ts
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titansolo/jobs'
========
* @see \App\Filament\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titanpro/jobs'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/ListJobs.ts
*/
ListJobs.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobs.url(options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.ts
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titansolo/jobs'
========
* @see \App\Filament\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titanpro/jobs'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/ListJobs.ts
*/
ListJobs.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListJobs.url(options),
    method: 'head',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.ts
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titansolo/jobs'
========
* @see \App\Filament\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titanpro/jobs'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/ListJobs.ts
*/
const ListJobsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobs.url(options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.ts
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titansolo/jobs'
========
* @see \App\Filament\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titanpro/jobs'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/ListJobs.ts
*/
ListJobsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobs.url(options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.ts
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titansolo/jobs'
========
* @see \App\Filament\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titanpro/jobs'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/JobResource/Pages/ListJobs.ts
*/
ListJobsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobs.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListJobs.form = ListJobsForm

export default ListJobs