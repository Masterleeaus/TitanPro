import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\ItemResource\Pages\ListItems::__invoke
* @see app/Filament/Resources/ItemResource/Pages/ListItems.php:7
* @route '/admin/items'
*/
const ListItems = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListItems.url(options),
    method: 'get',
})

ListItems.definition = {
    methods: ["get","head"],
    url: '/admin/items',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\ItemResource\Pages\ListItems::__invoke
* @see app/Filament/Resources/ItemResource/Pages/ListItems.php:7
* @route '/admin/items'
*/
ListItems.url = (options?: RouteQueryOptions) => {
    return ListItems.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\ItemResource\Pages\ListItems::__invoke
* @see app/Filament/Resources/ItemResource/Pages/ListItems.php:7
* @route '/admin/items'
*/
ListItems.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListItems.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\ItemResource\Pages\ListItems::__invoke
* @see app/Filament/Resources/ItemResource/Pages/ListItems.php:7
* @route '/admin/items'
*/
ListItems.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListItems.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\ItemResource\Pages\ListItems::__invoke
* @see app/Filament/Resources/ItemResource/Pages/ListItems.php:7
* @route '/admin/items'
*/
const ListItemsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListItems.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\ItemResource\Pages\ListItems::__invoke
* @see app/Filament/Resources/ItemResource/Pages/ListItems.php:7
* @route '/admin/items'
*/
ListItemsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListItems.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\ItemResource\Pages\ListItems::__invoke
* @see app/Filament/Resources/ItemResource/Pages/ListItems.php:7
* @route '/admin/items'
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