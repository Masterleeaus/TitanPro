import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\PaymentResource\Pages\ListPayments::__invoke
* @see app/Filament/Resources/PaymentResource/Pages/ListPayments.php:7
* @route '/titanpro/payments'
*/
const ListPayments = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListPayments.url(options),
    method: 'get',
})

ListPayments.definition = {
    methods: ["get","head"],
    url: '/titanpro/payments',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\PaymentResource\Pages\ListPayments::__invoke
* @see app/Filament/Resources/PaymentResource/Pages/ListPayments.php:7
* @route '/titanpro/payments'
*/
ListPayments.url = (options?: RouteQueryOptions) => {
    return ListPayments.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\PaymentResource\Pages\ListPayments::__invoke
* @see app/Filament/Resources/PaymentResource/Pages/ListPayments.php:7
* @route '/titanpro/payments'
*/
ListPayments.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListPayments.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\PaymentResource\Pages\ListPayments::__invoke
* @see app/Filament/Resources/PaymentResource/Pages/ListPayments.php:7
* @route '/titanpro/payments'
*/
ListPayments.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListPayments.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\PaymentResource\Pages\ListPayments::__invoke
* @see app/Filament/Resources/PaymentResource/Pages/ListPayments.php:7
* @route '/titanpro/payments'
*/
const ListPaymentsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListPayments.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\PaymentResource\Pages\ListPayments::__invoke
* @see app/Filament/Resources/PaymentResource/Pages/ListPayments.php:7
* @route '/titanpro/payments'
*/
ListPaymentsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListPayments.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\PaymentResource\Pages\ListPayments::__invoke
* @see app/Filament/Resources/PaymentResource/Pages/ListPayments.php:7
* @route '/titanpro/payments'
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