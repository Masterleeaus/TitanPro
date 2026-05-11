import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\ListDealProjects::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/ListDealProjects.php:7
* @route '/admin/deal-projects'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/deal-projects',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\ListDealProjects::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/ListDealProjects.php:7
* @route '/admin/deal-projects'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\ListDealProjects::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/ListDealProjects.php:7
* @route '/admin/deal-projects'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\ListDealProjects::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/ListDealProjects.php:7
* @route '/admin/deal-projects'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\ListDealProjects::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/ListDealProjects.php:7
* @route '/admin/deal-projects'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\ListDealProjects::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/ListDealProjects.php:7
* @route '/admin/deal-projects'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\ListDealProjects::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/ListDealProjects.php:7
* @route '/admin/deal-projects'
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
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\CreateDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/CreateDealProject.php:7
* @route '/admin/deal-projects/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/deal-projects/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\CreateDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/CreateDealProject.php:7
* @route '/admin/deal-projects/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\CreateDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/CreateDealProject.php:7
* @route '/admin/deal-projects/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\CreateDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/CreateDealProject.php:7
* @route '/admin/deal-projects/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\CreateDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/CreateDealProject.php:7
* @route '/admin/deal-projects/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\CreateDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/CreateDealProject.php:7
* @route '/admin/deal-projects/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\CreateDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/CreateDealProject.php:7
* @route '/admin/deal-projects/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\EditDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/EditDealProject.php:7
* @route '/admin/deal-projects/{record}/edit'
*/
export const edit = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/deal-projects/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\EditDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/EditDealProject.php:7
* @route '/admin/deal-projects/{record}/edit'
*/
edit.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\EditDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/EditDealProject.php:7
* @route '/admin/deal-projects/{record}/edit'
*/
edit.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\EditDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/EditDealProject.php:7
* @route '/admin/deal-projects/{record}/edit'
*/
edit.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\EditDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/EditDealProject.php:7
* @route '/admin/deal-projects/{record}/edit'
*/
const editForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\EditDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/EditDealProject.php:7
* @route '/admin/deal-projects/{record}/edit'
*/
editForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\DealProjectResource\Pages\EditDealProject::__invoke
* @see Modules/CRMCore/Filament/Resources/DealProjectResource/Pages/EditDealProject.php:7
* @route '/admin/deal-projects/{record}/edit'
*/
editForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

const dealProjects = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    edit: Object.assign(edit, edit),
}

export default dealProjects