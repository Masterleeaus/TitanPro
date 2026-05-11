import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanQuotes\Resources\ItemResource\Pages\CreateItem::__invoke
* @see app/Filament/TitanQuotes/Resources/ItemResource/Pages/CreateItem.php:7
* @route '/titanquotes/items/create'
*/
const CreateItem = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateItem.url(options),
    method: 'get',
})

CreateItem.definition = {
    methods: ["get","head"],
    url: '/titanquotes/items/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanQuotes\Resources\ItemResource\Pages\CreateItem::__invoke
* @see app/Filament/TitanQuotes/Resources/ItemResource/Pages/CreateItem.php:7
* @route '/titanquotes/items/create'
*/
CreateItem.url = (options?: RouteQueryOptions) => {
    return CreateItem.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanQuotes\Resources\ItemResource\Pages\CreateItem::__invoke
* @see app/Filament/TitanQuotes/Resources/ItemResource/Pages/CreateItem.php:7
* @route '/titanquotes/items/create'
*/
CreateItem.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateItem.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Resources\ItemResource\Pages\CreateItem::__invoke
* @see app/Filament/TitanQuotes/Resources/ItemResource/Pages/CreateItem.php:7
* @route '/titanquotes/items/create'
*/
CreateItem.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateItem.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanQuotes\Resources\ItemResource\Pages\CreateItem::__invoke
* @see app/Filament/TitanQuotes/Resources/ItemResource/Pages/CreateItem.php:7
* @route '/titanquotes/items/create'
*/
const CreateItemForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateItem.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Resources\ItemResource\Pages\CreateItem::__invoke
* @see app/Filament/TitanQuotes/Resources/ItemResource/Pages/CreateItem.php:7
* @route '/titanquotes/items/create'
*/
CreateItemForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateItem.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Resources\ItemResource\Pages\CreateItem::__invoke
* @see app/Filament/TitanQuotes/Resources/ItemResource/Pages/CreateItem.php:7
* @route '/titanquotes/items/create'
*/
CreateItemForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateItem.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateItem.form = CreateItemForm

export default CreateItem