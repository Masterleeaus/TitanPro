import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
import checklist from './checklist'
import photos from './photos'
import lineItems from './line-items'
/**
* @see \App\Http\Controllers\Technician\JobController::today
* @see app/Http/Controllers/Technician/JobController.php:54
* @route '/api/technician/jobs/today'
*/
export const today = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: today.url(options),
    method: 'get',
})

today.definition = {
    methods: ["get","head"],
    url: '/api/technician/jobs/today',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Technician\JobController::today
* @see app/Http/Controllers/Technician/JobController.php:54
* @route '/api/technician/jobs/today'
*/
today.url = (options?: RouteQueryOptions) => {
    return today.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Technician\JobController::today
* @see app/Http/Controllers/Technician/JobController.php:54
* @route '/api/technician/jobs/today'
*/
today.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: today.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Technician\JobController::today
* @see app/Http/Controllers/Technician/JobController.php:54
* @route '/api/technician/jobs/today'
*/
today.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: today.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Technician\JobController::today
* @see app/Http/Controllers/Technician/JobController.php:54
* @route '/api/technician/jobs/today'
*/
const todayForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: today.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Technician\JobController::today
* @see app/Http/Controllers/Technician/JobController.php:54
* @route '/api/technician/jobs/today'
*/
todayForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: today.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Technician\JobController::today
* @see app/Http/Controllers/Technician/JobController.php:54
* @route '/api/technician/jobs/today'
*/
todayForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: today.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

today.form = todayForm

/**
* @see \App\Http\Controllers\Technician\JobController::show
* @see app/Http/Controllers/Technician/JobController.php:64
* @route '/api/technician/jobs/{job}'
*/
export const show = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/technician/jobs/{job}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Technician\JobController::show
* @see app/Http/Controllers/Technician/JobController.php:64
* @route '/api/technician/jobs/{job}'
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
* @see \App\Http\Controllers\Technician\JobController::show
* @see app/Http/Controllers/Technician/JobController.php:64
* @route '/api/technician/jobs/{job}'
*/
show.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Technician\JobController::show
* @see app/Http/Controllers/Technician/JobController.php:64
* @route '/api/technician/jobs/{job}'
*/
show.head = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Technician\JobController::show
* @see app/Http/Controllers/Technician/JobController.php:64
* @route '/api/technician/jobs/{job}'
*/
const showForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Technician\JobController::show
* @see app/Http/Controllers/Technician/JobController.php:64
* @route '/api/technician/jobs/{job}'
*/
showForm.get = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Technician\JobController::show
* @see app/Http/Controllers/Technician/JobController.php:64
* @route '/api/technician/jobs/{job}'
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
* @see \App\Http\Controllers\Technician\JobController::status
* @see app/Http/Controllers/Technician/JobController.php:73
* @route '/api/technician/jobs/{job}/status'
*/
export const status = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: status.url(args, options),
    method: 'patch',
})

status.definition = {
    methods: ["patch"],
    url: '/api/technician/jobs/{job}/status',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Technician\JobController::status
* @see app/Http/Controllers/Technician/JobController.php:73
* @route '/api/technician/jobs/{job}/status'
*/
status.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return status.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Technician\JobController::status
* @see app/Http/Controllers/Technician/JobController.php:73
* @route '/api/technician/jobs/{job}/status'
*/
status.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: status.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Technician\JobController::status
* @see app/Http/Controllers/Technician/JobController.php:73
* @route '/api/technician/jobs/{job}/status'
*/
const statusForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: status.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Technician\JobController::status
* @see app/Http/Controllers/Technician/JobController.php:73
* @route '/api/technician/jobs/{job}/status'
*/
statusForm.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: status.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

status.form = statusForm

/**
* @see \App\Http\Controllers\Technician\JobController::notes
* @see app/Http/Controllers/Technician/JobController.php:103
* @route '/api/technician/jobs/{job}/notes'
*/
export const notes = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: notes.url(args, options),
    method: 'patch',
})

notes.definition = {
    methods: ["patch"],
    url: '/api/technician/jobs/{job}/notes',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Technician\JobController::notes
* @see app/Http/Controllers/Technician/JobController.php:103
* @route '/api/technician/jobs/{job}/notes'
*/
notes.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return notes.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Technician\JobController::notes
* @see app/Http/Controllers/Technician/JobController.php:103
* @route '/api/technician/jobs/{job}/notes'
*/
notes.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: notes.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Technician\JobController::notes
* @see app/Http/Controllers/Technician/JobController.php:103
* @route '/api/technician/jobs/{job}/notes'
*/
const notesForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: notes.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Technician\JobController::notes
* @see app/Http/Controllers/Technician/JobController.php:103
* @route '/api/technician/jobs/{job}/notes'
*/
notesForm.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: notes.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

notes.form = notesForm

/**
* @see \App\Http\Controllers\Technician\JobController::customerNotes
* @see app/Http/Controllers/Technician/JobController.php:116
* @route '/api/technician/jobs/{job}/customer-notes'
*/
export const customerNotes = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: customerNotes.url(args, options),
    method: 'patch',
})

customerNotes.definition = {
    methods: ["patch"],
    url: '/api/technician/jobs/{job}/customer-notes',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Technician\JobController::customerNotes
* @see app/Http/Controllers/Technician/JobController.php:116
* @route '/api/technician/jobs/{job}/customer-notes'
*/
customerNotes.url = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return customerNotes.definition.url
            .replace('{job}', parsedArgs.job.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Technician\JobController::customerNotes
* @see app/Http/Controllers/Technician/JobController.php:116
* @route '/api/technician/jobs/{job}/customer-notes'
*/
customerNotes.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: customerNotes.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Technician\JobController::customerNotes
* @see app/Http/Controllers/Technician/JobController.php:116
* @route '/api/technician/jobs/{job}/customer-notes'
*/
const customerNotesForm = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: customerNotes.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Technician\JobController::customerNotes
* @see app/Http/Controllers/Technician/JobController.php:116
* @route '/api/technician/jobs/{job}/customer-notes'
*/
customerNotesForm.patch = (args: { job: number | { id: number } } | [job: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: customerNotes.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

customerNotes.form = customerNotesForm

const jobs = {
    today: Object.assign(today, today),
    show: Object.assign(show, show),
    status: Object.assign(status, status),
    notes: Object.assign(notes, notes),
    customerNotes: Object.assign(customerNotes, customerNotes),
    checklist: Object.assign(checklist, checklist),
    photos: Object.assign(photos, photos),
    lineItems: Object.assign(lineItems, lineItems),
}

export default jobs