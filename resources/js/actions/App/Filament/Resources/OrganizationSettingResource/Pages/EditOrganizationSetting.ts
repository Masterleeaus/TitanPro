import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\EditOrganizationSetting::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/EditOrganizationSetting.php:7
* @route '/admin/organization-settings/{record}/edit'
*/
const EditOrganizationSetting = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditOrganizationSetting.url(args, options),
    method: 'get',
})

EditOrganizationSetting.definition = {
    methods: ["get","head"],
    url: '/admin/organization-settings/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\EditOrganizationSetting::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/EditOrganizationSetting.php:7
* @route '/admin/organization-settings/{record}/edit'
*/
EditOrganizationSetting.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { record: args }
    }

    if (Array.isArray(args)) {
        args = {
            record: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        record: args.record,
    }

    return EditOrganizationSetting.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\EditOrganizationSetting::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/EditOrganizationSetting.php:7
* @route '/admin/organization-settings/{record}/edit'
*/
EditOrganizationSetting.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditOrganizationSetting.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\EditOrganizationSetting::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/EditOrganizationSetting.php:7
* @route '/admin/organization-settings/{record}/edit'
*/
EditOrganizationSetting.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditOrganizationSetting.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\EditOrganizationSetting::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/EditOrganizationSetting.php:7
* @route '/admin/organization-settings/{record}/edit'
*/
const EditOrganizationSettingForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditOrganizationSetting.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\EditOrganizationSetting::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/EditOrganizationSetting.php:7
* @route '/admin/organization-settings/{record}/edit'
*/
EditOrganizationSettingForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditOrganizationSetting.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\OrganizationSettingResource\Pages\EditOrganizationSetting::__invoke
* @see app/Filament/Resources/OrganizationSettingResource/Pages/EditOrganizationSetting.php:7
* @route '/admin/organization-settings/{record}/edit'
*/
EditOrganizationSettingForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditOrganizationSetting.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditOrganizationSetting.form = EditOrganizationSettingForm

export default EditOrganizationSetting