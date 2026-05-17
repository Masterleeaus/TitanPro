import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \Awcodes\Curator\Http\Controllers\MediaController::show
* @see vendor/awcodes/filament-curator/src/Http/Controllers/MediaController.php:27
* @route '/curator/{path}'
*/
export const show = (args: { path: string | number } | [path: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/curator/{path}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Awcodes\Curator\Http\Controllers\MediaController::show
* @see vendor/awcodes/filament-curator/src/Http/Controllers/MediaController.php:27
* @route '/curator/{path}'
*/
show.url = (args: { path: string | number } | [path: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { path: args }
    }

    if (Array.isArray(args)) {
        args = {
            path: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        path: args.path,
    }

    return show.definition.url
            .replace('{path}', parsedArgs.path.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Awcodes\Curator\Http\Controllers\MediaController::show
* @see vendor/awcodes/filament-curator/src/Http/Controllers/MediaController.php:27
* @route '/curator/{path}'
*/
show.get = (args: { path: string | number } | [path: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \Awcodes\Curator\Http\Controllers\MediaController::show
* @see vendor/awcodes/filament-curator/src/Http/Controllers/MediaController.php:27
* @route '/curator/{path}'
*/
show.head = (args: { path: string | number } | [path: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \Awcodes\Curator\Http\Controllers\MediaController::show
* @see vendor/awcodes/filament-curator/src/Http/Controllers/MediaController.php:27
* @route '/curator/{path}'
*/
const showForm = (args: { path: string | number } | [path: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \Awcodes\Curator\Http\Controllers\MediaController::show
* @see vendor/awcodes/filament-curator/src/Http/Controllers/MediaController.php:27
* @route '/curator/{path}'
*/
showForm.get = (args: { path: string | number } | [path: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \Awcodes\Curator\Http\Controllers\MediaController::show
* @see vendor/awcodes/filament-curator/src/Http/Controllers/MediaController.php:27
* @route '/curator/{path}'
*/
showForm.head = (args: { path: string | number } | [path: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const MediaController = { show }

export default MediaController