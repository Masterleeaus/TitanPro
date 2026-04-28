import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Owner\SettingsController::company
* @see app/Http/Controllers/Owner/SettingsController.php:24
* @route '/owner/settings/company'
*/
export const company = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: company.url(options),
    method: 'get',
})

company.definition = {
    methods: ["get","head"],
    url: '/owner/settings/company',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\SettingsController::company
* @see app/Http/Controllers/Owner/SettingsController.php:24
* @route '/owner/settings/company'
*/
company.url = (options?: RouteQueryOptions) => {
    return company.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\SettingsController::company
* @see app/Http/Controllers/Owner/SettingsController.php:24
* @route '/owner/settings/company'
*/
company.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: company.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\SettingsController::company
* @see app/Http/Controllers/Owner/SettingsController.php:24
* @route '/owner/settings/company'
*/
company.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: company.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\SettingsController::company
* @see app/Http/Controllers/Owner/SettingsController.php:24
* @route '/owner/settings/company'
*/
const companyForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: company.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\SettingsController::company
* @see app/Http/Controllers/Owner/SettingsController.php:24
* @route '/owner/settings/company'
*/
companyForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: company.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\SettingsController::company
* @see app/Http/Controllers/Owner/SettingsController.php:24
* @route '/owner/settings/company'
*/
companyForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: company.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

company.form = companyForm

/**
* @see \App\Http\Controllers\Owner\SettingsController::updateCompany
* @see app/Http/Controllers/Owner/SettingsController.php:44
* @route '/owner/settings/company'
*/
export const updateCompany = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateCompany.url(options),
    method: 'post',
})

updateCompany.definition = {
    methods: ["post"],
    url: '/owner/settings/company',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Owner\SettingsController::updateCompany
* @see app/Http/Controllers/Owner/SettingsController.php:44
* @route '/owner/settings/company'
*/
updateCompany.url = (options?: RouteQueryOptions) => {
    return updateCompany.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\SettingsController::updateCompany
* @see app/Http/Controllers/Owner/SettingsController.php:44
* @route '/owner/settings/company'
*/
updateCompany.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateCompany.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\SettingsController::updateCompany
* @see app/Http/Controllers/Owner/SettingsController.php:44
* @route '/owner/settings/company'
*/
const updateCompanyForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updateCompany.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\SettingsController::updateCompany
* @see app/Http/Controllers/Owner/SettingsController.php:44
* @route '/owner/settings/company'
*/
updateCompanyForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updateCompany.url(options),
    method: 'post',
})

updateCompany.form = updateCompanyForm

/**
* @see \App\Http\Controllers\Owner\SettingsController::integrations
* @see app/Http/Controllers/Owner/SettingsController.php:83
* @route '/owner/settings/integrations'
*/
export const integrations = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: integrations.url(options),
    method: 'get',
})

integrations.definition = {
    methods: ["get","head"],
    url: '/owner/settings/integrations',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Owner\SettingsController::integrations
* @see app/Http/Controllers/Owner/SettingsController.php:83
* @route '/owner/settings/integrations'
*/
integrations.url = (options?: RouteQueryOptions) => {
    return integrations.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\SettingsController::integrations
* @see app/Http/Controllers/Owner/SettingsController.php:83
* @route '/owner/settings/integrations'
*/
integrations.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: integrations.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\SettingsController::integrations
* @see app/Http/Controllers/Owner/SettingsController.php:83
* @route '/owner/settings/integrations'
*/
integrations.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: integrations.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Owner\SettingsController::integrations
* @see app/Http/Controllers/Owner/SettingsController.php:83
* @route '/owner/settings/integrations'
*/
const integrationsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: integrations.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\SettingsController::integrations
* @see app/Http/Controllers/Owner/SettingsController.php:83
* @route '/owner/settings/integrations'
*/
integrationsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: integrations.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Owner\SettingsController::integrations
* @see app/Http/Controllers/Owner/SettingsController.php:83
* @route '/owner/settings/integrations'
*/
integrationsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: integrations.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

integrations.form = integrationsForm

/**
* @see \App\Http\Controllers\Owner\SettingsController::updateIntegrations
* @see app/Http/Controllers/Owner/SettingsController.php:106
* @route '/owner/settings/integrations'
*/
export const updateIntegrations = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateIntegrations.url(options),
    method: 'post',
})

updateIntegrations.definition = {
    methods: ["post"],
    url: '/owner/settings/integrations',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Owner\SettingsController::updateIntegrations
* @see app/Http/Controllers/Owner/SettingsController.php:106
* @route '/owner/settings/integrations'
*/
updateIntegrations.url = (options?: RouteQueryOptions) => {
    return updateIntegrations.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\SettingsController::updateIntegrations
* @see app/Http/Controllers/Owner/SettingsController.php:106
* @route '/owner/settings/integrations'
*/
updateIntegrations.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateIntegrations.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\SettingsController::updateIntegrations
* @see app/Http/Controllers/Owner/SettingsController.php:106
* @route '/owner/settings/integrations'
*/
const updateIntegrationsForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updateIntegrations.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\SettingsController::updateIntegrations
* @see app/Http/Controllers/Owner/SettingsController.php:106
* @route '/owner/settings/integrations'
*/
updateIntegrationsForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updateIntegrations.url(options),
    method: 'post',
})

updateIntegrations.form = updateIntegrationsForm

const SettingsController = { company, updateCompany, integrations, updateIntegrations }

export default SettingsController