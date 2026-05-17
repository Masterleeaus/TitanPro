import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\TitanQuotes\Resources\CustomerResource\Pages\ListCustomers::__invoke
* @see app/Filament/TitanQuotes/Resources/CustomerResource/Pages/ListCustomers.php:7
* @route '/titanquotes/customers'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/titanquotes/customers',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanQuotes\Resources\CustomerResource\Pages\ListCustomers::__invoke
* @see app/Filament/TitanQuotes/Resources/CustomerResource/Pages/ListCustomers.php:7
* @route '/titanquotes/customers'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanQuotes\Resources\CustomerResource\Pages\ListCustomers::__invoke
* @see app/Filament/TitanQuotes/Resources/CustomerResource/Pages/ListCustomers.php:7
* @route '/titanquotes/customers'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Resources\CustomerResource\Pages\ListCustomers::__invoke
* @see app/Filament/TitanQuotes/Resources/CustomerResource/Pages/ListCustomers.php:7
* @route '/titanquotes/customers'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanQuotes\Resources\CustomerResource\Pages\ListCustomers::__invoke
* @see app/Filament/TitanQuotes/Resources/CustomerResource/Pages/ListCustomers.php:7
* @route '/titanquotes/customers'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Resources\CustomerResource\Pages\ListCustomers::__invoke
* @see app/Filament/TitanQuotes/Resources/CustomerResource/Pages/ListCustomers.php:7
* @route '/titanquotes/customers'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Resources\CustomerResource\Pages\ListCustomers::__invoke
* @see app/Filament/TitanQuotes/Resources/CustomerResource/Pages/ListCustomers.php:7
* @route '/titanquotes/customers'
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

const customers = {
    index: Object.assign(index, index),
}

export default customers