import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/admin/invoices/{record}/edit'
*/
const EditInvoice = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditInvoice.url(args, options),
    method: 'get',
})

EditInvoice.definition = {
    methods: ["get","head"],
    url: '/admin/invoices/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/admin/invoices/{record}/edit'
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
* @see \App\Filament\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/admin/invoices/{record}/edit'
*/
EditInvoice.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditInvoice.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/admin/invoices/{record}/edit'
*/
EditInvoice.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditInvoice.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/admin/invoices/{record}/edit'
*/
const EditInvoiceForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditInvoice.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/admin/invoices/{record}/edit'
*/
EditInvoiceForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditInvoice.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\EditInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/EditInvoice.php:7
* @route '/admin/invoices/{record}/edit'
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