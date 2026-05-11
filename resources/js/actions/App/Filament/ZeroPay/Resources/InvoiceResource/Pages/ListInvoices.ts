import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\ZeroPay\Resources\InvoiceResource\Pages\ListInvoices::__invoke
* @see app/Filament/ZeroPay/Resources/InvoiceResource/Pages/ListInvoices.php:7
* @route '/zeropay/invoices'
*/
const ListInvoices = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListInvoices.url(options),
    method: 'get',
})

ListInvoices.definition = {
    methods: ["get","head"],
    url: '/zeropay/invoices',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroPay\Resources\InvoiceResource\Pages\ListInvoices::__invoke
* @see app/Filament/ZeroPay/Resources/InvoiceResource/Pages/ListInvoices.php:7
* @route '/zeropay/invoices'
*/
ListInvoices.url = (options?: RouteQueryOptions) => {
    return ListInvoices.definition.url + queryParams(options)
}

/**
* @see \App\Filament\ZeroPay\Resources\InvoiceResource\Pages\ListInvoices::__invoke
* @see app/Filament/ZeroPay/Resources/InvoiceResource/Pages/ListInvoices.php:7
* @route '/zeropay/invoices'
*/
ListInvoices.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListInvoices.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\InvoiceResource\Pages\ListInvoices::__invoke
* @see app/Filament/ZeroPay/Resources/InvoiceResource/Pages/ListInvoices.php:7
* @route '/zeropay/invoices'
*/
ListInvoices.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListInvoices.url(options),
    method: 'head',
})

/**
* @see \App\Filament\ZeroPay\Resources\InvoiceResource\Pages\ListInvoices::__invoke
* @see app/Filament/ZeroPay/Resources/InvoiceResource/Pages/ListInvoices.php:7
* @route '/zeropay/invoices'
*/
const ListInvoicesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListInvoices.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\InvoiceResource\Pages\ListInvoices::__invoke
* @see app/Filament/ZeroPay/Resources/InvoiceResource/Pages/ListInvoices.php:7
* @route '/zeropay/invoices'
*/
ListInvoicesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListInvoices.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\InvoiceResource\Pages\ListInvoices::__invoke
* @see app/Filament/ZeroPay/Resources/InvoiceResource/Pages/ListInvoices.php:7
* @route '/zeropay/invoices'
*/
ListInvoicesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListInvoices.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListInvoices.form = ListInvoicesForm

export default ListInvoices