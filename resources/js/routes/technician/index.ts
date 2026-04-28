import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import jobs from './jobs'
/**
* @see \App\Http\Controllers\Technician\DashboardController::dashboard
* @see app/Http/Controllers/Technician/DashboardController.php:13
* @route '/technician/dashboard'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/technician/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Technician\DashboardController::dashboard
* @see app/Http/Controllers/Technician/DashboardController.php:13
* @route '/technician/dashboard'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Technician\DashboardController::dashboard
* @see app/Http/Controllers/Technician/DashboardController.php:13
* @route '/technician/dashboard'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Technician\DashboardController::dashboard
* @see app/Http/Controllers/Technician/DashboardController.php:13
* @route '/technician/dashboard'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Technician\DashboardController::dashboard
* @see app/Http/Controllers/Technician/DashboardController.php:13
* @route '/technician/dashboard'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Technician\DashboardController::dashboard
* @see app/Http/Controllers/Technician/DashboardController.php:13
* @route '/technician/dashboard'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Technician\DashboardController::dashboard
* @see app/Http/Controllers/Technician/DashboardController.php:13
* @route '/technician/dashboard'
*/
dashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

dashboard.form = dashboardForm

const technician = {
    dashboard: Object.assign(dashboard, dashboard),
    jobs: Object.assign(jobs, jobs),
}

export default technician