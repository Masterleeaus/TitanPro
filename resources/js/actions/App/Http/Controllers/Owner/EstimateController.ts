import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Owner\EstimateController::index
* @see app/Http/Controllers/Owner/EstimateController.php:22
* @route '/owner/estimates'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/owner/estimates',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\EstimateController::index
* @see app/Http/Controllers/Owner/EstimateController.php:22
* @route '/owner/estimates'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\EstimateController::index
* @see app/Http/Controllers/Owner/EstimateController.php:22
* @route '/owner/estimates'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::index
* @see app/Http/Controllers/Owner/EstimateController.php:22
* @route '/owner/estimates'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::index
* @see app/Http/Controllers/Owner/EstimateController.php:22
* @route '/owner/estimates'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::index
* @see app/Http/Controllers/Owner/EstimateController.php:22
* @route '/owner/estimates'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::index
* @see app/Http/Controllers/Owner/EstimateController.php:22
* @route '/owner/estimates'
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
* @see \App\Http\Controllers\Owner\EstimateController::create
* @see app/Http/Controllers/Owner/EstimateController.php:66
* @route '/owner/estimates/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/owner/estimates/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\EstimateController::create
* @see app/Http/Controllers/Owner/EstimateController.php:66
* @route '/owner/estimates/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\EstimateController::create
* @see app/Http/Controllers/Owner/EstimateController.php:66
* @route '/owner/estimates/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::create
* @see app/Http/Controllers/Owner/EstimateController.php:66
* @route '/owner/estimates/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::create
* @see app/Http/Controllers/Owner/EstimateController.php:66
* @route '/owner/estimates/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::create
* @see app/Http/Controllers/Owner/EstimateController.php:66
* @route '/owner/estimates/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::create
* @see app/Http/Controllers/Owner/EstimateController.php:66
* @route '/owner/estimates/create'
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
* @see \App\Http\Controllers\Owner\EstimateController::store
* @see app/Http/Controllers/Owner/EstimateController.php:87
* @route '/owner/estimates'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/owner/estimates',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Owner\EstimateController::store
* @see app/Http/Controllers/Owner/EstimateController.php:87
* @route '/owner/estimates'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\EstimateController::store
* @see app/Http/Controllers/Owner/EstimateController.php:87
* @route '/owner/estimates'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::store
* @see app/Http/Controllers/Owner/EstimateController.php:87
* @route '/owner/estimates'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::store
* @see app/Http/Controllers/Owner/EstimateController.php:87
* @route '/owner/estimates'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Owner\EstimateController::show
* @see app/Http/Controllers/Owner/EstimateController.php:52
* @route '/owner/estimates/{estimate}'
*/
export const show = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/owner/estimates/{estimate}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\EstimateController::show
* @see app/Http/Controllers/Owner/EstimateController.php:52
* @route '/owner/estimates/{estimate}'
*/
show.url = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { estimate: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { estimate: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            estimate: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        estimate: typeof args.estimate === 'object'
        ? args.estimate.id
        : args.estimate,
    }

    return show.definition.url
            .replace('{estimate}', parsedArgs.estimate.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\EstimateController::show
* @see app/Http/Controllers/Owner/EstimateController.php:52
* @route '/owner/estimates/{estimate}'
*/
show.get = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::show
* @see app/Http/Controllers/Owner/EstimateController.php:52
* @route '/owner/estimates/{estimate}'
*/
show.head = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::show
* @see app/Http/Controllers/Owner/EstimateController.php:52
* @route '/owner/estimates/{estimate}'
*/
const showForm = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::show
* @see app/Http/Controllers/Owner/EstimateController.php:52
* @route '/owner/estimates/{estimate}'
*/
showForm.get = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::show
* @see app/Http/Controllers/Owner/EstimateController.php:52
* @route '/owner/estimates/{estimate}'
*/
showForm.head = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Owner\EstimateController::edit
* @see app/Http/Controllers/Owner/EstimateController.php:133
* @route '/owner/estimates/{estimate}/edit'
*/
export const edit = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/owner/estimates/{estimate}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\EstimateController::edit
* @see app/Http/Controllers/Owner/EstimateController.php:133
* @route '/owner/estimates/{estimate}/edit'
*/
edit.url = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { estimate: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { estimate: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            estimate: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        estimate: typeof args.estimate === 'object'
        ? args.estimate.id
        : args.estimate,
    }

    return edit.definition.url
            .replace('{estimate}', parsedArgs.estimate.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\EstimateController::edit
* @see app/Http/Controllers/Owner/EstimateController.php:133
* @route '/owner/estimates/{estimate}/edit'
*/
edit.get = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::edit
* @see app/Http/Controllers/Owner/EstimateController.php:133
* @route '/owner/estimates/{estimate}/edit'
*/
edit.head = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::edit
* @see app/Http/Controllers/Owner/EstimateController.php:133
* @route '/owner/estimates/{estimate}/edit'
*/
const editForm = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::edit
* @see app/Http/Controllers/Owner/EstimateController.php:133
* @route '/owner/estimates/{estimate}/edit'
*/
editForm.get = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::edit
* @see app/Http/Controllers/Owner/EstimateController.php:133
* @route '/owner/estimates/{estimate}/edit'
*/
editForm.head = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Owner\EstimateController::update
* @see app/Http/Controllers/Owner/EstimateController.php:158
* @route '/owner/estimates/{estimate}'
*/
export const update = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/owner/estimates/{estimate}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Owner\EstimateController::update
* @see app/Http/Controllers/Owner/EstimateController.php:158
* @route '/owner/estimates/{estimate}'
*/
update.url = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { estimate: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { estimate: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            estimate: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        estimate: typeof args.estimate === 'object'
        ? args.estimate.id
        : args.estimate,
    }

    return update.definition.url
            .replace('{estimate}', parsedArgs.estimate.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\EstimateController::update
* @see app/Http/Controllers/Owner/EstimateController.php:158
* @route '/owner/estimates/{estimate}'
*/
update.put = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::update
* @see app/Http/Controllers/Owner/EstimateController.php:158
* @route '/owner/estimates/{estimate}'
*/
update.patch = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::update
* @see app/Http/Controllers/Owner/EstimateController.php:158
* @route '/owner/estimates/{estimate}'
*/
const updateForm = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::update
* @see app/Http/Controllers/Owner/EstimateController.php:158
* @route '/owner/estimates/{estimate}'
*/
updateForm.put = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::update
* @see app/Http/Controllers/Owner/EstimateController.php:158
* @route '/owner/estimates/{estimate}'
*/
updateForm.patch = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Owner\EstimateController::destroy
* @see app/Http/Controllers/Owner/EstimateController.php:261
* @route '/owner/estimates/{estimate}'
*/
export const destroy = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/owner/estimates/{estimate}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Owner\EstimateController::destroy
* @see app/Http/Controllers/Owner/EstimateController.php:261
* @route '/owner/estimates/{estimate}'
*/
destroy.url = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { estimate: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { estimate: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            estimate: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        estimate: typeof args.estimate === 'object'
        ? args.estimate.id
        : args.estimate,
    }

    return destroy.definition.url
            .replace('{estimate}', parsedArgs.estimate.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\EstimateController::destroy
* @see app/Http/Controllers/Owner/EstimateController.php:261
* @route '/owner/estimates/{estimate}'
*/
destroy.delete = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::destroy
* @see app/Http/Controllers/Owner/EstimateController.php:261
* @route '/owner/estimates/{estimate}'
*/
const destroyForm = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::destroy
* @see app/Http/Controllers/Owner/EstimateController.php:261
* @route '/owner/estimates/{estimate}'
*/
destroyForm.delete = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Owner\EstimateController::send
* @see app/Http/Controllers/Owner/EstimateController.php:203
* @route '/owner/estimates/{estimate}/send'
*/
export const send = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: send.url(args, options),
    method: 'post',
})

send.definition = {
    methods: ["post"],
    url: '/owner/estimates/{estimate}/send',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Owner\EstimateController::send
* @see app/Http/Controllers/Owner/EstimateController.php:203
* @route '/owner/estimates/{estimate}/send'
*/
send.url = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { estimate: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { estimate: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            estimate: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        estimate: typeof args.estimate === 'object'
        ? args.estimate.id
        : args.estimate,
    }

    return send.definition.url
            .replace('{estimate}', parsedArgs.estimate.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\EstimateController::send
* @see app/Http/Controllers/Owner/EstimateController.php:203
* @route '/owner/estimates/{estimate}/send'
*/
send.post = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: send.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::send
* @see app/Http/Controllers/Owner/EstimateController.php:203
* @route '/owner/estimates/{estimate}/send'
*/
const sendForm = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: send.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::send
* @see app/Http/Controllers/Owner/EstimateController.php:203
* @route '/owner/estimates/{estimate}/send'
*/
sendForm.post = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: send.url(args, options),
    method: 'post',
})

send.form = sendForm

/**
* @see \App\Http\Controllers\Owner\EstimateController::convertToJob
* @see app/Http/Controllers/Owner/EstimateController.php:220
* @route '/owner/estimates/{estimate}/convert'
*/
export const convertToJob = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: convertToJob.url(args, options),
    method: 'post',
})

convertToJob.definition = {
    methods: ["post"],
    url: '/owner/estimates/{estimate}/convert',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Owner\EstimateController::convertToJob
* @see app/Http/Controllers/Owner/EstimateController.php:220
* @route '/owner/estimates/{estimate}/convert'
*/
convertToJob.url = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { estimate: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { estimate: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            estimate: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        estimate: typeof args.estimate === 'object'
        ? args.estimate.id
        : args.estimate,
    }

    return convertToJob.definition.url
            .replace('{estimate}', parsedArgs.estimate.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\EstimateController::convertToJob
* @see app/Http/Controllers/Owner/EstimateController.php:220
* @route '/owner/estimates/{estimate}/convert'
*/
convertToJob.post = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: convertToJob.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::convertToJob
* @see app/Http/Controllers/Owner/EstimateController.php:220
* @route '/owner/estimates/{estimate}/convert'
*/
const convertToJobForm = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: convertToJob.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\EstimateController::convertToJob
* @see app/Http/Controllers/Owner/EstimateController.php:220
* @route '/owner/estimates/{estimate}/convert'
*/
convertToJobForm.post = (args: { estimate: number | { id: number } } | [estimate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: convertToJob.url(args, options),
    method: 'post',
})

convertToJob.form = convertToJobForm

const EstimateController = { index, create, store, show, edit, update, destroy, send, convertToJob }

export default EstimateController