import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults, validateParameters } from './../../../../wayfinder'
/**
* @see \LaraZeus\DynamicDashboard\Livewire\Layouts::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Livewire/Layouts.php:7
* @route '/dynamic-dashboard/{slug?}'
*/
const Layouts = (args?: { slug?: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Layouts.url(args, options),
    method: 'get',
})

Layouts.definition = {
    methods: ["get","head"],
    url: '/dynamic-dashboard/{slug?}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Livewire\Layouts::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Livewire/Layouts.php:7
* @route '/dynamic-dashboard/{slug?}'
*/
Layouts.url = (args?: { slug?: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { slug: args }
    }

    if (Array.isArray(args)) {
        args = {
            slug: args[0],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "slug",
    ])

    const parsedArgs = {
        slug: args?.slug,
    }

    return Layouts.definition.url
            .replace('{slug?}', parsedArgs.slug?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Livewire\Layouts::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Livewire/Layouts.php:7
* @route '/dynamic-dashboard/{slug?}'
*/
Layouts.get = (args?: { slug?: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Layouts.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Livewire\Layouts::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Livewire/Layouts.php:7
* @route '/dynamic-dashboard/{slug?}'
*/
Layouts.head = (args?: { slug?: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Layouts.url(args, options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Livewire\Layouts::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Livewire/Layouts.php:7
* @route '/dynamic-dashboard/{slug?}'
*/
const LayoutsForm = (args?: { slug?: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Layouts.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Livewire\Layouts::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Livewire/Layouts.php:7
* @route '/dynamic-dashboard/{slug?}'
*/
LayoutsForm.get = (args?: { slug?: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Layouts.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Livewire\Layouts::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Livewire/Layouts.php:7
* @route '/dynamic-dashboard/{slug?}'
*/
LayoutsForm.head = (args?: { slug?: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Layouts.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Layouts.form = LayoutsForm

export default Layouts