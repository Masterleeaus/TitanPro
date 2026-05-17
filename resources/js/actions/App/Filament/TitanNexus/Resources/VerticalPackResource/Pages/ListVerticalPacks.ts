import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ListVerticalPacks::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ListVerticalPacks.php:7
* @route '/titannexus/verticals'
*/
const ListVerticalPacks = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListVerticalPacks.url(options),
    method: 'get',
})

ListVerticalPacks.definition = {
    methods: ["get","head"],
    url: '/titannexus/verticals',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ListVerticalPacks::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ListVerticalPacks.php:7
* @route '/titannexus/verticals'
*/
ListVerticalPacks.url = (options?: RouteQueryOptions) => {
    return ListVerticalPacks.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ListVerticalPacks::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ListVerticalPacks.php:7
* @route '/titannexus/verticals'
*/
ListVerticalPacks.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListVerticalPacks.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ListVerticalPacks::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ListVerticalPacks.php:7
* @route '/titannexus/verticals'
*/
ListVerticalPacks.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListVerticalPacks.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ListVerticalPacks::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ListVerticalPacks.php:7
* @route '/titannexus/verticals'
*/
const ListVerticalPacksForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListVerticalPacks.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ListVerticalPacks::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ListVerticalPacks.php:7
* @route '/titannexus/verticals'
*/
ListVerticalPacksForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListVerticalPacks.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\ListVerticalPacks::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/ListVerticalPacks.php:7
* @route '/titannexus/verticals'
*/
ListVerticalPacksForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListVerticalPacks.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListVerticalPacks.form = ListVerticalPacksForm

export default ListVerticalPacks