import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Pages\Verticals::__invoke
* @see app/Filament/TitanNexus/Pages/Verticals.php:7
* @route '/titannexus/verticals'
*/
const Verticals = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Verticals.url(options),
    method: 'get',
})

Verticals.definition = {
    methods: ["get","head"],
    url: '/titannexus/verticals',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Pages\Verticals::__invoke
* @see app/Filament/TitanNexus/Pages/Verticals.php:7
* @route '/titannexus/verticals'
*/
Verticals.url = (options?: RouteQueryOptions) => {
    return Verticals.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Pages\Verticals::__invoke
* @see app/Filament/TitanNexus/Pages/Verticals.php:7
* @route '/titannexus/verticals'
*/
Verticals.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Verticals.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\Verticals::__invoke
* @see app/Filament/TitanNexus/Pages/Verticals.php:7
* @route '/titannexus/verticals'
*/
Verticals.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Verticals.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Pages\Verticals::__invoke
* @see app/Filament/TitanNexus/Pages/Verticals.php:7
* @route '/titannexus/verticals'
*/
const VerticalsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Verticals.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\Verticals::__invoke
* @see app/Filament/TitanNexus/Pages/Verticals.php:7
* @route '/titannexus/verticals'
*/
VerticalsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Verticals.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\Verticals::__invoke
* @see app/Filament/TitanNexus/Pages/Verticals.php:7
* @route '/titannexus/verticals'
*/
VerticalsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Verticals.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Verticals.form = VerticalsForm

export default Verticals