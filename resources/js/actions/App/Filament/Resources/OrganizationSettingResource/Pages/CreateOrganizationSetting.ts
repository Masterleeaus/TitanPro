import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\CreateOrganizationSetting::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/CreateOrganizationSetting.php:7
* @route '/admin/organization-settings/create'
*/
const CreateOrganizationSetting = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateOrganizationSetting.url(options),
    method: 'get',
})

CreateOrganizationSetting.definition = {
    methods: ["get","head"],
    url: '/admin/organization-settings/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\CreateOrganizationSetting::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/CreateOrganizationSetting.php:7
* @route '/admin/organization-settings/create'
*/
CreateOrganizationSetting.url = (options?: RouteQueryOptions) => {
    return CreateOrganizationSetting.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\CreateOrganizationSetting::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/CreateOrganizationSetting.php:7
* @route '/admin/organization-settings/create'
*/
CreateOrganizationSetting.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateOrganizationSetting.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\CreateOrganizationSetting::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/CreateOrganizationSetting.php:7
* @route '/admin/organization-settings/create'
*/
CreateOrganizationSetting.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateOrganizationSetting.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\CreateOrganizationSetting::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/CreateOrganizationSetting.php:7
* @route '/admin/organization-settings/create'
*/
const CreateOrganizationSettingForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateOrganizationSetting.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\CreateOrganizationSetting::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/CreateOrganizationSetting.php:7
* @route '/admin/organization-settings/create'
*/
CreateOrganizationSettingForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateOrganizationSetting.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\CreateOrganizationSetting::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/CreateOrganizationSetting.php:7
* @route '/admin/organization-settings/create'
*/
CreateOrganizationSettingForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateOrganizationSetting.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateOrganizationSetting.form = CreateOrganizationSettingForm

export default CreateOrganizationSetting