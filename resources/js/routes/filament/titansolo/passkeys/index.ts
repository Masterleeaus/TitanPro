import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see vendor/jeffgreco13/filament-breezy/routes/web.php:26
* @route '/titansolo/passkeys/authentication-options'
*/
export const authentication_options = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: authentication_options.url(options),
    method: 'get',
})

authentication_options.definition = {
    methods: ["get","head"],
    url: '/titansolo/passkeys/authentication-options',
} satisfies RouteDefinition<["get","head"]>

/**
* @see vendor/jeffgreco13/filament-breezy/routes/web.php:26
* @route '/titansolo/passkeys/authentication-options'
*/
authentication_options.url = (options?: RouteQueryOptions) => {
    return authentication_options.definition.url + queryParams(options)
}

/**
* @see vendor/jeffgreco13/filament-breezy/routes/web.php:26
* @route '/titansolo/passkeys/authentication-options'
*/
authentication_options.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: authentication_options.url(options),
    method: 'get',
})

/**
* @see vendor/jeffgreco13/filament-breezy/routes/web.php:26
* @route '/titansolo/passkeys/authentication-options'
*/
authentication_options.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: authentication_options.url(options),
    method: 'head',
})

/**
* @see vendor/jeffgreco13/filament-breezy/routes/web.php:26
* @route '/titansolo/passkeys/authentication-options'
*/
const authentication_optionsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: authentication_options.url(options),
    method: 'get',
})

/**
* @see vendor/jeffgreco13/filament-breezy/routes/web.php:26
* @route '/titansolo/passkeys/authentication-options'
*/
authentication_optionsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: authentication_options.url(options),
    method: 'get',
})

/**
* @see vendor/jeffgreco13/filament-breezy/routes/web.php:26
* @route '/titansolo/passkeys/authentication-options'
*/
authentication_optionsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: authentication_options.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

authentication_options.form = authentication_optionsForm

const passkeys = {
    authentication_options: Object.assign(authentication_options, authentication_options),
}

export default passkeys