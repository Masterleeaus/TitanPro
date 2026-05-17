import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/ZeroPay/Resources/InvoiceResource/Pages/EditInvoice.ts
* @see \App\Filament\ZeroPay\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/ZeroPay/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/zeropay/invoices/{record}/edit'
========
* @see \App\Filament\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/titanpro/invoices/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/InvoiceResource/Pages/EditInvoice.ts
*/
const EditInvoice = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditInvoice.url(args, options),
    method: 'get',
})

EditInvoice.definition = {
    methods: ["get","head"],
<<<<<<<< HEAD:resources/js/actions/App/Filament/ZeroPay/Resources/InvoiceResource/Pages/EditInvoice.ts
    url: '/zeropay/invoices/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroPay\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/ZeroPay/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/zeropay/invoices/{record}/edit'
========
    url: '/titanpro/invoices/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/titanpro/invoices/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/InvoiceResource/Pages/EditInvoice.ts
*/
EditInvoice.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditInvoice.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/ZeroPay/Resources/InvoiceResource/Pages/EditInvoice.ts
* @see \App\Filament\ZeroPay\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/ZeroPay/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/zeropay/invoices/{record}/edit'
========
* @see \App\Filament\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/titanpro/invoices/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/InvoiceResource/Pages/EditInvoice.ts
*/
EditInvoice.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditInvoice.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/ZeroPay/Resources/InvoiceResource/Pages/EditInvoice.ts
* @see \App\Filament\ZeroPay\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/ZeroPay/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/zeropay/invoices/{record}/edit'
========
* @see \App\Filament\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/titanpro/invoices/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/InvoiceResource/Pages/EditInvoice.ts
*/
EditInvoice.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditInvoice.url(args, options),
    method: 'head',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/ZeroPay/Resources/InvoiceResource/Pages/EditInvoice.ts
* @see \App\Filament\ZeroPay\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/ZeroPay/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/zeropay/invoices/{record}/edit'
========
* @see \App\Filament\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/titanpro/invoices/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/InvoiceResource/Pages/EditInvoice.ts
*/
const EditInvoiceForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditInvoice.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/ZeroPay/Resources/InvoiceResource/Pages/EditInvoice.ts
* @see \App\Filament\ZeroPay\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/ZeroPay/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/zeropay/invoices/{record}/edit'
========
* @see \App\Filament\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/titanpro/invoices/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/InvoiceResource/Pages/EditInvoice.ts
*/
EditInvoiceForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditInvoice.url(args, options),
    method: 'get',
})

/**
<<<<<<<< HEAD:resources/js/actions/App/Filament/ZeroPay/Resources/InvoiceResource/Pages/EditInvoice.ts
* @see \App\Filament\ZeroPay\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/ZeroPay/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/zeropay/invoices/{record}/edit'
========
* @see \App\Filament\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/titanpro/invoices/{record}/edit'
>>>>>>>> 33b682f987af93af087e129eddf755df6385daa8:resources/js/actions/App/Filament/Resources/InvoiceResource/Pages/EditInvoice.ts
*/
EditInvoiceForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditInvoice.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditInvoice.form = EditInvoiceForm

export default EditInvoice