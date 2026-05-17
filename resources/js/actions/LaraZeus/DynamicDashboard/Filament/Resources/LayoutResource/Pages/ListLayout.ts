import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titanpro/layouts'
*/
const ListLayout231a146d27ba266bc512ce12cafdcc40 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLayout231a146d27ba266bc512ce12cafdcc40.url(options),
    method: 'get',
})

ListLayout231a146d27ba266bc512ce12cafdcc40.definition = {
    methods: ["get","head"],
    url: '/titanpro/layouts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titanpro/layouts'
*/
ListLayout231a146d27ba266bc512ce12cafdcc40.url = (options?: RouteQueryOptions) => {
    return ListLayout231a146d27ba266bc512ce12cafdcc40.definition.url + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titanpro/layouts'
*/
ListLayout231a146d27ba266bc512ce12cafdcc40.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLayout231a146d27ba266bc512ce12cafdcc40.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titanpro/layouts'
*/
ListLayout231a146d27ba266bc512ce12cafdcc40.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListLayout231a146d27ba266bc512ce12cafdcc40.url(options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titanpro/layouts'
*/
const ListLayout231a146d27ba266bc512ce12cafdcc40Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLayout231a146d27ba266bc512ce12cafdcc40.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titanpro/layouts'
*/
ListLayout231a146d27ba266bc512ce12cafdcc40Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLayout231a146d27ba266bc512ce12cafdcc40.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titanpro/layouts'
*/
ListLayout231a146d27ba266bc512ce12cafdcc40Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLayout231a146d27ba266bc512ce12cafdcc40.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListLayout231a146d27ba266bc512ce12cafdcc40.form = ListLayout231a146d27ba266bc512ce12cafdcc40Form
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/groundzero/layouts'
*/
const ListLayout1e9d65f543073a6195030ede4566a17b = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLayout1e9d65f543073a6195030ede4566a17b.url(options),
    method: 'get',
})

ListLayout1e9d65f543073a6195030ede4566a17b.definition = {
    methods: ["get","head"],
    url: '/groundzero/layouts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/groundzero/layouts'
*/
ListLayout1e9d65f543073a6195030ede4566a17b.url = (options?: RouteQueryOptions) => {
    return ListLayout1e9d65f543073a6195030ede4566a17b.definition.url + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/groundzero/layouts'
*/
ListLayout1e9d65f543073a6195030ede4566a17b.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLayout1e9d65f543073a6195030ede4566a17b.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/groundzero/layouts'
*/
ListLayout1e9d65f543073a6195030ede4566a17b.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListLayout1e9d65f543073a6195030ede4566a17b.url(options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/groundzero/layouts'
*/
const ListLayout1e9d65f543073a6195030ede4566a17bForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLayout1e9d65f543073a6195030ede4566a17b.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/groundzero/layouts'
*/
ListLayout1e9d65f543073a6195030ede4566a17bForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLayout1e9d65f543073a6195030ede4566a17b.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/groundzero/layouts'
*/
ListLayout1e9d65f543073a6195030ede4566a17bForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLayout1e9d65f543073a6195030ede4566a17b.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListLayout1e9d65f543073a6195030ede4566a17b.form = ListLayout1e9d65f543073a6195030ede4566a17bForm
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titango/layouts'
*/
const ListLayout1794914c2d8f5d8e1dfe3bd876e495c2 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLayout1794914c2d8f5d8e1dfe3bd876e495c2.url(options),
    method: 'get',
})

ListLayout1794914c2d8f5d8e1dfe3bd876e495c2.definition = {
    methods: ["get","head"],
    url: '/titango/layouts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titango/layouts'
*/
ListLayout1794914c2d8f5d8e1dfe3bd876e495c2.url = (options?: RouteQueryOptions) => {
    return ListLayout1794914c2d8f5d8e1dfe3bd876e495c2.definition.url + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titango/layouts'
*/
ListLayout1794914c2d8f5d8e1dfe3bd876e495c2.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLayout1794914c2d8f5d8e1dfe3bd876e495c2.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titango/layouts'
*/
ListLayout1794914c2d8f5d8e1dfe3bd876e495c2.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListLayout1794914c2d8f5d8e1dfe3bd876e495c2.url(options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titango/layouts'
*/
const ListLayout1794914c2d8f5d8e1dfe3bd876e495c2Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLayout1794914c2d8f5d8e1dfe3bd876e495c2.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titango/layouts'
*/
ListLayout1794914c2d8f5d8e1dfe3bd876e495c2Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLayout1794914c2d8f5d8e1dfe3bd876e495c2.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titango/layouts'
*/
ListLayout1794914c2d8f5d8e1dfe3bd876e495c2Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLayout1794914c2d8f5d8e1dfe3bd876e495c2.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListLayout1794914c2d8f5d8e1dfe3bd876e495c2.form = ListLayout1794914c2d8f5d8e1dfe3bd876e495c2Form
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titansolo/layouts'
*/
const ListLayout3bda7beb3e66af47073747720e9d3e88 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLayout3bda7beb3e66af47073747720e9d3e88.url(options),
    method: 'get',
})

ListLayout3bda7beb3e66af47073747720e9d3e88.definition = {
    methods: ["get","head"],
    url: '/titansolo/layouts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titansolo/layouts'
*/
ListLayout3bda7beb3e66af47073747720e9d3e88.url = (options?: RouteQueryOptions) => {
    return ListLayout3bda7beb3e66af47073747720e9d3e88.definition.url + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titansolo/layouts'
*/
ListLayout3bda7beb3e66af47073747720e9d3e88.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLayout3bda7beb3e66af47073747720e9d3e88.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titansolo/layouts'
*/
ListLayout3bda7beb3e66af47073747720e9d3e88.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListLayout3bda7beb3e66af47073747720e9d3e88.url(options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titansolo/layouts'
*/
const ListLayout3bda7beb3e66af47073747720e9d3e88Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLayout3bda7beb3e66af47073747720e9d3e88.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titansolo/layouts'
*/
ListLayout3bda7beb3e66af47073747720e9d3e88Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLayout3bda7beb3e66af47073747720e9d3e88.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titansolo/layouts'
*/
ListLayout3bda7beb3e66af47073747720e9d3e88Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLayout3bda7beb3e66af47073747720e9d3e88.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListLayout3bda7beb3e66af47073747720e9d3e88.form = ListLayout3bda7beb3e66af47073747720e9d3e88Form
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titannexus/layouts'
*/
const ListLayout6deaa3bb6a7aedb811d2011debe42166 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLayout6deaa3bb6a7aedb811d2011debe42166.url(options),
    method: 'get',
})

ListLayout6deaa3bb6a7aedb811d2011debe42166.definition = {
    methods: ["get","head"],
    url: '/titannexus/layouts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titannexus/layouts'
*/
ListLayout6deaa3bb6a7aedb811d2011debe42166.url = (options?: RouteQueryOptions) => {
    return ListLayout6deaa3bb6a7aedb811d2011debe42166.definition.url + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titannexus/layouts'
*/
ListLayout6deaa3bb6a7aedb811d2011debe42166.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLayout6deaa3bb6a7aedb811d2011debe42166.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titannexus/layouts'
*/
ListLayout6deaa3bb6a7aedb811d2011debe42166.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListLayout6deaa3bb6a7aedb811d2011debe42166.url(options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titannexus/layouts'
*/
const ListLayout6deaa3bb6a7aedb811d2011debe42166Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLayout6deaa3bb6a7aedb811d2011debe42166.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titannexus/layouts'
*/
ListLayout6deaa3bb6a7aedb811d2011debe42166Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLayout6deaa3bb6a7aedb811d2011debe42166.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\ListLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/ListLayout.php:7
* @route '/titannexus/layouts'
*/
ListLayout6deaa3bb6a7aedb811d2011debe42166Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLayout6deaa3bb6a7aedb811d2011debe42166.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListLayout6deaa3bb6a7aedb811d2011debe42166.form = ListLayout6deaa3bb6a7aedb811d2011debe42166Form

const ListLayout = {
    '/titanpro/layouts': ListLayout231a146d27ba266bc512ce12cafdcc40,
    '/groundzero/layouts': ListLayout1e9d65f543073a6195030ede4566a17b,
    '/titango/layouts': ListLayout1794914c2d8f5d8e1dfe3bd876e495c2,
    '/titansolo/layouts': ListLayout3bda7beb3e66af47073747720e9d3e88,
    '/titannexus/layouts': ListLayout6deaa3bb6a7aedb811d2011debe42166,
}

export default ListLayout