import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titanpro/layouts/create'
*/
const CreateLayoutfeee7bcb538de01124c50e0ed4f58f40 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLayoutfeee7bcb538de01124c50e0ed4f58f40.url(options),
    method: 'get',
})

CreateLayoutfeee7bcb538de01124c50e0ed4f58f40.definition = {
    methods: ["get","head"],
    url: '/titanpro/layouts/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titanpro/layouts/create'
*/
CreateLayoutfeee7bcb538de01124c50e0ed4f58f40.url = (options?: RouteQueryOptions) => {
    return CreateLayoutfeee7bcb538de01124c50e0ed4f58f40.definition.url + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titanpro/layouts/create'
*/
CreateLayoutfeee7bcb538de01124c50e0ed4f58f40.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLayoutfeee7bcb538de01124c50e0ed4f58f40.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titanpro/layouts/create'
*/
CreateLayoutfeee7bcb538de01124c50e0ed4f58f40.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateLayoutfeee7bcb538de01124c50e0ed4f58f40.url(options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titanpro/layouts/create'
*/
const CreateLayoutfeee7bcb538de01124c50e0ed4f58f40Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLayoutfeee7bcb538de01124c50e0ed4f58f40.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titanpro/layouts/create'
*/
CreateLayoutfeee7bcb538de01124c50e0ed4f58f40Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLayoutfeee7bcb538de01124c50e0ed4f58f40.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titanpro/layouts/create'
*/
CreateLayoutfeee7bcb538de01124c50e0ed4f58f40Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLayoutfeee7bcb538de01124c50e0ed4f58f40.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateLayoutfeee7bcb538de01124c50e0ed4f58f40.form = CreateLayoutfeee7bcb538de01124c50e0ed4f58f40Form
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/groundzero/layouts/create'
*/
const CreateLayoutb12de6d85317fd58051ef20de3e70665 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLayoutb12de6d85317fd58051ef20de3e70665.url(options),
    method: 'get',
})

CreateLayoutb12de6d85317fd58051ef20de3e70665.definition = {
    methods: ["get","head"],
    url: '/groundzero/layouts/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/groundzero/layouts/create'
*/
CreateLayoutb12de6d85317fd58051ef20de3e70665.url = (options?: RouteQueryOptions) => {
    return CreateLayoutb12de6d85317fd58051ef20de3e70665.definition.url + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/groundzero/layouts/create'
*/
CreateLayoutb12de6d85317fd58051ef20de3e70665.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLayoutb12de6d85317fd58051ef20de3e70665.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/groundzero/layouts/create'
*/
CreateLayoutb12de6d85317fd58051ef20de3e70665.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateLayoutb12de6d85317fd58051ef20de3e70665.url(options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/groundzero/layouts/create'
*/
const CreateLayoutb12de6d85317fd58051ef20de3e70665Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLayoutb12de6d85317fd58051ef20de3e70665.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/groundzero/layouts/create'
*/
CreateLayoutb12de6d85317fd58051ef20de3e70665Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLayoutb12de6d85317fd58051ef20de3e70665.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/groundzero/layouts/create'
*/
CreateLayoutb12de6d85317fd58051ef20de3e70665Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLayoutb12de6d85317fd58051ef20de3e70665.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateLayoutb12de6d85317fd58051ef20de3e70665.form = CreateLayoutb12de6d85317fd58051ef20de3e70665Form
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titango/layouts/create'
*/
const CreateLayout703a9977d9281b160d24dec838435014 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLayout703a9977d9281b160d24dec838435014.url(options),
    method: 'get',
})

CreateLayout703a9977d9281b160d24dec838435014.definition = {
    methods: ["get","head"],
    url: '/titango/layouts/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titango/layouts/create'
*/
CreateLayout703a9977d9281b160d24dec838435014.url = (options?: RouteQueryOptions) => {
    return CreateLayout703a9977d9281b160d24dec838435014.definition.url + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titango/layouts/create'
*/
CreateLayout703a9977d9281b160d24dec838435014.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLayout703a9977d9281b160d24dec838435014.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titango/layouts/create'
*/
CreateLayout703a9977d9281b160d24dec838435014.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateLayout703a9977d9281b160d24dec838435014.url(options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titango/layouts/create'
*/
const CreateLayout703a9977d9281b160d24dec838435014Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLayout703a9977d9281b160d24dec838435014.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titango/layouts/create'
*/
CreateLayout703a9977d9281b160d24dec838435014Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLayout703a9977d9281b160d24dec838435014.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titango/layouts/create'
*/
CreateLayout703a9977d9281b160d24dec838435014Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLayout703a9977d9281b160d24dec838435014.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateLayout703a9977d9281b160d24dec838435014.form = CreateLayout703a9977d9281b160d24dec838435014Form
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titansolo/layouts/create'
*/
const CreateLayout69292db12983bda34af3428c98f07d83 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLayout69292db12983bda34af3428c98f07d83.url(options),
    method: 'get',
})

CreateLayout69292db12983bda34af3428c98f07d83.definition = {
    methods: ["get","head"],
    url: '/titansolo/layouts/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titansolo/layouts/create'
*/
CreateLayout69292db12983bda34af3428c98f07d83.url = (options?: RouteQueryOptions) => {
    return CreateLayout69292db12983bda34af3428c98f07d83.definition.url + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titansolo/layouts/create'
*/
CreateLayout69292db12983bda34af3428c98f07d83.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLayout69292db12983bda34af3428c98f07d83.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titansolo/layouts/create'
*/
CreateLayout69292db12983bda34af3428c98f07d83.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateLayout69292db12983bda34af3428c98f07d83.url(options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titansolo/layouts/create'
*/
const CreateLayout69292db12983bda34af3428c98f07d83Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLayout69292db12983bda34af3428c98f07d83.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titansolo/layouts/create'
*/
CreateLayout69292db12983bda34af3428c98f07d83Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLayout69292db12983bda34af3428c98f07d83.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titansolo/layouts/create'
*/
CreateLayout69292db12983bda34af3428c98f07d83Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLayout69292db12983bda34af3428c98f07d83.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateLayout69292db12983bda34af3428c98f07d83.form = CreateLayout69292db12983bda34af3428c98f07d83Form
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titannexus/layouts/create'
*/
const CreateLayoutf946f3e825e85fc93955593e78e7baac = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLayoutf946f3e825e85fc93955593e78e7baac.url(options),
    method: 'get',
})

CreateLayoutf946f3e825e85fc93955593e78e7baac.definition = {
    methods: ["get","head"],
    url: '/titannexus/layouts/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titannexus/layouts/create'
*/
CreateLayoutf946f3e825e85fc93955593e78e7baac.url = (options?: RouteQueryOptions) => {
    return CreateLayoutf946f3e825e85fc93955593e78e7baac.definition.url + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titannexus/layouts/create'
*/
CreateLayoutf946f3e825e85fc93955593e78e7baac.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLayoutf946f3e825e85fc93955593e78e7baac.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titannexus/layouts/create'
*/
CreateLayoutf946f3e825e85fc93955593e78e7baac.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateLayoutf946f3e825e85fc93955593e78e7baac.url(options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titannexus/layouts/create'
*/
const CreateLayoutf946f3e825e85fc93955593e78e7baacForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLayoutf946f3e825e85fc93955593e78e7baac.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titannexus/layouts/create'
*/
CreateLayoutf946f3e825e85fc93955593e78e7baacForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLayoutf946f3e825e85fc93955593e78e7baac.url(options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\CreateLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/CreateLayout.php:7
* @route '/titannexus/layouts/create'
*/
CreateLayoutf946f3e825e85fc93955593e78e7baacForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLayoutf946f3e825e85fc93955593e78e7baac.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateLayoutf946f3e825e85fc93955593e78e7baac.form = CreateLayoutf946f3e825e85fc93955593e78e7baacForm

const CreateLayout = {
    '/titanpro/layouts/create': CreateLayoutfeee7bcb538de01124c50e0ed4f58f40,
    '/groundzero/layouts/create': CreateLayoutb12de6d85317fd58051ef20de3e70665,
    '/titango/layouts/create': CreateLayout703a9977d9281b160d24dec838435014,
    '/titansolo/layouts/create': CreateLayout69292db12983bda34af3428c98f07d83,
    '/titannexus/layouts/create': CreateLayoutf946f3e825e85fc93955593e78e7baac,
}

export default CreateLayout