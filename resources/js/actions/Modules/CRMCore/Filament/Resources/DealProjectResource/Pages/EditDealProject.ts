import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\EditDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/EditDealProject.php:7
* @route '/admin/deal-projects/{record}/edit'
*/
const EditDealProject = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditDealProject.url(args, options),
    method: 'get',
})

EditDealProject.definition = {
    methods: ["get","head"],
    url: '/admin/deal-projects/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\EditDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/EditDealProject.php:7
* @route '/admin/deal-projects/{record}/edit'
*/
EditDealProject.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditDealProject.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\EditDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/EditDealProject.php:7
* @route '/admin/deal-projects/{record}/edit'
*/
EditDealProject.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditDealProject.url(args, options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\EditDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/EditDealProject.php:7
* @route '/admin/deal-projects/{record}/edit'
*/
EditDealProject.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditDealProject.url(args, options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\EditDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/EditDealProject.php:7
* @route '/admin/deal-projects/{record}/edit'
*/
const EditDealProjectForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditDealProject.url(args, options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\EditDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/EditDealProject.php:7
* @route '/admin/deal-projects/{record}/edit'
*/
EditDealProjectForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditDealProject.url(args, options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\EditDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/EditDealProject.php:7
* @route '/admin/deal-projects/{record}/edit'
*/
EditDealProjectForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditDealProject.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditDealProject.form = EditDealProjectForm

export default EditDealProject