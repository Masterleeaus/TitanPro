import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\InvoiceResource\Pages\CreateInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/CreateInvoice.php:7
* @route '/admin/invoices/create'
*/
const CreateInvoice = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateInvoice.url(options),
    method: 'get',
})

CreateInvoice.definition = {
    methods: ["get","head"],
    url: '/admin/invoices/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\CreateInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/CreateInvoice.php:7
* @route '/admin/invoices/create'
*/
CreateInvoice.url = (options?: RouteQueryOptions) => {
    return CreateInvoice.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\CreateInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/CreateInvoice.php:7
* @route '/admin/invoices/create'
*/
CreateInvoice.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateInvoice.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\CreateInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/CreateInvoice.php:7
* @route '/admin/invoices/create'
*/
CreateInvoice.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateInvoice.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\CreateInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/CreateInvoice.php:7
* @route '/admin/invoices/create'
*/
const CreateInvoiceForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateInvoice.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\CreateInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/CreateInvoice.php:7
* @route '/admin/invoices/create'
*/
CreateInvoiceForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateInvoice.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\InvoiceResource\Pages\CreateInvoice::__invoke
* @see app/Filament/Resources/InvoiceResource/Pages/CreateInvoice.php:7
* @route '/admin/invoices/create'
*/
CreateInvoiceForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateInvoice.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateInvoice.form = CreateInvoiceForm

export default CreateInvoice