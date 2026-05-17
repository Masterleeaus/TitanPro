import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanPro\Resources\OrganizationResource\Pages\CreateOrganization::__invoke
* @see app/Filament/TitanPro/Resources/OrganizationResource/Pages/CreateOrganization.php:7
* @route '/titanpro/organizations/create'
*/
const CreateOrganization = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateOrganization.url(options),
    method: 'get',
})

CreateOrganization.definition = {
    methods: ["get","head"],
    url: '/titanpro/organizations/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanPro\Resources\OrganizationResource\Pages\CreateOrganization::__invoke
* @see app/Filament/TitanPro/Resources/OrganizationResource/Pages/CreateOrganization.php:7
* @route '/titanpro/organizations/create'
*/
CreateOrganization.url = (options?: RouteQueryOptions) => {
    return CreateOrganization.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanPro\Resources\OrganizationResource\Pages\CreateOrganization::__invoke
* @see app/Filament/TitanPro/Resources/OrganizationResource/Pages/CreateOrganization.php:7
* @route '/titanpro/organizations/create'
*/
CreateOrganization.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateOrganization.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\OrganizationResource\Pages\CreateOrganization::__invoke
* @see app/Filament/TitanPro/Resources/OrganizationResource/Pages/CreateOrganization.php:7
* @route '/titanpro/organizations/create'
*/
CreateOrganization.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateOrganization.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanPro\Resources\OrganizationResource\Pages\CreateOrganization::__invoke
* @see app/Filament/TitanPro/Resources/OrganizationResource/Pages/CreateOrganization.php:7
* @route '/titanpro/organizations/create'
*/
const CreateOrganizationForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateOrganization.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\OrganizationResource\Pages\CreateOrganization::__invoke
* @see app/Filament/TitanPro/Resources/OrganizationResource/Pages/CreateOrganization.php:7
* @route '/titanpro/organizations/create'
*/
CreateOrganizationForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateOrganization.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\OrganizationResource\Pages\CreateOrganization::__invoke
* @see app/Filament/TitanPro/Resources/OrganizationResource/Pages/CreateOrganization.php:7
* @route '/titanpro/organizations/create'
*/
CreateOrganizationForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateOrganization.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateOrganization.form = CreateOrganizationForm

export default CreateOrganization