import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\ListOrganizationSettings::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/ListOrganizationSettings.php:7
* @route '/admin/organization-settings'
*/
const ListOrganizationSettings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListOrganizationSettings.url(options),
    method: 'get',
})

ListOrganizationSettings.definition = {
    methods: ["get","head"],
    url: '/admin/organization-settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\ListOrganizationSettings::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/ListOrganizationSettings.php:7
* @route '/admin/organization-settings'
*/
ListOrganizationSettings.url = (options?: RouteQueryOptions) => {
    return ListOrganizationSettings.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\ListOrganizationSettings::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/ListOrganizationSettings.php:7
* @route '/admin/organization-settings'
*/
ListOrganizationSettings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListOrganizationSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\ListOrganizationSettings::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/ListOrganizationSettings.php:7
* @route '/admin/organization-settings'
*/
ListOrganizationSettings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListOrganizationSettings.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\ListOrganizationSettings::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/ListOrganizationSettings.php:7
* @route '/admin/organization-settings'
*/
const ListOrganizationSettingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListOrganizationSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\ListOrganizationSettings::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/ListOrganizationSettings.php:7
* @route '/admin/organization-settings'
*/
ListOrganizationSettingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListOrganizationSettings.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\ListOrganizationSettings::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/ListOrganizationSettings.php:7
* @route '/admin/organization-settings'
*/
ListOrganizationSettingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListOrganizationSettings.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListOrganizationSettings.form = ListOrganizationSettingsForm

export default ListOrganizationSettings