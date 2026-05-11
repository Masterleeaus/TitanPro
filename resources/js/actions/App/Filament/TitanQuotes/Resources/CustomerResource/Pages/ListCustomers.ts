import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanQuotes\Resources\CustomerResource\Pages\ListCustomers::__invoke
* @see app/Filament/TitanQuotes/Resources/CustomerResource/Pages/ListCustomers.php:7
* @route '/titanquotes/customers'
*/
const ListCustomers = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCustomers.url(options),
    method: 'get',
})

ListCustomers.definition = {
    methods: ["get","head"],
    url: '/titanquotes/customers',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanQuotes\Resources\CustomerResource\Pages\ListCustomers::__invoke
* @see app/Filament/TitanQuotes/Resources/CustomerResource/Pages/ListCustomers.php:7
* @route '/titanquotes/customers'
*/
ListCustomers.url = (options?: RouteQueryOptions) => {
    return ListCustomers.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanQuotes\Resources\CustomerResource\Pages\ListCustomers::__invoke
* @see app/Filament/TitanQuotes/Resources/CustomerResource/Pages/ListCustomers.php:7
* @route '/titanquotes/customers'
*/
ListCustomers.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCustomers.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Resources\CustomerResource\Pages\ListCustomers::__invoke
* @see app/Filament/TitanQuotes/Resources/CustomerResource/Pages/ListCustomers.php:7
* @route '/titanquotes/customers'
*/
ListCustomers.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListCustomers.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanQuotes\Resources\CustomerResource\Pages\ListCustomers::__invoke
* @see app/Filament/TitanQuotes/Resources/CustomerResource/Pages/ListCustomers.php:7
* @route '/titanquotes/customers'
*/
const ListCustomersForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCustomers.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Resources\CustomerResource\Pages\ListCustomers::__invoke
* @see app/Filament/TitanQuotes/Resources/CustomerResource/Pages/ListCustomers.php:7
* @route '/titanquotes/customers'
*/
ListCustomersForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCustomers.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanQuotes\Resources\CustomerResource\Pages\ListCustomers::__invoke
* @see app/Filament/TitanQuotes/Resources/CustomerResource/Pages/ListCustomers.php:7
* @route '/titanquotes/customers'
*/
ListCustomersForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCustomers.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListCustomers.form = ListCustomersForm

export default ListCustomers