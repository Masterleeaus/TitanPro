import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
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
* @see \App\Http\Controllers\EsoftTemplateController::thirdLevel
* @see app/Http/Controllers/EsoftTemplateController.php:26
* @route '/templates/esoft/{first}/{second}/{third}'
*/
export const thirdLevel = (args: { first: string | number, second: string | number, third: string | number } | [first: string | number, second: string | number, third: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: thirdLevel.url(args, options),
    method: 'get',
})

thirdLevel.definition = {
    methods: ["get","head"],
    url: '/templates/esoft/{first}/{second}/{third}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EsoftTemplateController::thirdLevel
* @see app/Http/Controllers/EsoftTemplateController.php:26
* @route '/templates/esoft/{first}/{second}/{third}'
*/
thirdLevel.url = (args: { first: string | number, second: string | number, third: string | number } | [first: string | number, second: string | number, third: string | number ], options?: RouteQueryOptions) => {
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

    return thirdLevel.definition.url
            .replace('{first}', parsedArgs.first.toString())
            .replace('{second}', parsedArgs.second.toString())
            .replace('{third}', parsedArgs.third.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EsoftTemplateController::thirdLevel
* @see app/Http/Controllers/EsoftTemplateController.php:26
* @route '/templates/esoft/{first}/{second}/{third}'
*/
thirdLevel.get = (args: { first: string | number, second: string | number, third: string | number } | [first: string | number, second: string | number, third: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: thirdLevel.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::thirdLevel
* @see app/Http/Controllers/EsoftTemplateController.php:26
* @route '/templates/esoft/{first}/{second}/{third}'
*/
thirdLevel.head = (args: { first: string | number, second: string | number, third: string | number } | [first: string | number, second: string | number, third: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: thirdLevel.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::thirdLevel
* @see app/Http/Controllers/EsoftTemplateController.php:26
* @route '/templates/esoft/{first}/{second}/{third}'
*/
const thirdLevelForm = (args: { first: string | number, second: string | number, third: string | number } | [first: string | number, second: string | number, third: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: thirdLevel.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::thirdLevel
* @see app/Http/Controllers/EsoftTemplateController.php:26
* @route '/templates/esoft/{first}/{second}/{third}'
*/
thirdLevelForm.get = (args: { first: string | number, second: string | number, third: string | number } | [first: string | number, second: string | number, third: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: thirdLevel.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::thirdLevel
* @see app/Http/Controllers/EsoftTemplateController.php:26
* @route '/templates/esoft/{first}/{second}/{third}'
*/
thirdLevelForm.head = (args: { first: string | number, second: string | number, third: string | number } | [first: string | number, second: string | number, third: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: thirdLevel.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

thirdLevel.form = thirdLevelForm

/**
* @see \App\Http\Controllers\EsoftTemplateController::secondLevel
* @see app/Http/Controllers/EsoftTemplateController.php:21
* @route '/templates/esoft/{first}/{second}'
*/
export const secondLevel = (args: { first: string | number, second: string | number } | [first: string | number, second: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: secondLevel.url(args, options),
    method: 'get',
})

secondLevel.definition = {
    methods: ["get","head"],
    url: '/templates/esoft/{first}/{second}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EsoftTemplateController::secondLevel
* @see app/Http/Controllers/EsoftTemplateController.php:21
* @route '/templates/esoft/{first}/{second}'
*/
secondLevel.url = (args: { first: string | number, second: string | number } | [first: string | number, second: string | number ], options?: RouteQueryOptions) => {
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

    return secondLevel.definition.url
            .replace('{first}', parsedArgs.first.toString())
            .replace('{second}', parsedArgs.second.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EsoftTemplateController::secondLevel
* @see app/Http/Controllers/EsoftTemplateController.php:21
* @route '/templates/esoft/{first}/{second}'
*/
secondLevel.get = (args: { first: string | number, second: string | number } | [first: string | number, second: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: secondLevel.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::secondLevel
* @see app/Http/Controllers/EsoftTemplateController.php:21
* @route '/templates/esoft/{first}/{second}'
*/
secondLevel.head = (args: { first: string | number, second: string | number } | [first: string | number, second: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: secondLevel.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::secondLevel
* @see app/Http/Controllers/EsoftTemplateController.php:21
* @route '/templates/esoft/{first}/{second}'
*/
const secondLevelForm = (args: { first: string | number, second: string | number } | [first: string | number, second: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: secondLevel.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::secondLevel
* @see app/Http/Controllers/EsoftTemplateController.php:21
* @route '/templates/esoft/{first}/{second}'
*/
secondLevelForm.get = (args: { first: string | number, second: string | number } | [first: string | number, second: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: secondLevel.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::secondLevel
* @see app/Http/Controllers/EsoftTemplateController.php:21
* @route '/templates/esoft/{first}/{second}'
*/
secondLevelForm.head = (args: { first: string | number, second: string | number } | [first: string | number, second: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: secondLevel.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

secondLevel.form = secondLevelForm

/**
* @see \App\Http\Controllers\EsoftTemplateController::firstLevel
* @see app/Http/Controllers/EsoftTemplateController.php:16
* @route '/templates/esoft/{any}'
*/
export const firstLevel = (args: { any: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: firstLevel.url(args, options),
    method: 'get',
})

firstLevel.definition = {
    methods: ["get","head"],
    url: '/templates/esoft/{any}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EsoftTemplateController::firstLevel
* @see app/Http/Controllers/EsoftTemplateController.php:16
* @route '/templates/esoft/{any}'
*/
firstLevel.url = (args: { any: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return firstLevel.definition.url
            .replace('{any}', parsedArgs.any.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EsoftTemplateController::firstLevel
* @see app/Http/Controllers/EsoftTemplateController.php:16
* @route '/templates/esoft/{any}'
*/
firstLevel.get = (args: { any: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: firstLevel.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::firstLevel
* @see app/Http/Controllers/EsoftTemplateController.php:16
* @route '/templates/esoft/{any}'
*/
firstLevel.head = (args: { any: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: firstLevel.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::firstLevel
* @see app/Http/Controllers/EsoftTemplateController.php:16
* @route '/templates/esoft/{any}'
*/
const firstLevelForm = (args: { any: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: firstLevel.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::firstLevel
* @see app/Http/Controllers/EsoftTemplateController.php:16
* @route '/templates/esoft/{any}'
*/
firstLevelForm.get = (args: { any: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: firstLevel.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EsoftTemplateController::firstLevel
* @see app/Http/Controllers/EsoftTemplateController.php:16
* @route '/templates/esoft/{any}'
*/
firstLevelForm.head = (args: { any: string | number } | [any: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: firstLevel.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

firstLevel.form = firstLevelForm

const EsoftTemplateController = { root, thirdLevel, secondLevel, firstLevel }

export default EsoftTemplateController