import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanPro\Resources\UserResource\Pages\CreateUser::__invoke
* @see app/Filament/TitanPro/Resources/UserResource/Pages/CreateUser.php:7
* @route '/titanpro/users/create'
*/
const CreateUser = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateUser.url(options),
    method: 'get',
})

CreateUser.definition = {
    methods: ["get","head"],
    url: '/titanpro/users/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanPro\Resources\UserResource\Pages\CreateUser::__invoke
* @see app/Filament/TitanPro/Resources/UserResource/Pages/CreateUser.php:7
* @route '/titanpro/users/create'
*/
CreateUser.url = (options?: RouteQueryOptions) => {
    return CreateUser.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanPro\Resources\UserResource\Pages\CreateUser::__invoke
* @see app/Filament/TitanPro/Resources/UserResource/Pages/CreateUser.php:7
* @route '/titanpro/users/create'
*/
CreateUser.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateUser.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\UserResource\Pages\CreateUser::__invoke
* @see app/Filament/TitanPro/Resources/UserResource/Pages/CreateUser.php:7
* @route '/titanpro/users/create'
*/
CreateUser.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateUser.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanPro\Resources\UserResource\Pages\CreateUser::__invoke
* @see app/Filament/TitanPro/Resources/UserResource/Pages/CreateUser.php:7
* @route '/titanpro/users/create'
*/
const CreateUserForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateUser.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\UserResource\Pages\CreateUser::__invoke
* @see app/Filament/TitanPro/Resources/UserResource/Pages/CreateUser.php:7
* @route '/titanpro/users/create'
*/
CreateUserForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateUser.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\UserResource\Pages\CreateUser::__invoke
* @see app/Filament/TitanPro/Resources/UserResource/Pages/CreateUser.php:7
* @route '/titanpro/users/create'
*/
CreateUserForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateUser.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateUser.form = CreateUserForm

export default CreateUser