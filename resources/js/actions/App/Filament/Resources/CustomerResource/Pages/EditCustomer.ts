import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/admin/customers/{record}/edit'
*/
const EditCustomer = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCustomer.url(args, options),
    method: 'get',
})

EditCustomer.definition = {
    methods: ["get","head"],
    url: '/admin/customers/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/admin/customers/{record}/edit'
*/
EditCustomer.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditCustomer.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/admin/customers/{record}/edit'
*/
EditCustomer.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCustomer.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/admin/customers/{record}/edit'
*/
EditCustomer.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditCustomer.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/admin/customers/{record}/edit'
*/
const EditCustomerForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCustomer.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/admin/customers/{record}/edit'
*/
EditCustomerForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCustomer.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/admin/customers/{record}/edit'
*/
EditCustomerForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCustomer.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditCustomer.form = EditCustomerForm

export default EditCustomer