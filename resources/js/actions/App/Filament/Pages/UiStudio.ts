import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/groundzero/ui-studio'
*/
const UiStudioa8589b07ad9cebec165d22e3a70a6951 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: UiStudioa8589b07ad9cebec165d22e3a70a6951.url(options),
    method: 'get',
})

UiStudioa8589b07ad9cebec165d22e3a70a6951.definition = {
    methods: ["get","head"],
    url: '/groundzero/ui-studio',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/groundzero/ui-studio'
*/
UiStudioa8589b07ad9cebec165d22e3a70a6951.url = (options?: RouteQueryOptions) => {
    return UiStudioa8589b07ad9cebec165d22e3a70a6951.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/groundzero/ui-studio'
*/
UiStudioa8589b07ad9cebec165d22e3a70a6951.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: UiStudioa8589b07ad9cebec165d22e3a70a6951.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/groundzero/ui-studio'
*/
UiStudioa8589b07ad9cebec165d22e3a70a6951.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: UiStudioa8589b07ad9cebec165d22e3a70a6951.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/groundzero/ui-studio'
*/
const UiStudioa8589b07ad9cebec165d22e3a70a6951Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: UiStudioa8589b07ad9cebec165d22e3a70a6951.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/groundzero/ui-studio'
*/
UiStudioa8589b07ad9cebec165d22e3a70a6951Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: UiStudioa8589b07ad9cebec165d22e3a70a6951.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/groundzero/ui-studio'
*/
UiStudioa8589b07ad9cebec165d22e3a70a6951Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: UiStudioa8589b07ad9cebec165d22e3a70a6951.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

UiStudioa8589b07ad9cebec165d22e3a70a6951.form = UiStudioa8589b07ad9cebec165d22e3a70a6951Form
/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanquotes/ui-studio'
*/
const UiStudiof31d3d69bdf4b47f73f1a87a7fddf37c = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: UiStudiof31d3d69bdf4b47f73f1a87a7fddf37c.url(options),
    method: 'get',
})

UiStudiof31d3d69bdf4b47f73f1a87a7fddf37c.definition = {
    methods: ["get","head"],
    url: '/titanquotes/ui-studio',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanquotes/ui-studio'
*/
UiStudiof31d3d69bdf4b47f73f1a87a7fddf37c.url = (options?: RouteQueryOptions) => {
    return UiStudiof31d3d69bdf4b47f73f1a87a7fddf37c.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanquotes/ui-studio'
*/
UiStudiof31d3d69bdf4b47f73f1a87a7fddf37c.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: UiStudiof31d3d69bdf4b47f73f1a87a7fddf37c.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanquotes/ui-studio'
*/
UiStudiof31d3d69bdf4b47f73f1a87a7fddf37c.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: UiStudiof31d3d69bdf4b47f73f1a87a7fddf37c.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanquotes/ui-studio'
*/
const UiStudiof31d3d69bdf4b47f73f1a87a7fddf37cForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: UiStudiof31d3d69bdf4b47f73f1a87a7fddf37c.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanquotes/ui-studio'
*/
UiStudiof31d3d69bdf4b47f73f1a87a7fddf37cForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: UiStudiof31d3d69bdf4b47f73f1a87a7fddf37c.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titanquotes/ui-studio'
*/
UiStudiof31d3d69bdf4b47f73f1a87a7fddf37cForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: UiStudiof31d3d69bdf4b47f73f1a87a7fddf37c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

UiStudiof31d3d69bdf4b47f73f1a87a7fddf37c.form = UiStudiof31d3d69bdf4b47f73f1a87a7fddf37cForm
/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/zeropay/ui-studio'
*/
const UiStudio08c00772e90be738822b799f55dcfa5e = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: UiStudio08c00772e90be738822b799f55dcfa5e.url(options),
    method: 'get',
})

UiStudio08c00772e90be738822b799f55dcfa5e.definition = {
    methods: ["get","head"],
    url: '/zeropay/ui-studio',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/zeropay/ui-studio'
*/
UiStudio08c00772e90be738822b799f55dcfa5e.url = (options?: RouteQueryOptions) => {
    return UiStudio08c00772e90be738822b799f55dcfa5e.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/zeropay/ui-studio'
*/
UiStudio08c00772e90be738822b799f55dcfa5e.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: UiStudio08c00772e90be738822b799f55dcfa5e.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/zeropay/ui-studio'
*/
UiStudio08c00772e90be738822b799f55dcfa5e.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: UiStudio08c00772e90be738822b799f55dcfa5e.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/zeropay/ui-studio'
*/
const UiStudio08c00772e90be738822b799f55dcfa5eForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: UiStudio08c00772e90be738822b799f55dcfa5e.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/zeropay/ui-studio'
*/
UiStudio08c00772e90be738822b799f55dcfa5eForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: UiStudio08c00772e90be738822b799f55dcfa5e.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/zeropay/ui-studio'
*/
UiStudio08c00772e90be738822b799f55dcfa5eForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: UiStudio08c00772e90be738822b799f55dcfa5e.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

UiStudio08c00772e90be738822b799f55dcfa5e.form = UiStudio08c00772e90be738822b799f55dcfa5eForm
/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titannexus/ui-studio'
*/
const UiStudio05a00afdb1e6674b9b79a55ffbbbc11f = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: UiStudio05a00afdb1e6674b9b79a55ffbbbc11f.url(options),
    method: 'get',
})

UiStudio05a00afdb1e6674b9b79a55ffbbbc11f.definition = {
    methods: ["get","head"],
    url: '/titannexus/ui-studio',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titannexus/ui-studio'
*/
UiStudio05a00afdb1e6674b9b79a55ffbbbc11f.url = (options?: RouteQueryOptions) => {
    return UiStudio05a00afdb1e6674b9b79a55ffbbbc11f.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titannexus/ui-studio'
*/
UiStudio05a00afdb1e6674b9b79a55ffbbbc11f.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: UiStudio05a00afdb1e6674b9b79a55ffbbbc11f.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titannexus/ui-studio'
*/
UiStudio05a00afdb1e6674b9b79a55ffbbbc11f.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: UiStudio05a00afdb1e6674b9b79a55ffbbbc11f.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titannexus/ui-studio'
*/
const UiStudio05a00afdb1e6674b9b79a55ffbbbc11fForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: UiStudio05a00afdb1e6674b9b79a55ffbbbc11f.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titannexus/ui-studio'
*/
UiStudio05a00afdb1e6674b9b79a55ffbbbc11fForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: UiStudio05a00afdb1e6674b9b79a55ffbbbc11f.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titannexus/ui-studio'
*/
UiStudio05a00afdb1e6674b9b79a55ffbbbc11fForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: UiStudio05a00afdb1e6674b9b79a55ffbbbc11f.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

UiStudio05a00afdb1e6674b9b79a55ffbbbc11f.form = UiStudio05a00afdb1e6674b9b79a55ffbbbc11fForm

const UiStudio = {
    '/groundzero/ui-studio': UiStudioa8589b07ad9cebec165d22e3a70a6951,
    '/titanquotes/ui-studio': UiStudiof31d3d69bdf4b47f73f1a87a7fddf37c,
    '/zeropay/ui-studio': UiStudio08c00772e90be738822b799f55dcfa5e,
    '/titannexus/ui-studio': UiStudio05a00afdb1e6674b9b79a55ffbbbc11f,
}

export default UiStudio