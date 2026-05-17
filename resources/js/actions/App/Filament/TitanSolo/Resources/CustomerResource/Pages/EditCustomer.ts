import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/CustomerResource/Pages/EditCustomer.ts
* @see \App\Filament\TitanSolo\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/TitanSolo/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/titansolo/customers/{record}/edit'
========
* @see \App\Filament\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/titanpro/customers/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/CustomerResource/Pages/EditCustomer.ts
*/
const EditCustomer = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCustomer.url(args, options),
    method: 'get',
})

EditCustomer.definition = {
    methods: ["get","head"],
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/CustomerResource/Pages/EditCustomer.ts
    url: '/titansolo/customers/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanSolo\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/TitanSolo/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/titansolo/customers/{record}/edit'
========
    url: '/titanpro/customers/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/titanpro/customers/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/CustomerResource/Pages/EditCustomer.ts
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
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/CustomerResource/Pages/EditCustomer.ts
* @see \App\Filament\TitanSolo\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/TitanSolo/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/titansolo/customers/{record}/edit'
========
* @see \App\Filament\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/titanpro/customers/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/CustomerResource/Pages/EditCustomer.ts
*/
EditCustomer.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCustomer.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/CustomerResource/Pages/EditCustomer.ts
* @see \App\Filament\TitanSolo\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/TitanSolo/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/titansolo/customers/{record}/edit'
========
* @see \App\Filament\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/titanpro/customers/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/CustomerResource/Pages/EditCustomer.ts
*/
EditCustomer.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditCustomer.url(args, options),
    method: 'head',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/CustomerResource/Pages/EditCustomer.ts
* @see \App\Filament\TitanSolo\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/TitanSolo/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/titansolo/customers/{record}/edit'
========
* @see \App\Filament\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/titanpro/customers/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/CustomerResource/Pages/EditCustomer.ts
*/
const EditCustomerForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCustomer.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/CustomerResource/Pages/EditCustomer.ts
* @see \App\Filament\TitanSolo\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/TitanSolo/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/titansolo/customers/{record}/edit'
========
* @see \App\Filament\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/titanpro/customers/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/CustomerResource/Pages/EditCustomer.ts
*/
EditCustomerForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCustomer.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/TitanSolo/Resources/CustomerResource/Pages/EditCustomer.ts
* @see \App\Filament\TitanSolo\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/TitanSolo/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/titansolo/customers/{record}/edit'
========
* @see \App\Filament\Resources\CustomerResource\Pages\EditCustomer::__invoke
* @see app/Filament/Resources/CustomerResource/Pages/EditCustomer.php:7
* @route '/titanpro/customers/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/CustomerResource/Pages/EditCustomer.ts
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