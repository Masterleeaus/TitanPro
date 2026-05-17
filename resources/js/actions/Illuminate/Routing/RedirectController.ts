import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
const RedirectController0b36e5c94e8a3e33df1758bc4731a883 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url(options),
    method: 'get',
})

RedirectController0b36e5c94e8a3e33df1758bc4731a883.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/owner/dispatch',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
RedirectController0b36e5c94e8a3e33df1758bc4731a883.url = (options?: RouteQueryOptions) => {
    return RedirectController0b36e5c94e8a3e33df1758bc4731a883.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
RedirectController0b36e5c94e8a3e33df1758bc4731a883.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
RedirectController0b36e5c94e8a3e33df1758bc4731a883.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
RedirectController0b36e5c94e8a3e33df1758bc4731a883.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
RedirectController0b36e5c94e8a3e33df1758bc4731a883.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
RedirectController0b36e5c94e8a3e33df1758bc4731a883.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
RedirectController0b36e5c94e8a3e33df1758bc4731a883.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
RedirectController0b36e5c94e8a3e33df1758bc4731a883.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
const RedirectController0b36e5c94e8a3e33df1758bc4731a883Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
RedirectController0b36e5c94e8a3e33df1758bc4731a883Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
RedirectController0b36e5c94e8a3e33df1758bc4731a883Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
RedirectController0b36e5c94e8a3e33df1758bc4731a883Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
RedirectController0b36e5c94e8a3e33df1758bc4731a883Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
RedirectController0b36e5c94e8a3e33df1758bc4731a883Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
RedirectController0b36e5c94e8a3e33df1758bc4731a883Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/dispatch'
*/
RedirectController0b36e5c94e8a3e33df1758bc4731a883Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController0b36e5c94e8a3e33df1758bc4731a883.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectController0b36e5c94e8a3e33df1758bc4731a883.form = RedirectController0b36e5c94e8a3e33df1758bc4731a883Form
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
const RedirectController403520ec299eece33bcc4213db7e0304 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController403520ec299eece33bcc4213db7e0304.url(options),
    method: 'get',
})

RedirectController403520ec299eece33bcc4213db7e0304.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/owner/billing',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
RedirectController403520ec299eece33bcc4213db7e0304.url = (options?: RouteQueryOptions) => {
    return RedirectController403520ec299eece33bcc4213db7e0304.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
RedirectController403520ec299eece33bcc4213db7e0304.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController403520ec299eece33bcc4213db7e0304.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
RedirectController403520ec299eece33bcc4213db7e0304.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController403520ec299eece33bcc4213db7e0304.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
RedirectController403520ec299eece33bcc4213db7e0304.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController403520ec299eece33bcc4213db7e0304.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
RedirectController403520ec299eece33bcc4213db7e0304.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController403520ec299eece33bcc4213db7e0304.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
RedirectController403520ec299eece33bcc4213db7e0304.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController403520ec299eece33bcc4213db7e0304.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
RedirectController403520ec299eece33bcc4213db7e0304.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController403520ec299eece33bcc4213db7e0304.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
RedirectController403520ec299eece33bcc4213db7e0304.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController403520ec299eece33bcc4213db7e0304.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
const RedirectController403520ec299eece33bcc4213db7e0304Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController403520ec299eece33bcc4213db7e0304.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
RedirectController403520ec299eece33bcc4213db7e0304Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController403520ec299eece33bcc4213db7e0304.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
RedirectController403520ec299eece33bcc4213db7e0304Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController403520ec299eece33bcc4213db7e0304.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
RedirectController403520ec299eece33bcc4213db7e0304Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController403520ec299eece33bcc4213db7e0304.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
RedirectController403520ec299eece33bcc4213db7e0304Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController403520ec299eece33bcc4213db7e0304.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
RedirectController403520ec299eece33bcc4213db7e0304Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController403520ec299eece33bcc4213db7e0304.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
RedirectController403520ec299eece33bcc4213db7e0304Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController403520ec299eece33bcc4213db7e0304.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/billing'
*/
RedirectController403520ec299eece33bcc4213db7e0304Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController403520ec299eece33bcc4213db7e0304.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectController403520ec299eece33bcc4213db7e0304.form = RedirectController403520ec299eece33bcc4213db7e0304Form
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
const RedirectControllerca39ef003338b9fa59afaf80cf898df6 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url(options),
    method: 'get',
})

RedirectControllerca39ef003338b9fa59afaf80cf898df6.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/owner/estimates',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
RedirectControllerca39ef003338b9fa59afaf80cf898df6.url = (options?: RouteQueryOptions) => {
    return RedirectControllerca39ef003338b9fa59afaf80cf898df6.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
RedirectControllerca39ef003338b9fa59afaf80cf898df6.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
RedirectControllerca39ef003338b9fa59afaf80cf898df6.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
RedirectControllerca39ef003338b9fa59afaf80cf898df6.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
RedirectControllerca39ef003338b9fa59afaf80cf898df6.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
RedirectControllerca39ef003338b9fa59afaf80cf898df6.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
RedirectControllerca39ef003338b9fa59afaf80cf898df6.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
RedirectControllerca39ef003338b9fa59afaf80cf898df6.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
const RedirectControllerca39ef003338b9fa59afaf80cf898df6Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
RedirectControllerca39ef003338b9fa59afaf80cf898df6Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
RedirectControllerca39ef003338b9fa59afaf80cf898df6Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
RedirectControllerca39ef003338b9fa59afaf80cf898df6Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
RedirectControllerca39ef003338b9fa59afaf80cf898df6Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
RedirectControllerca39ef003338b9fa59afaf80cf898df6Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
RedirectControllerca39ef003338b9fa59afaf80cf898df6Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/estimates'
*/
RedirectControllerca39ef003338b9fa59afaf80cf898df6Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerca39ef003338b9fa59afaf80cf898df6.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectControllerca39ef003338b9fa59afaf80cf898df6.form = RedirectControllerca39ef003338b9fa59afaf80cf898df6Form
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
const RedirectController9064f533c64a712067bdce32f1fa7b9a = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController9064f533c64a712067bdce32f1fa7b9a.url(options),
    method: 'get',
})

RedirectController9064f533c64a712067bdce32f1fa7b9a.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/owner/marketing',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
RedirectController9064f533c64a712067bdce32f1fa7b9a.url = (options?: RouteQueryOptions) => {
    return RedirectController9064f533c64a712067bdce32f1fa7b9a.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
RedirectController9064f533c64a712067bdce32f1fa7b9a.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController9064f533c64a712067bdce32f1fa7b9a.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
RedirectController9064f533c64a712067bdce32f1fa7b9a.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController9064f533c64a712067bdce32f1fa7b9a.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
RedirectController9064f533c64a712067bdce32f1fa7b9a.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController9064f533c64a712067bdce32f1fa7b9a.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
RedirectController9064f533c64a712067bdce32f1fa7b9a.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController9064f533c64a712067bdce32f1fa7b9a.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
RedirectController9064f533c64a712067bdce32f1fa7b9a.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController9064f533c64a712067bdce32f1fa7b9a.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
RedirectController9064f533c64a712067bdce32f1fa7b9a.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController9064f533c64a712067bdce32f1fa7b9a.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
RedirectController9064f533c64a712067bdce32f1fa7b9a.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController9064f533c64a712067bdce32f1fa7b9a.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
const RedirectController9064f533c64a712067bdce32f1fa7b9aForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController9064f533c64a712067bdce32f1fa7b9a.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
RedirectController9064f533c64a712067bdce32f1fa7b9aForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController9064f533c64a712067bdce32f1fa7b9a.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
RedirectController9064f533c64a712067bdce32f1fa7b9aForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController9064f533c64a712067bdce32f1fa7b9a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
RedirectController9064f533c64a712067bdce32f1fa7b9aForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController9064f533c64a712067bdce32f1fa7b9a.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
RedirectController9064f533c64a712067bdce32f1fa7b9aForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController9064f533c64a712067bdce32f1fa7b9a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
RedirectController9064f533c64a712067bdce32f1fa7b9aForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController9064f533c64a712067bdce32f1fa7b9a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
RedirectController9064f533c64a712067bdce32f1fa7b9aForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController9064f533c64a712067bdce32f1fa7b9a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/owner/marketing'
*/
RedirectController9064f533c64a712067bdce32f1fa7b9aForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController9064f533c64a712067bdce32f1fa7b9a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectController9064f533c64a712067bdce32f1fa7b9a.form = RedirectController9064f533c64a712067bdce32f1fa7b9aForm
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
const RedirectController35f58437d9250c39f332f5e8e70440b7 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'get',
})

RedirectController35f58437d9250c39f332f5e8e70440b7.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/admin',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.url = (options?: RouteQueryOptions) => {
    return RedirectController35f58437d9250c39f332f5e8e70440b7.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
const RedirectController35f58437d9250c39f332f5e8e70440b7Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/admin'
*/
RedirectController35f58437d9250c39f332f5e8e70440b7Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController35f58437d9250c39f332f5e8e70440b7.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectController35f58437d9250c39f332f5e8e70440b7.form = RedirectController35f58437d9250c39f332f5e8e70440b7Form
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
const RedirectControllercf3000a670e5ec008396a849f4be1405 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllercf3000a670e5ec008396a849f4be1405.url(options),
    method: 'get',
})

RedirectControllercf3000a670e5ec008396a849f4be1405.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/ground-zero',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
RedirectControllercf3000a670e5ec008396a849f4be1405.url = (options?: RouteQueryOptions) => {
    return RedirectControllercf3000a670e5ec008396a849f4be1405.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
RedirectControllercf3000a670e5ec008396a849f4be1405.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllercf3000a670e5ec008396a849f4be1405.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
RedirectControllercf3000a670e5ec008396a849f4be1405.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectControllercf3000a670e5ec008396a849f4be1405.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
RedirectControllercf3000a670e5ec008396a849f4be1405.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectControllercf3000a670e5ec008396a849f4be1405.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
RedirectControllercf3000a670e5ec008396a849f4be1405.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectControllercf3000a670e5ec008396a849f4be1405.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
RedirectControllercf3000a670e5ec008396a849f4be1405.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectControllercf3000a670e5ec008396a849f4be1405.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
RedirectControllercf3000a670e5ec008396a849f4be1405.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectControllercf3000a670e5ec008396a849f4be1405.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
RedirectControllercf3000a670e5ec008396a849f4be1405.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectControllercf3000a670e5ec008396a849f4be1405.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
const RedirectControllercf3000a670e5ec008396a849f4be1405Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllercf3000a670e5ec008396a849f4be1405.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
RedirectControllercf3000a670e5ec008396a849f4be1405Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllercf3000a670e5ec008396a849f4be1405.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
RedirectControllercf3000a670e5ec008396a849f4be1405Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllercf3000a670e5ec008396a849f4be1405.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
RedirectControllercf3000a670e5ec008396a849f4be1405Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllercf3000a670e5ec008396a849f4be1405.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
RedirectControllercf3000a670e5ec008396a849f4be1405Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllercf3000a670e5ec008396a849f4be1405.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
RedirectControllercf3000a670e5ec008396a849f4be1405Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllercf3000a670e5ec008396a849f4be1405.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
RedirectControllercf3000a670e5ec008396a849f4be1405Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllercf3000a670e5ec008396a849f4be1405.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/ground-zero'
*/
RedirectControllercf3000a670e5ec008396a849f4be1405Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllercf3000a670e5ec008396a849f4be1405.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectControllercf3000a670e5ec008396a849f4be1405.form = RedirectControllercf3000a670e5ec008396a849f4be1405Form
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
const RedirectController7c9843a68399e58a9985173364eb08ac = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController7c9843a68399e58a9985173364eb08ac.url(options),
    method: 'get',
})

RedirectController7c9843a68399e58a9985173364eb08ac.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/titan-go',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
RedirectController7c9843a68399e58a9985173364eb08ac.url = (options?: RouteQueryOptions) => {
    return RedirectController7c9843a68399e58a9985173364eb08ac.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
RedirectController7c9843a68399e58a9985173364eb08ac.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController7c9843a68399e58a9985173364eb08ac.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
RedirectController7c9843a68399e58a9985173364eb08ac.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController7c9843a68399e58a9985173364eb08ac.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
RedirectController7c9843a68399e58a9985173364eb08ac.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController7c9843a68399e58a9985173364eb08ac.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
RedirectController7c9843a68399e58a9985173364eb08ac.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController7c9843a68399e58a9985173364eb08ac.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
RedirectController7c9843a68399e58a9985173364eb08ac.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController7c9843a68399e58a9985173364eb08ac.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
RedirectController7c9843a68399e58a9985173364eb08ac.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController7c9843a68399e58a9985173364eb08ac.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
RedirectController7c9843a68399e58a9985173364eb08ac.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController7c9843a68399e58a9985173364eb08ac.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
const RedirectController7c9843a68399e58a9985173364eb08acForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController7c9843a68399e58a9985173364eb08ac.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
RedirectController7c9843a68399e58a9985173364eb08acForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController7c9843a68399e58a9985173364eb08ac.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
RedirectController7c9843a68399e58a9985173364eb08acForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController7c9843a68399e58a9985173364eb08ac.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
RedirectController7c9843a68399e58a9985173364eb08acForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController7c9843a68399e58a9985173364eb08ac.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
RedirectController7c9843a68399e58a9985173364eb08acForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController7c9843a68399e58a9985173364eb08ac.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
RedirectController7c9843a68399e58a9985173364eb08acForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController7c9843a68399e58a9985173364eb08ac.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
RedirectController7c9843a68399e58a9985173364eb08acForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController7c9843a68399e58a9985173364eb08ac.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-go'
*/
RedirectController7c9843a68399e58a9985173364eb08acForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController7c9843a68399e58a9985173364eb08ac.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectController7c9843a68399e58a9985173364eb08ac.form = RedirectController7c9843a68399e58a9985173364eb08acForm
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
const RedirectControllerac33588b5b9409039d009742a577ba66 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllerac33588b5b9409039d009742a577ba66.url(options),
    method: 'get',
})

RedirectControllerac33588b5b9409039d009742a577ba66.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/titan-quotes',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
RedirectControllerac33588b5b9409039d009742a577ba66.url = (options?: RouteQueryOptions) => {
    return RedirectControllerac33588b5b9409039d009742a577ba66.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
RedirectControllerac33588b5b9409039d009742a577ba66.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllerac33588b5b9409039d009742a577ba66.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
RedirectControllerac33588b5b9409039d009742a577ba66.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectControllerac33588b5b9409039d009742a577ba66.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
RedirectControllerac33588b5b9409039d009742a577ba66.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectControllerac33588b5b9409039d009742a577ba66.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
RedirectControllerac33588b5b9409039d009742a577ba66.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectControllerac33588b5b9409039d009742a577ba66.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
RedirectControllerac33588b5b9409039d009742a577ba66.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectControllerac33588b5b9409039d009742a577ba66.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
RedirectControllerac33588b5b9409039d009742a577ba66.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectControllerac33588b5b9409039d009742a577ba66.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
RedirectControllerac33588b5b9409039d009742a577ba66.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectControllerac33588b5b9409039d009742a577ba66.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
const RedirectControllerac33588b5b9409039d009742a577ba66Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerac33588b5b9409039d009742a577ba66.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
RedirectControllerac33588b5b9409039d009742a577ba66Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerac33588b5b9409039d009742a577ba66.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
RedirectControllerac33588b5b9409039d009742a577ba66Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerac33588b5b9409039d009742a577ba66.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
RedirectControllerac33588b5b9409039d009742a577ba66Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerac33588b5b9409039d009742a577ba66.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
RedirectControllerac33588b5b9409039d009742a577ba66Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerac33588b5b9409039d009742a577ba66.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
RedirectControllerac33588b5b9409039d009742a577ba66Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerac33588b5b9409039d009742a577ba66.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
RedirectControllerac33588b5b9409039d009742a577ba66Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerac33588b5b9409039d009742a577ba66.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-quotes'
*/
RedirectControllerac33588b5b9409039d009742a577ba66Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerac33588b5b9409039d009742a577ba66.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectControllerac33588b5b9409039d009742a577ba66.form = RedirectControllerac33588b5b9409039d009742a577ba66Form
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
const RedirectController8729799fb147e60a53dc97bbd67c152a = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController8729799fb147e60a53dc97bbd67c152a.url(options),
    method: 'get',
})

RedirectController8729799fb147e60a53dc97bbd67c152a.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/titan-grow',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
RedirectController8729799fb147e60a53dc97bbd67c152a.url = (options?: RouteQueryOptions) => {
    return RedirectController8729799fb147e60a53dc97bbd67c152a.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
RedirectController8729799fb147e60a53dc97bbd67c152a.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController8729799fb147e60a53dc97bbd67c152a.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
RedirectController8729799fb147e60a53dc97bbd67c152a.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController8729799fb147e60a53dc97bbd67c152a.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
RedirectController8729799fb147e60a53dc97bbd67c152a.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController8729799fb147e60a53dc97bbd67c152a.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
RedirectController8729799fb147e60a53dc97bbd67c152a.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController8729799fb147e60a53dc97bbd67c152a.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
RedirectController8729799fb147e60a53dc97bbd67c152a.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController8729799fb147e60a53dc97bbd67c152a.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
RedirectController8729799fb147e60a53dc97bbd67c152a.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController8729799fb147e60a53dc97bbd67c152a.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
RedirectController8729799fb147e60a53dc97bbd67c152a.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController8729799fb147e60a53dc97bbd67c152a.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
const RedirectController8729799fb147e60a53dc97bbd67c152aForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController8729799fb147e60a53dc97bbd67c152a.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
RedirectController8729799fb147e60a53dc97bbd67c152aForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController8729799fb147e60a53dc97bbd67c152a.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
RedirectController8729799fb147e60a53dc97bbd67c152aForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController8729799fb147e60a53dc97bbd67c152a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
RedirectController8729799fb147e60a53dc97bbd67c152aForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController8729799fb147e60a53dc97bbd67c152a.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
RedirectController8729799fb147e60a53dc97bbd67c152aForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController8729799fb147e60a53dc97bbd67c152a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
RedirectController8729799fb147e60a53dc97bbd67c152aForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController8729799fb147e60a53dc97bbd67c152a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
RedirectController8729799fb147e60a53dc97bbd67c152aForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController8729799fb147e60a53dc97bbd67c152a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-grow'
*/
RedirectController8729799fb147e60a53dc97bbd67c152aForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController8729799fb147e60a53dc97bbd67c152a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectController8729799fb147e60a53dc97bbd67c152a.form = RedirectController8729799fb147e60a53dc97bbd67c152aForm
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
const RedirectControllerc8859a136d4fe1916a84de8afe50cb96 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url(options),
    method: 'get',
})

RedirectControllerc8859a136d4fe1916a84de8afe50cb96.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/titan-nexus',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url = (options?: RouteQueryOptions) => {
    return RedirectControllerc8859a136d4fe1916a84de8afe50cb96.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
RedirectControllerc8859a136d4fe1916a84de8afe50cb96.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
RedirectControllerc8859a136d4fe1916a84de8afe50cb96.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
RedirectControllerc8859a136d4fe1916a84de8afe50cb96.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
RedirectControllerc8859a136d4fe1916a84de8afe50cb96.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
RedirectControllerc8859a136d4fe1916a84de8afe50cb96.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
RedirectControllerc8859a136d4fe1916a84de8afe50cb96.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
RedirectControllerc8859a136d4fe1916a84de8afe50cb96.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
const RedirectControllerc8859a136d4fe1916a84de8afe50cb96Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
RedirectControllerc8859a136d4fe1916a84de8afe50cb96Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
RedirectControllerc8859a136d4fe1916a84de8afe50cb96Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
RedirectControllerc8859a136d4fe1916a84de8afe50cb96Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
RedirectControllerc8859a136d4fe1916a84de8afe50cb96Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
RedirectControllerc8859a136d4fe1916a84de8afe50cb96Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
RedirectControllerc8859a136d4fe1916a84de8afe50cb96Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/titan-nexus'
*/
RedirectControllerc8859a136d4fe1916a84de8afe50cb96Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectControllerc8859a136d4fe1916a84de8afe50cb96.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectControllerc8859a136d4fe1916a84de8afe50cb96.form = RedirectControllerc8859a136d4fe1916a84de8afe50cb96Form
/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
const RedirectController4b87d2df7e3aa853f6720faea796e36c = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'get',
})

RedirectController4b87d2df7e3aa853f6720faea796e36c.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/settings',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.url = (options?: RouteQueryOptions) => {
    return RedirectController4b87d2df7e3aa853f6720faea796e36c.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'head',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'put',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'patch',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'delete',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36c.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'options',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
const RedirectController4b87d2df7e3aa853f6720faea796e36cForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36cForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36cForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36cForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36cForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36cForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36cForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \Illuminate\Routing\RedirectController::__invoke
* @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
* @route '/settings'
*/
RedirectController4b87d2df7e3aa853f6720faea796e36cForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'OPTIONS',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RedirectController4b87d2df7e3aa853f6720faea796e36c.form = RedirectController4b87d2df7e3aa853f6720faea796e36cForm

const RedirectController = {
    '/owner/dispatch': RedirectController0b36e5c94e8a3e33df1758bc4731a883,
    '/owner/billing': RedirectController403520ec299eece33bcc4213db7e0304,
    '/owner/estimates': RedirectControllerca39ef003338b9fa59afaf80cf898df6,
    '/owner/marketing': RedirectController9064f533c64a712067bdce32f1fa7b9a,
    '/admin': RedirectController35f58437d9250c39f332f5e8e70440b7,
    '/ground-zero': RedirectControllercf3000a670e5ec008396a849f4be1405,
    '/titan-go': RedirectController7c9843a68399e58a9985173364eb08ac,
    '/titan-quotes': RedirectControllerac33588b5b9409039d009742a577ba66,
    '/titan-grow': RedirectController8729799fb147e60a53dc97bbd67c152a,
    '/titan-nexus': RedirectControllerc8859a136d4fe1916a84de8afe50cb96,
    '/settings': RedirectController4b87d2df7e3aa853f6720faea796e36c,
}

export default RedirectController