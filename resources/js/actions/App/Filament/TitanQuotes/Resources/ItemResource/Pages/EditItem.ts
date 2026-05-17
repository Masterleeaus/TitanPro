import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanQuotes/Resources/ItemResource/Pages/EditItem.ts
* @see \App\Filament\TitanQuotes\Resources\ItemResource\Pages\EditItem::__invoke
* @see app/Filament/TitanQuotes/Resources/ItemResource/Pages/EditItem.php:7
* @route '/titanquotes/items/{record}/edit'
========
* @see \App\Filament\Resources\ItemResource\Pages\EditItem::__invoke
* @see app/Filament/Resources/ItemResource/Pages/EditItem.php:7
* @route '/titanpro/items/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/ItemResource/Pages/EditItem.ts
*/
const EditItem = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditItem.url(args, options),
    method: 'get',
})

EditItem.definition = {
    methods: ["get","head"],
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanQuotes/Resources/ItemResource/Pages/EditItem.ts
    url: '/titanquotes/items/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanQuotes\Resources\ItemResource\Pages\EditItem::__invoke
* @see app/Filament/TitanQuotes/Resources/ItemResource/Pages/EditItem.php:7
* @route '/titanquotes/items/{record}/edit'
========
    url: '/titanpro/items/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\ItemResource\Pages\EditItem::__invoke
* @see app/Filament/Resources/ItemResource/Pages/EditItem.php:7
* @route '/titanpro/items/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/ItemResource/Pages/EditItem.ts
*/
EditItem.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { record: args }
    }

    if (Array.isArray(args)) {
        args = {
            record: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        record: args.record,
    }

    return EditItem.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanQuotes/Resources/ItemResource/Pages/EditItem.ts
* @see \App\Filament\TitanQuotes\Resources\ItemResource\Pages\EditItem::__invoke
* @see app/Filament/TitanQuotes/Resources/ItemResource/Pages/EditItem.php:7
* @route '/titanquotes/items/{record}/edit'
========
* @see \App\Filament\Resources\ItemResource\Pages\EditItem::__invoke
* @see app/Filament/Resources/ItemResource/Pages/EditItem.php:7
* @route '/titanpro/items/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/ItemResource/Pages/EditItem.ts
*/
EditItem.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditItem.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanQuotes/Resources/ItemResource/Pages/EditItem.ts
* @see \App\Filament\TitanQuotes\Resources\ItemResource\Pages\EditItem::__invoke
* @see app/Filament/TitanQuotes/Resources/ItemResource/Pages/EditItem.php:7
* @route '/titanquotes/items/{record}/edit'
========
* @see \App\Filament\Resources\ItemResource\Pages\EditItem::__invoke
* @see app/Filament/Resources/ItemResource/Pages/EditItem.php:7
* @route '/titanpro/items/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/ItemResource/Pages/EditItem.ts
*/
EditItem.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditItem.url(args, options),
    method: 'head',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanQuotes/Resources/ItemResource/Pages/EditItem.ts
* @see \App\Filament\TitanQuotes\Resources\ItemResource\Pages\EditItem::__invoke
* @see app/Filament/TitanQuotes/Resources/ItemResource/Pages/EditItem.php:7
* @route '/titanquotes/items/{record}/edit'
========
* @see \App\Filament\Resources\ItemResource\Pages\EditItem::__invoke
* @see app/Filament/Resources/ItemResource/Pages/EditItem.php:7
* @route '/titanpro/items/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/ItemResource/Pages/EditItem.ts
*/
const EditItemForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditItem.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanQuotes/Resources/ItemResource/Pages/EditItem.ts
* @see \App\Filament\TitanQuotes\Resources\ItemResource\Pages\EditItem::__invoke
* @see app/Filament/TitanQuotes/Resources/ItemResource/Pages/EditItem.php:7
* @route '/titanquotes/items/{record}/edit'
========
* @see \App\Filament\Resources\ItemResource\Pages\EditItem::__invoke
* @see app/Filament/Resources/ItemResource/Pages/EditItem.php:7
* @route '/titanpro/items/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/ItemResource/Pages/EditItem.ts
*/
EditItemForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditItem.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanQuotes/Resources/ItemResource/Pages/EditItem.ts
* @see \App\Filament\TitanQuotes\Resources\ItemResource\Pages\EditItem::__invoke
* @see app/Filament/TitanQuotes/Resources/ItemResource/Pages/EditItem.php:7
* @route '/titanquotes/items/{record}/edit'
========
* @see \App\Filament\Resources\ItemResource\Pages\EditItem::__invoke
* @see app/Filament/Resources/ItemResource/Pages/EditItem.php:7
* @route '/titanpro/items/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/ItemResource/Pages/EditItem.ts
*/
EditItemForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditItem.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditItem.form = EditItemForm

export default EditItem