import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Owner\SettingsController::update
* @see app/Http/Controllers/Owner/SettingsController.php:44
* @route '/owner/settings/company'
*/
export const update = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

update.definition = {
    methods: ["post"],
    url: '/owner/settings/company',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Owner\SettingsController::update
* @see app/Http/Controllers/Owner/SettingsController.php:44
* @route '/owner/settings/company'
*/
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Owner\SettingsController::update
* @see app/Http/Controllers/Owner/SettingsController.php:44
* @route '/owner/settings/company'
*/
update.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\SettingsController::update
* @see app/Http/Controllers/Owner/SettingsController.php:44
* @route '/owner/settings/company'
*/
const updateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Owner\SettingsController::update
* @see app/Http/Controllers/Owner/SettingsController.php:44
* @route '/owner/settings/company'
*/
updateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(options),
    method: 'post',
})

update.form = updateForm

const company = {
    update: Object.assign(update, update),
}

export default company