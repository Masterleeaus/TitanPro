import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\ItemResource\Pages\ListItems::__invoke
* @see app/Filament/Resources/ItemResource/Pages/ListItems.php:7
* @route '/titanpro/items'
*/
const ListItems = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListItems.url(options),
    method: 'get',
})

ListItems.definition = {
    methods: ["get","head"],
    url: '/titanpro/items',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\ItemResource\Pages\ListItems::__invoke
* @see app/Filament/Resources/ItemResource/Pages/ListItems.php:7
* @route '/titanpro/items'
*/
ListItems.url = (options?: RouteQueryOptions) => {
    return ListItems.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\ItemResource\Pages\ListItems::__invoke
* @see app/Filament/Resources/ItemResource/Pages/ListItems.php:7
* @route '/titanpro/items'
*/
ListItems.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListItems.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\ItemResource\Pages\ListItems::__invoke
* @see app/Filament/Resources/ItemResource/Pages/ListItems.php:7
* @route '/titanpro/items'
*/
ListItems.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListItems.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\ItemResource\Pages\ListItems::__invoke
* @see app/Filament/Resources/ItemResource/Pages/ListItems.php:7
* @route '/titanpro/items'
*/
const ListItemsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListItems.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\ItemResource\Pages\ListItems::__invoke
* @see app/Filament/Resources/ItemResource/Pages/ListItems.php:7
* @route '/titanpro/items'
*/
ListItemsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListItems.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\ItemResource\Pages\ListItems::__invoke
* @see app/Filament/Resources/ItemResource/Pages/ListItems.php:7
* @route '/titanpro/items'
*/
ListItemsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListItems.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListItems.form = ListItemsForm

export default ListItems