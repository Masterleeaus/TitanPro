import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titansolo/jobs'
*/
const ListJobs = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobs.url(options),
    method: 'get',
})

ListJobs.definition = {
    methods: ["get","head"],
    url: '/titansolo/jobs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titansolo/jobs'
*/
ListJobs.url = (options?: RouteQueryOptions) => {
    return ListJobs.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titansolo/jobs'
*/
ListJobs.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobs.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titansolo/jobs'
*/
ListJobs.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListJobs.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titansolo/jobs'
*/
const ListJobsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobs.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titansolo/jobs'
*/
ListJobsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobs.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\ListJobs::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/ListJobs.php:7
* @route '/titansolo/jobs'
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