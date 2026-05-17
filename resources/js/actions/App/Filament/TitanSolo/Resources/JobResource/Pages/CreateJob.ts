import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\CreateJob::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/CreateJob.php:7
* @route '/titansolo/jobs/create'
*/
const CreateJob = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateJob.url(options),
    method: 'get',
})

CreateJob.definition = {
    methods: ["get","head"],
    url: '/titansolo/jobs/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\CreateJob::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/CreateJob.php:7
* @route '/titansolo/jobs/create'
*/
CreateJob.url = (options?: RouteQueryOptions) => {
    return CreateJob.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\CreateJob::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/CreateJob.php:7
* @route '/titansolo/jobs/create'
*/
CreateJob.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateJob.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\CreateJob::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/CreateJob.php:7
* @route '/titansolo/jobs/create'
*/
CreateJob.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateJob.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\CreateJob::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/CreateJob.php:7
* @route '/titansolo/jobs/create'
*/
const CreateJobForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateJob.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\CreateJob::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/CreateJob.php:7
* @route '/titansolo/jobs/create'
*/
CreateJobForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateJob.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanSolo\Resources\JobResource\Pages\CreateJob::__invoke
* @see app/Filament/TitanSolo/Resources/JobResource/Pages/CreateJob.php:7
* @route '/titansolo/jobs/create'
*/
CreateJobForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateJob.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateJob.form = CreateJobForm

export default CreateJob