import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\ListPayments::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/ListPayments.php:7
* @route '/zeropay/payments'
*/
const ListPayments = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListPayments.url(options),
    method: 'get',
})

ListPayments.definition = {
    methods: ["get","head"],
    url: '/zeropay/payments',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\ListPayments::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/ListPayments.php:7
* @route '/zeropay/payments'
*/
ListPayments.url = (options?: RouteQueryOptions) => {
    return ListPayments.definition.url + queryParams(options)
}

/**
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\ListPayments::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/ListPayments.php:7
* @route '/zeropay/payments'
*/
ListPayments.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListPayments.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\ListPayments::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/ListPayments.php:7
* @route '/zeropay/payments'
*/
ListPayments.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListPayments.url(options),
    method: 'head',
})

/**
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\ListPayments::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/ListPayments.php:7
* @route '/zeropay/payments'
*/
const ListPaymentsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListPayments.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\ListPayments::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/ListPayments.php:7
* @route '/zeropay/payments'
*/
ListPaymentsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListPayments.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\ListPayments::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/ListPayments.php:7
* @route '/zeropay/payments'
*/
ListPaymentsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListPayments.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListPayments.form = ListPaymentsForm

export default ListPayments