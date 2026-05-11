import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanSolo\Resources\CustomerResource\Pages\CreateCustomer::__invoke
* @see app/Filament/TitanSolo/Resources/CustomerResource/Pages/CreateCustomer.php:7
* @route '/titansolo/customers/create'
*/
const CreateCustomer = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateCustomer.url(options),
    method: 'get',
})

CreateCustomer.definition = {
    methods: ["get","head"],
    url: '/titansolo/customers/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanSolo\Resources\CustomerResource\Pages\CreateCustomer::__invoke
* @see app/Filament/TitanSolo/Resources/CustomerResource/Pages/CreateCustomer.php:7
* @route '/titansolo/customers/create'
*/
CreateCustomer.url = (options?: RouteQueryOptions) => {
    return CreateCustomer.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanSolo\Resources\CustomerResource\Pages\CreateCustomer::__invoke
* @see app/Filament/TitanSolo/Resources/CustomerResource/Pages/CreateCustomer.php:7
* @route '/titansolo/customers/create'
*/
CreateCustomer.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateCustomer.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanSolo\Resources\CustomerResource\Pages\CreateCustomer::__invoke
* @see app/Filament/TitanSolo/Resources/CustomerResource/Pages/CreateCustomer.php:7
* @route '/titansolo/customers/create'
*/
CreateCustomer.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateCustomer.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanSolo\Resources\CustomerResource\Pages\CreateCustomer::__invoke
* @see app/Filament/TitanSolo/Resources/CustomerResource/Pages/CreateCustomer.php:7
* @route '/titansolo/customers/create'
*/
const CreateCustomerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateCustomer.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanSolo\Resources\CustomerResource\Pages\CreateCustomer::__invoke
* @see app/Filament/TitanSolo/Resources/CustomerResource/Pages/CreateCustomer.php:7
* @route '/titansolo/customers/create'
*/
CreateCustomerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateCustomer.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanSolo\Resources\CustomerResource\Pages\CreateCustomer::__invoke
* @see app/Filament/TitanSolo/Resources/CustomerResource/Pages/CreateCustomer.php:7
* @route '/titansolo/customers/create'
*/
CreateCustomerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateCustomer.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateCustomer.form = CreateCustomerForm

export default CreateCustomer