import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanPro\Resources\OrganizationResource\Pages\EditOrganization::__invoke
* @see app/Filament/TitanPro/Resources/OrganizationResource/Pages/EditOrganization.php:7
* @route '/titanpro/organizations/{record}/edit'
*/
const EditOrganization = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditOrganization.url(args, options),
    method: 'get',
})

EditOrganization.definition = {
    methods: ["get","head"],
    url: '/titanpro/organizations/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanPro\Resources\OrganizationResource\Pages\EditOrganization::__invoke
* @see app/Filament/TitanPro/Resources/OrganizationResource/Pages/EditOrganization.php:7
* @route '/titanpro/organizations/{record}/edit'
*/
EditOrganization.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditOrganization.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\TitanPro\Resources\OrganizationResource\Pages\EditOrganization::__invoke
* @see app/Filament/TitanPro/Resources/OrganizationResource/Pages/EditOrganization.php:7
* @route '/titanpro/organizations/{record}/edit'
*/
EditOrganization.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditOrganization.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\OrganizationResource\Pages\EditOrganization::__invoke
* @see app/Filament/TitanPro/Resources/OrganizationResource/Pages/EditOrganization.php:7
* @route '/titanpro/organizations/{record}/edit'
*/
EditOrganization.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditOrganization.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\TitanPro\Resources\OrganizationResource\Pages\EditOrganization::__invoke
* @see app/Filament/TitanPro/Resources/OrganizationResource/Pages/EditOrganization.php:7
* @route '/titanpro/organizations/{record}/edit'
*/
const EditOrganizationForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditOrganization.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\OrganizationResource\Pages\EditOrganization::__invoke
* @see app/Filament/TitanPro/Resources/OrganizationResource/Pages/EditOrganization.php:7
* @route '/titanpro/organizations/{record}/edit'
*/
EditOrganizationForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditOrganization.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\OrganizationResource\Pages\EditOrganization::__invoke
* @see app/Filament/TitanPro/Resources/OrganizationResource/Pages/EditOrganization.php:7
* @route '/titanpro/organizations/{record}/edit'
*/
EditOrganizationForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditOrganization.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditOrganization.form = EditOrganizationForm

export default EditOrganization