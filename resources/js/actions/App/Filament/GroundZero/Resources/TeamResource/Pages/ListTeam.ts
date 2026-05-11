import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\GroundZero\Resources\TeamResource\Pages\ListTeam::__invoke
* @see app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php:7
* @route '/groundzero/team'
*/
const ListTeam = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListTeam.url(options),
    method: 'get',
})

ListTeam.definition = {
    methods: ["get","head"],
    url: '/groundzero/team',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\GroundZero\Resources\TeamResource\Pages\ListTeam::__invoke
* @see app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php:7
* @route '/groundzero/team'
*/
ListTeam.url = (options?: RouteQueryOptions) => {
    return ListTeam.definition.url + queryParams(options)
}

/**
* @see \App\Filament\GroundZero\Resources\TeamResource\Pages\ListTeam::__invoke
* @see app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php:7
* @route '/groundzero/team'
*/
ListTeam.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListTeam.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Resources\TeamResource\Pages\ListTeam::__invoke
* @see app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php:7
* @route '/groundzero/team'
*/
ListTeam.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListTeam.url(options),
    method: 'head',
})

/**
* @see \App\Filament\GroundZero\Resources\TeamResource\Pages\ListTeam::__invoke
* @see app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php:7
* @route '/groundzero/team'
*/
const ListTeamForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListTeam.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Resources\TeamResource\Pages\ListTeam::__invoke
* @see app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php:7
* @route '/groundzero/team'
*/
ListTeamForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListTeam.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Resources\TeamResource\Pages\ListTeam::__invoke
* @see app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php:7
* @route '/groundzero/team'
*/
ListTeamForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListTeam.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListTeam.form = ListTeamForm

export default ListTeam