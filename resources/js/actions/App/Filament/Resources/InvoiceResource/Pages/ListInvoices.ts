import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\InvoiceResource\Pages\ListInvoices::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/ListInvoices.php:7
* @route '/titanpro/invoices'
*/
const ListInvoices = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListInvoices.url(options),
    method: 'get',
})

ListInvoices.definition = {
    methods: ["get","head"],
    url: '/titanpro/invoices',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\ListInvoices::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/ListInvoices.php:7
* @route '/titanpro/invoices'
*/
ListInvoices.url = (options?: RouteQueryOptions) => {
    return ListInvoices.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\ListInvoices::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/ListInvoices.php:7
* @route '/titanpro/invoices'
*/
ListInvoices.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListInvoices.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\ListInvoices::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/ListInvoices.php:7
* @route '/titanpro/invoices'
*/
ListInvoices.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListInvoices.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\ListInvoices::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/ListInvoices.php:7
* @route '/titanpro/invoices'
*/
const ListInvoicesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListInvoices.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\ListInvoices::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/ListInvoices.php:7
* @route '/titanpro/invoices'
*/
ListInvoicesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListInvoices.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\ListInvoices::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/ListInvoices.php:7
* @route '/titanpro/invoices'
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