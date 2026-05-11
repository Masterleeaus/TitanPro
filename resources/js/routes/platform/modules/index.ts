import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Platform\ModuleAdminDashboardController::index
* @see app/Http/Controllers/Platform/ModuleAdminDashboardController.php:15
* @route '/platform/modules'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/platform/modules',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Platform\ModuleAdminDashboardController::index
* @see app/Http/Controllers/Platform/ModuleAdminDashboardController.php:15
* @route '/platform/modules'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Platform\ModuleAdminDashboardController::index
* @see app/Http/Controllers/Platform/ModuleAdminDashboardController.php:15
* @route '/platform/modules'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\ModuleAdminDashboardController::index
* @see app/Http/Controllers/Platform/ModuleAdminDashboardController.php:15
* @route '/platform/modules'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Platform\ModuleAdminDashboardController::index
* @see app/Http/Controllers/Platform/ModuleAdminDashboardController.php:15
* @route '/platform/modules'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\ModuleAdminDashboardController::index
* @see app/Http/Controllers/Platform/ModuleAdminDashboardController.php:15
* @route '/platform/modules'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\ModuleAdminDashboardController::index
* @see app/Http/Controllers/Platform/ModuleAdminDashboardController.php:15
* @route '/platform/modules'
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
* @see \App\Http\Controllers\Platform\ModuleAuditLogController::auditLog
* @see app/Http/Controllers/Platform/ModuleAuditLogController.php:12
* @route '/platform/modules/audit-log'
*/
export const auditLog = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: auditLog.url(options),
    method: 'get',
})

auditLog.definition = {
    methods: ["get","head"],
    url: '/platform/modules/audit-log',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Platform\ModuleAuditLogController::auditLog
* @see app/Http/Controllers/Platform/ModuleAuditLogController.php:12
* @route '/platform/modules/audit-log'
*/
auditLog.url = (options?: RouteQueryOptions) => {
    return auditLog.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Platform\ModuleAuditLogController::auditLog
* @see app/Http/Controllers/Platform/ModuleAuditLogController.php:12
* @route '/platform/modules/audit-log'
*/
auditLog.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: auditLog.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\ModuleAuditLogController::auditLog
* @see app/Http/Controllers/Platform/ModuleAuditLogController.php:12
* @route '/platform/modules/audit-log'
*/
auditLog.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: auditLog.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Platform\ModuleAuditLogController::auditLog
* @see app/Http/Controllers/Platform/ModuleAuditLogController.php:12
* @route '/platform/modules/audit-log'
*/
const auditLogForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: auditLog.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\ModuleAuditLogController::auditLog
* @see app/Http/Controllers/Platform/ModuleAuditLogController.php:12
* @route '/platform/modules/audit-log'
*/
auditLogForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: auditLog.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Platform\ModuleAuditLogController::auditLog
* @see app/Http/Controllers/Platform/ModuleAuditLogController.php:12
* @route '/platform/modules/audit-log'
*/
auditLogForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: auditLog.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

auditLog.form = auditLogForm

const modules = {
    index: Object.assign(index, index),
    auditLog: Object.assign(auditLog, auditLog),
}

export default modules