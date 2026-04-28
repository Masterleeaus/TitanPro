import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\EsoftTemplateController::root
* @see app/Http/Controllers/EsoftTemplateController.php:11
* @route '/templates/esoft'
*/
export const root = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: root.url(options),
    method: 'get',
})

root.definition = {
    methods: ["get","head"],
    url: '/templates/esoft',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EsoftTemplateController::root
* @see app/Http/Controllers/EsoftTemplateController.php:11
* @route '/templates/esoft'
*/
root.url = (options?: RouteQueryOptions) => {
    return root.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\EsoftTemplateController::root
* @see app/Http/Controllers/EsoftTemplateController.php:11
* @route '/templates/esoft'
*/
root.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: root.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::root
* @see app/Http/Controllers/EsoftTemplateController.php:11
* @route '/templates/esoft'
*/
root.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: root.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::root
* @see app/Http/Controllers/EsoftTemplateController.php:11
* @route '/templates/esoft'
*/
const rootForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: root.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::root
* @see app/Http/Controllers/EsoftTemplateController.php:11
* @route '/templates/esoft'
*/
rootForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: root.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::root
* @see app/Http/Controllers/EsoftTemplateController.php:11
* @route '/templates/esoft'
*/
rootForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: root.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

root.form = rootForm

/**
* @see \App\Http\Controllers\EsoftTemplateController::third
* @see app/Http/Controllers/EsoftTemplateController.php:26
* @route '/templates/esoft/{first}/{second}/{third}'
*/
export const third = (args: { first: string | number, second: string | number, third: string | number } | [first: string | number, second: string | number, third: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: third.url(args, options),
    method: 'get',
})

third.definition = {
    methods: ["get","head"],
    url: '/templates/esoft/{first}/{second}/{third}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EsoftTemplateController::third
* @see app/Http/Controllers/EsoftTemplateController.php:26
* @route '/templates/esoft/{first}/{second}/{third}'
*/
third.url = (args: { first: string | number, second: string | number, third: string | number } | [first: string | number, second: string | number, third: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            first: args[0],
            second: args[1],
            third: args[2],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        first: args.first,
        second: args.second,
        third: args.third,
    }

    return third.definition.url
            .replace('{first}', parsedArgs.first.toString())
            .replace('{second}', parsedArgs.second.toString())
            .replace('{third}', parsedArgs.third.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EsoftTemplateController::third
* @see app/Http/Controllers/EsoftTemplateController.php:26
* @route '/templates/esoft/{first}/{second}/{third}'
*/
third.get = (args: { first: string | number, second: string | number, third: string | number } | [first: string | number, second: string | number, third: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: third.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::third
* @see app/Http/Controllers/EsoftTemplateController.php:26
* @route '/templates/esoft/{first}/{second}/{third}'
*/
third.head = (args: { first: string | number, second: string | number, third: string | number } | [first: string | number, second: string | number, third: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: third.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::third
* @see app/Http/Controllers/EsoftTemplateController.php:26
* @route '/templates/esoft/{first}/{second}/{third}'
*/
const thirdForm = (args: { first: string | number, second: string | number, third: string | number } | [first: string | number, second: string | number, third: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: third.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::third
* @see app/Http/Controllers/EsoftTemplateController.php:26
* @route '/templates/esoft/{first}/{second}/{third}'
*/
thirdForm.get = (args: { first: string | number, second: string | number, third: string | number } | [first: string | number, second: string | number, third: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: third.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::third
* @see app/Http/Controllers/EsoftTemplateController.php:26
* @route '/templates/esoft/{first}/{second}/{third}'
*/
thirdForm.head = (args: { first: string | number, second: string | number, third: string | number } | [first: string | number, second: string | number, third: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: third.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

third.form = thirdForm

/**
* @see \App\Http\Controllers\EsoftTemplateController::second
* @see app/Http/Controllers/EsoftTemplateController.php:21
* @route '/templates/esoft/{first}/{second}'
*/
export const second = (args: { first: string | number, second: string | number } | [first: string | number, second: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: second.url(args, options),
    method: 'get',
})

second.definition = {
    methods: ["get","head"],
    url: '/templates/esoft/{first}/{second}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EsoftTemplateController::second
* @see app/Http/Controllers/EsoftTemplateController.php:21
* @route '/templates/esoft/{first}/{second}'
*/
second.url = (args: { first: string | number, second: string | number } | [first: string | number, second: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            first: args[0],
            second: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        first: args.first,
        second: args.second,
    }

    return second.definition.url
            .replace('{first}', parsedArgs.first.toString())
            .replace('{second}', parsedArgs.second.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EsoftTemplateController::second
* @see app/Http/Controllers/EsoftTemplateController.php:21
* @route '/templates/esoft/{first}/{second}'
*/
second.get = (args: { first: string | number, second: string | number } | [first: string | number, second: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: second.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::second
* @see app/Http/Controllers/EsoftTemplateController.php:21
* @route '/templates/esoft/{first}/{second}'
*/
second.head = (args: { first: string | number, second: string | number } | [first: string | number, second: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: second.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::second
* @see app/Http/Controllers/EsoftTemplateController.php:21
* @route '/templates/esoft/{first}/{second}'
*/
const secondForm = (args: { first: string | number, second: string | number } | [first: string | number, second: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: second.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::second
* @see app/Http/Controllers/EsoftTemplateController.php:21
* @route '/templates/esoft/{first}/{second}'
*/
secondForm.get = (args: { first: string | number, second: string | number } | [first: string | number, second: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: second.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::second
* @see app/Http/Controllers/EsoftTemplateController.php:21
* @route '/templates/esoft/{first}/{second}'
*/
secondForm.head = (args: { first: string | number, second: string | number } | [first: string | number, second: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: second.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

second.form = secondForm

/**
* @see \App\Http\Controllers\EsoftTemplateController::any
* @see app/Http/Controllers/EsoftTemplateController.php:16
* @route '/templates/esoft/{any}'
*/
export const any = (args: { any: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: any.url(args, options),
    method: 'get',
})

any.definition = {
    methods: ["get","head"],
    url: '/templates/esoft/{any}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EsoftTemplateController::any
* @see app/Http/Controllers/EsoftTemplateController.php:16
* @route '/templates/esoft/{any}'
*/
any.url = (args: { any: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { any: args }
    }

    if (Array.isArray(args)) {
        args = {
            any: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        any: args.any,
    }

    return any.definition.url
            .replace('{any}', parsedArgs.any.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EsoftTemplateController::any
* @see app/Http/Controllers/EsoftTemplateController.php:16
* @route '/templates/esoft/{any}'
*/
any.get = (args: { any: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: any.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::any
* @see app/Http/Controllers/EsoftTemplateController.php:16
* @route '/templates/esoft/{any}'
*/
any.head = (args: { any: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: any.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::any
* @see app/Http/Controllers/EsoftTemplateController.php:16
* @route '/templates/esoft/{any}'
*/
const anyForm = (args: { any: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: any.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::any
* @see app/Http/Controllers/EsoftTemplateController.php:16
* @route '/templates/esoft/{any}'
*/
anyForm.get = (args: { any: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: any.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::any
* @see app/Http/Controllers/EsoftTemplateController.php:16
* @route '/templates/esoft/{any}'
*/
anyForm.head = (args: { any: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: any.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

any.form = anyForm

const esoft = {
    root: Object.assign(root, root),
    third: Object.assign(third, third),
    second: Object.assign(second, second),
    any: Object.assign(any, any),
}

export default esoft