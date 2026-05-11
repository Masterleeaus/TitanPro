import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\GroundZero\Resources\TeamResource\Pages\ListTeam::__invoke
* @see app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php:7
* @route '/groundzero/team'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/groundzero/team',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\GroundZero\Resources\TeamResource\Pages\ListTeam::__invoke
* @see app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php:7
* @route '/groundzero/team'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\GroundZero\Resources\TeamResource\Pages\ListTeam::__invoke
* @see app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php:7
* @route '/groundzero/team'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Resources\TeamResource\Pages\ListTeam::__invoke
* @see app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php:7
* @route '/groundzero/team'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Filament\GroundZero\Resources\TeamResource\Pages\ListTeam::__invoke
* @see app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php:7
* @route '/groundzero/team'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Resources\TeamResource\Pages\ListTeam::__invoke
* @see app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php:7
* @route '/groundzero/team'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Resources\TeamResource\Pages\ListTeam::__invoke
* @see app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php:7
* @route '/groundzero/team'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

const team = {
    index: Object.assign(index, index),
}

export default team