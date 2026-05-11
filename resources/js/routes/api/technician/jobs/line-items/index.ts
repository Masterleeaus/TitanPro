import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Technician\JobController::store
* @see app/Http/Controllers/Technician/JobController.php:183
* @route '/api/technician/jobs/{job}/line-items'
*/
export const store = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/technician/jobs/{job}/line-items',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Technician\JobController::store
* @see app/Http/Controllers/Technician/JobController.php:183
* @route '/api/technician/jobs/{job}/line-items'
*/
store.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { job: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { job: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            job: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        job: typeof args.job === 'object'
        ? args.job.id
        : args.job,
    }

    return store.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Technician\JobController::store
* @see app/Http/Controllers/Technician/JobController.php:183
* @route '/api/technician/jobs/{job}/line-items'
*/
store.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Technician\JobController::store
* @see app/Http/Controllers/Technician/JobController.php:183
* @route '/api/technician/jobs/{job}/line-items'
*/
const storeForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Technician\JobController::store
* @see app/Http/Controllers/Technician/JobController.php:183
* @route '/api/technician/jobs/{job}/line-items'
*/
storeForm.post = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Technician\JobController::update
* @see app/Http/Controllers/Technician/JobController.php:209
* @route '/api/technician/jobs/{job}/line-items/{lineItem}'
*/
export const update = (args: { job: number | { id: number }, lineItem: number | { id: number } } | [job: number | { id: number }, lineItem: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/api/technician/jobs/{job}/line-items/{lineItem}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Technician\JobController::update
* @see app/Http/Controllers/Technician/JobController.php:209
* @route '/api/technician/jobs/{job}/line-items/{lineItem}'
*/
update.url = (args: { job: number | { id: number }, lineItem: number | { id: number } } | [job: number | { id: number }, lineItem: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            job: args[0],
            lineItem: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        job: typeof args.job === 'object'
        ? args.job.id
        : args.job,
        lineItem: typeof args.lineItem === 'object'
        ? args.lineItem.id
        : args.lineItem,
    }

    return update.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace('{lineItem}', parsedArgs.lineItem.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Technician\JobController::update
* @see app/Http/Controllers/Technician/JobController.php:209
* @route '/api/technician/jobs/{job}/line-items/{lineItem}'
*/
update.patch = (args: { job: number | { id: number }, lineItem: number | { id: number } } | [job: number | { id: number }, lineItem: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Technician\JobController::update
* @see app/Http/Controllers/Technician/JobController.php:209
* @route '/api/technician/jobs/{job}/line-items/{lineItem}'
*/
const updateForm = (args: { job: number | { id: number }, lineItem: number | { id: number } } | [job: number | { id: number }, lineItem: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Technician\JobController::update
* @see app/Http/Controllers/Technician/JobController.php:209
* @route '/api/technician/jobs/{job}/line-items/{lineItem}'
*/
updateForm.patch = (args: { job: number | { id: number }, lineItem: number | { id: number } } | [job: number | { id: number }, lineItem: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\Technician\JobController::destroy
* @see app/Http/Controllers/Technician/JobController.php:225
* @route '/api/technician/jobs/{job}/line-items/{lineItem}'
*/
export const destroy = (args: { job: number | { id: number }, lineItem: number | { id: number } } | [job: number | { id: number }, lineItem: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/api/technician/jobs/{job}/line-items/{lineItem}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Technician\JobController::destroy
* @see app/Http/Controllers/Technician/JobController.php:225
* @route '/api/technician/jobs/{job}/line-items/{lineItem}'
*/
destroy.url = (args: { job: number | { id: number }, lineItem: number | { id: number } } | [job: number | { id: number }, lineItem: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            job: args[0],
            lineItem: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        job: typeof args.job === 'object'
        ? args.job.id
        : args.job,
        lineItem: typeof args.lineItem === 'object'
        ? args.lineItem.id
        : args.lineItem,
    }

    return destroy.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace('{lineItem}', parsedArgs.lineItem.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Technician\JobController::destroy
* @see app/Http/Controllers/Technician/JobController.php:225
* @route '/api/technician/jobs/{job}/line-items/{lineItem}'
*/
destroy.delete = (args: { job: number | { id: number }, lineItem: number | { id: number } } | [job: number | { id: number }, lineItem: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Technician\JobController::destroy
* @see app/Http/Controllers/Technician/JobController.php:225
* @route '/api/technician/jobs/{job}/line-items/{lineItem}'
*/
const destroyForm = (args: { job: number | { id: number }, lineItem: number | { id: number } } | [job: number | { id: number }, lineItem: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Technician\JobController::destroy
* @see app/Http/Controllers/Technician/JobController.php:225
* @route '/api/technician/jobs/{job}/line-items/{lineItem}'
*/
destroyForm.delete = (args: { job: number | { id: number }, lineItem: number | { id: number } } | [job: number | { id: number }, lineItem: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const lineItems = {
    store: Object.assign(store, store),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default lineItems