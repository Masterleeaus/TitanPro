import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\CreatePayment::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/CreatePayment.php:7
* @route '/zeropay/payments/create'
*/
const CreatePayment = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreatePayment.url(options),
    method: 'get',
})

CreatePayment.definition = {
    methods: ["get","head"],
    url: '/zeropay/payments/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\CreatePayment::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/CreatePayment.php:7
* @route '/zeropay/payments/create'
*/
CreatePayment.url = (options?: RouteQueryOptions) => {
    return CreatePayment.definition.url + queryParams(options)
}

/**
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\CreatePayment::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/CreatePayment.php:7
* @route '/zeropay/payments/create'
*/
CreatePayment.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreatePayment.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\CreatePayment::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/CreatePayment.php:7
* @route '/zeropay/payments/create'
*/
CreatePayment.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreatePayment.url(options),
    method: 'head',
})

/**
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\CreatePayment::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/CreatePayment.php:7
* @route '/zeropay/payments/create'
*/
const CreatePaymentForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreatePayment.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\CreatePayment::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/CreatePayment.php:7
* @route '/zeropay/payments/create'
*/
CreatePaymentForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreatePayment.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Resources\PaymentResource\Pages\CreatePayment::__invoke
* @see app/Filament/ZeroPay/Resources/PaymentResource/Pages/CreatePayment.php:7
* @route '/zeropay/payments/create'
*/
CreatePaymentForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreatePayment.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreatePayment.form = CreatePaymentForm

export default CreatePayment