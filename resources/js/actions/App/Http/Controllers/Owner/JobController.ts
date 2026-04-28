import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Owner\JobController::index
* @see app/Http/Controllers/Owner/JobController.php:23
* @route '/owner/jobs'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/owner/jobs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\JobController::index
* @see app/Http/Controllers/Owner/JobController.php:23
* @route '/owner/jobs'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\JobController::index
* @see app/Http/Controllers/Owner/JobController.php:23
* @route '/owner/jobs'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\JobController::index
* @see app/Http/Controllers/Owner/JobController.php:23
* @route '/owner/jobs'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\JobController::index
* @see app/Http/Controllers/Owner/JobController.php:23
* @route '/owner/jobs'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\JobController::index
* @see app/Http/Controllers/Owner/JobController.php:23
* @route '/owner/jobs'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\JobController::index
* @see app/Http/Controllers/Owner/JobController.php:23
* @route '/owner/jobs'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\Owner\JobController::create
* @see app/Http/Controllers/Owner/JobController.php:67
* @route '/owner/jobs/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/owner/jobs/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\JobController::create
* @see app/Http/Controllers/Owner/JobController.php:67
* @route '/owner/jobs/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\JobController::create
* @see app/Http/Controllers/Owner/JobController.php:67
* @route '/owner/jobs/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\JobController::create
* @see app/Http/Controllers/Owner/JobController.php:67
* @route '/owner/jobs/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\JobController::create
* @see app/Http/Controllers/Owner/JobController.php:67
* @route '/owner/jobs/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\JobController::create
* @see app/Http/Controllers/Owner/JobController.php:67
* @route '/owner/jobs/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\JobController::create
* @see app/Http/Controllers/Owner/JobController.php:67
* @route '/owner/jobs/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\Owner\JobController::store
* @see app/Http/Controllers/Owner/JobController.php:88
* @route '/owner/jobs'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/owner/jobs',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Owner\JobController::store
* @see app/Http/Controllers/Owner/JobController.php:88
* @route '/owner/jobs'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\JobController::store
* @see app/Http/Controllers/Owner/JobController.php:88
* @route '/owner/jobs'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\JobController::store
* @see app/Http/Controllers/Owner/JobController.php:88
* @route '/owner/jobs'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\JobController::store
* @see app/Http/Controllers/Owner/JobController.php:88
* @route '/owner/jobs'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Owner\JobController::show
* @see app/Http/Controllers/Owner/JobController.php:55
* @route '/owner/jobs/{job}'
*/
export const show = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/owner/jobs/{job}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\JobController::show
* @see app/Http/Controllers/Owner/JobController.php:55
* @route '/owner/jobs/{job}'
*/
show.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\JobController::show
* @see app/Http/Controllers/Owner/JobController.php:55
* @route '/owner/jobs/{job}'
*/
show.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\JobController::show
* @see app/Http/Controllers/Owner/JobController.php:55
* @route '/owner/jobs/{job}'
*/
show.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\JobController::show
* @see app/Http/Controllers/Owner/JobController.php:55
* @route '/owner/jobs/{job}'
*/
const showForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\JobController::show
* @see app/Http/Controllers/Owner/JobController.php:55
* @route '/owner/jobs/{job}'
*/
showForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\JobController::show
* @see app/Http/Controllers/Owner/JobController.php:55
* @route '/owner/jobs/{job}'
*/
showForm.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\Owner\JobController::edit
* @see app/Http/Controllers/Owner/JobController.php:102
* @route '/owner/jobs/{job}/edit'
*/
export const edit = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/owner/jobs/{job}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\JobController::edit
* @see app/Http/Controllers/Owner/JobController.php:102
* @route '/owner/jobs/{job}/edit'
*/
edit.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\JobController::edit
* @see app/Http/Controllers/Owner/JobController.php:102
* @route '/owner/jobs/{job}/edit'
*/
edit.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\JobController::edit
* @see app/Http/Controllers/Owner/JobController.php:102
* @route '/owner/jobs/{job}/edit'
*/
edit.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\JobController::edit
* @see app/Http/Controllers/Owner/JobController.php:102
* @route '/owner/jobs/{job}/edit'
*/
const editForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\JobController::edit
* @see app/Http/Controllers/Owner/JobController.php:102
* @route '/owner/jobs/{job}/edit'
*/
editForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\JobController::edit
* @see app/Http/Controllers/Owner/JobController.php:102
* @route '/owner/jobs/{job}/edit'
*/
editForm.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\Owner\JobController::update
* @see app/Http/Controllers/Owner/JobController.php:125
* @route '/owner/jobs/{job}'
*/
export const update = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/owner/jobs/{job}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Owner\JobController::update
* @see app/Http/Controllers/Owner/JobController.php:125
* @route '/owner/jobs/{job}'
*/
update.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\JobController::update
* @see app/Http/Controllers/Owner/JobController.php:125
* @route '/owner/jobs/{job}'
*/
update.put = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Owner\JobController::update
* @see app/Http/Controllers/Owner/JobController.php:125
* @route '/owner/jobs/{job}'
*/
update.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Owner\JobController::update
* @see app/Http/Controllers/Owner/JobController.php:125
* @route '/owner/jobs/{job}'
*/
const updateForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\JobController::update
* @see app/Http/Controllers/Owner/JobController.php:125
* @route '/owner/jobs/{job}'
*/
updateForm.put = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\JobController::update
* @see app/Http/Controllers/Owner/JobController.php:125
* @route '/owner/jobs/{job}'
*/
updateForm.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Owner\JobController::destroy
* @see app/Http/Controllers/Owner/JobController.php:135
* @route '/owner/jobs/{job}'
*/
export const destroy = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/owner/jobs/{job}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Owner\JobController::destroy
* @see app/Http/Controllers/Owner/JobController.php:135
* @route '/owner/jobs/{job}'
*/
destroy.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\JobController::destroy
* @see app/Http/Controllers/Owner/JobController.php:135
* @route '/owner/jobs/{job}'
*/
destroy.delete = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Owner\JobController::destroy
* @see app/Http/Controllers/Owner/JobController.php:135
* @route '/owner/jobs/{job}'
*/
const destroyForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\JobController::destroy
* @see app/Http/Controllers/Owner/JobController.php:135
* @route '/owner/jobs/{job}'
*/
destroyForm.delete = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

/**
* @see \App\Http\Controllers\Owner\JobController::updateStatus
* @see app/Http/Controllers/Owner/JobController.php:145
* @route '/owner/jobs/{job}/status'
*/
export const updateStatus = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateStatus.url(args, options),
    method: 'patch',
})

updateStatus.definition = {
    methods: ["patch"],
    url: '/owner/jobs/{job}/status',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Owner\JobController::updateStatus
* @see app/Http/Controllers/Owner/JobController.php:145
* @route '/owner/jobs/{job}/status'
*/
updateStatus.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return updateStatus.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\JobController::updateStatus
* @see app/Http/Controllers/Owner/JobController.php:145
* @route '/owner/jobs/{job}/status'
*/
updateStatus.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateStatus.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Owner\JobController::updateStatus
* @see app/Http/Controllers/Owner/JobController.php:145
* @route '/owner/jobs/{job}/status'
*/
const updateStatusForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updateStatus.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\JobController::updateStatus
* @see app/Http/Controllers/Owner/JobController.php:145
* @route '/owner/jobs/{job}/status'
*/
updateStatusForm.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updateStatus.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

updateStatus.form = updateStatusForm

/**
* @see \App\Http\Controllers\Owner\JobController::reschedule
* @see app/Http/Controllers/Owner/JobController.php:168
* @route '/owner/jobs/{job}/reschedule'
*/
export const reschedule = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: reschedule.url(args, options),
    method: 'patch',
})

reschedule.definition = {
    methods: ["patch"],
    url: '/owner/jobs/{job}/reschedule',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Owner\JobController::reschedule
* @see app/Http/Controllers/Owner/JobController.php:168
* @route '/owner/jobs/{job}/reschedule'
*/
reschedule.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return reschedule.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\JobController::reschedule
* @see app/Http/Controllers/Owner/JobController.php:168
* @route '/owner/jobs/{job}/reschedule'
*/
reschedule.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: reschedule.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Owner\JobController::reschedule
* @see app/Http/Controllers/Owner/JobController.php:168
* @route '/owner/jobs/{job}/reschedule'
*/
const rescheduleForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: reschedule.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\JobController::reschedule
* @see app/Http/Controllers/Owner/JobController.php:168
* @route '/owner/jobs/{job}/reschedule'
*/
rescheduleForm.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: reschedule.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

reschedule.form = rescheduleForm

/**
* @see \App\Http\Controllers\Owner\JobController::reassign
* @see app/Http/Controllers/Owner/JobController.php:181
* @route '/owner/jobs/{job}/reassign'
*/
export const reassign = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: reassign.url(args, options),
    method: 'patch',
})

reassign.definition = {
    methods: ["patch"],
    url: '/owner/jobs/{job}/reassign',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Owner\JobController::reassign
* @see app/Http/Controllers/Owner/JobController.php:181
* @route '/owner/jobs/{job}/reassign'
*/
reassign.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return reassign.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\JobController::reassign
* @see app/Http/Controllers/Owner/JobController.php:181
* @route '/owner/jobs/{job}/reassign'
*/
reassign.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: reassign.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Owner\JobController::reassign
* @see app/Http/Controllers/Owner/JobController.php:181
* @route '/owner/jobs/{job}/reassign'
*/
const reassignForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: reassign.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\JobController::reassign
* @see app/Http/Controllers/Owner/JobController.php:181
* @route '/owner/jobs/{job}/reassign'
*/
reassignForm.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: reassign.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

reassign.form = reassignForm

const JobController = { index, create, store, show, edit, update, destroy, updateStatus, reschedule, reassign }

export default JobController