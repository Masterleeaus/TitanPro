import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\JobTypeResource\Pages\ListJobTypes::__invoke
* @see app/Filament/Resources/JobTypeResource/Pages/ListJobTypes.php:7
* @route '/admin/job-types'
*/
const ListJobTypes = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobTypes.url(options),
    method: 'get',
})

ListJobTypes.definition = {
    methods: ["get","head"],
    url: '/admin/job-types',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\JobTypeResource\Pages\ListJobTypes::__invoke
* @see app/Filament/Resources/JobTypeResource/Pages/ListJobTypes.php:7
* @route '/admin/job-types'
*/
ListJobTypes.url = (options?: RouteQueryOptions) => {
    return ListJobTypes.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\JobTypeResource\Pages\ListJobTypes::__invoke
* @see app/Filament/Resources/JobTypeResource/Pages/ListJobTypes.php:7
* @route '/admin/job-types'
*/
ListJobTypes.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListJobTypes.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeResource\Pages\ListJobTypes::__invoke
* @see app/Filament/Resources/JobTypeResource/Pages/ListJobTypes.php:7
* @route '/admin/job-types'
*/
ListJobTypes.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListJobTypes.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\JobTypeResource\Pages\ListJobTypes::__invoke
* @see app/Filament/Resources/JobTypeResource/Pages/ListJobTypes.php:7
* @route '/admin/job-types'
*/
const ListJobTypesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobTypes.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeResource\Pages\ListJobTypes::__invoke
* @see app/Filament/Resources/JobTypeResource/Pages/ListJobTypes.php:7
* @route '/admin/job-types'
*/
ListJobTypesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobTypes.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\JobTypeResource\Pages\ListJobTypes::__invoke
* @see app/Filament/Resources/JobTypeResource/Pages/ListJobTypes.php:7
* @route '/admin/job-types'
*/
ListJobTypesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListJobTypes.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListJobTypes.form = ListJobTypesForm

export default ListJobTypes