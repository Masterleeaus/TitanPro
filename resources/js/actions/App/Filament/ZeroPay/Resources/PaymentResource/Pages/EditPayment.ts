import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/ZeroPay/Resources/PaymentResource/Pages/EditPayment.ts
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\EditPayment::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/EditPayment.php:7
* @route '/zeropay/payments/{record}/edit'
========
* @see \App\Filament\Resources\PaymentResource\Pages\EditPayment::__invoke
* @see app/Filament/Resources/PaymentResource/Pages/EditPayment.php:7
* @route '/titanpro/payments/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/PaymentResource/Pages/EditPayment.ts
*/
const EditPayment = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditPayment.url(args, options),
    method: 'get',
})

EditPayment.definition = {
    methods: ["get","head"],
<<<<<<<< HEAD:resources/js/actions/App/Filament/ZeroPay/Resources/PaymentResource/Pages/EditPayment.ts
    url: '/zeropay/payments/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\EditPayment::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/EditPayment.php:7
* @route '/zeropay/payments/{record}/edit'
========
    url: '/titanpro/payments/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\PaymentResource\Pages\EditPayment::__invoke
* @see app/Filament/Resources/PaymentResource/Pages/EditPayment.php:7
* @route '/titanpro/payments/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/PaymentResource/Pages/EditPayment.ts
*/
EditPayment.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditPayment.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/ZeroPay/Resources/PaymentResource/Pages/EditPayment.ts
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\EditPayment::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/EditPayment.php:7
* @route '/zeropay/payments/{record}/edit'
========
* @see \App\Filament\Resources\PaymentResource\Pages\EditPayment::__invoke
* @see app/Filament/Resources/PaymentResource/Pages/EditPayment.php:7
* @route '/titanpro/payments/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/PaymentResource/Pages/EditPayment.ts
*/
EditPayment.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditPayment.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/ZeroPay/Resources/PaymentResource/Pages/EditPayment.ts
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\EditPayment::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/EditPayment.php:7
* @route '/zeropay/payments/{record}/edit'
========
* @see \App\Filament\Resources\PaymentResource\Pages\EditPayment::__invoke
* @see app/Filament/Resources/PaymentResource/Pages/EditPayment.php:7
* @route '/titanpro/payments/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/PaymentResource/Pages/EditPayment.ts
*/
EditPayment.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditPayment.url(args, options),
    method: 'head',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/ZeroPay/Resources/PaymentResource/Pages/EditPayment.ts
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\EditPayment::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/EditPayment.php:7
* @route '/zeropay/payments/{record}/edit'
========
* @see \App\Filament\Resources\PaymentResource\Pages\EditPayment::__invoke
* @see app/Filament/Resources/PaymentResource/Pages/EditPayment.php:7
* @route '/titanpro/payments/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/PaymentResource/Pages/EditPayment.ts
*/
const EditPaymentForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditPayment.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/ZeroPay/Resources/PaymentResource/Pages/EditPayment.ts
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\EditPayment::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/EditPayment.php:7
* @route '/zeropay/payments/{record}/edit'
========
* @see \App\Filament\Resources\PaymentResource\Pages\EditPayment::__invoke
* @see app/Filament/Resources/PaymentResource/Pages/EditPayment.php:7
* @route '/titanpro/payments/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/PaymentResource/Pages/EditPayment.ts
*/
EditPaymentForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditPayment.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/ZeroPay/Resources/PaymentResource/Pages/EditPayment.ts
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\EditPayment::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/EditPayment.php:7
* @route '/zeropay/payments/{record}/edit'
========
* @see \App\Filament\Resources\PaymentResource\Pages\EditPayment::__invoke
* @see app/Filament/Resources/PaymentResource/Pages/EditPayment.php:7
* @route '/titanpro/payments/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/PaymentResource/Pages/EditPayment.ts
*/
EditPaymentForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditPayment.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditPayment.form = EditPaymentForm

export default EditPayment