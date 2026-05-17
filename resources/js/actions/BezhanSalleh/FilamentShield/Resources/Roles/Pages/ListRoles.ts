import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanpro/shield/roles'
*/
const ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bd = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bd.url(options),
    method: 'get',
})

ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bd.definition = {
    methods: ["get","head"],
    url: '/titanpro/shield/roles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanpro/shield/roles'
*/
ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bd.url = (options?: RouteQueryOptions) => {
    return ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bd.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanpro/shield/roles'
*/
ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bd.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bd.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanpro/shield/roles'
*/
ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bd.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bd.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanpro/shield/roles'
*/
const ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bdForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bd.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanpro/shield/roles'
*/
ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bdForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bd.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanpro/shield/roles'
*/
ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bdForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bd.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bd.form = ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bdForm
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/groundzero/shield/roles'
*/
const ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14.url(options),
    method: 'get',
})

ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14.definition = {
    methods: ["get","head"],
    url: '/groundzero/shield/roles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/groundzero/shield/roles'
*/
ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14.url = (options?: RouteQueryOptions) => {
    return ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/groundzero/shield/roles'
*/
ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/groundzero/shield/roles'
*/
ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/groundzero/shield/roles'
*/
const ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/groundzero/shield/roles'
*/
ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/groundzero/shield/roles'
*/
ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14.form = ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanquotes/shield/roles'
*/
const ListRoles4bd6d11284f2466290a0b1365c3c4857 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRoles4bd6d11284f2466290a0b1365c3c4857.url(options),
    method: 'get',
})

ListRoles4bd6d11284f2466290a0b1365c3c4857.definition = {
    methods: ["get","head"],
    url: '/titanquotes/shield/roles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanquotes/shield/roles'
*/
ListRoles4bd6d11284f2466290a0b1365c3c4857.url = (options?: RouteQueryOptions) => {
    return ListRoles4bd6d11284f2466290a0b1365c3c4857.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanquotes/shield/roles'
*/
ListRoles4bd6d11284f2466290a0b1365c3c4857.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRoles4bd6d11284f2466290a0b1365c3c4857.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanquotes/shield/roles'
*/
ListRoles4bd6d11284f2466290a0b1365c3c4857.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListRoles4bd6d11284f2466290a0b1365c3c4857.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanquotes/shield/roles'
*/
const ListRoles4bd6d11284f2466290a0b1365c3c4857Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRoles4bd6d11284f2466290a0b1365c3c4857.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanquotes/shield/roles'
*/
ListRoles4bd6d11284f2466290a0b1365c3c4857Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRoles4bd6d11284f2466290a0b1365c3c4857.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanquotes/shield/roles'
*/
ListRoles4bd6d11284f2466290a0b1365c3c4857Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRoles4bd6d11284f2466290a0b1365c3c4857.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListRoles4bd6d11284f2466290a0b1365c3c4857.form = ListRoles4bd6d11284f2466290a0b1365c3c4857Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/zeropay/shield/roles'
*/
const ListRoles28e87ebe26403b971f8756acafcf9a7e = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRoles28e87ebe26403b971f8756acafcf9a7e.url(options),
    method: 'get',
})

ListRoles28e87ebe26403b971f8756acafcf9a7e.definition = {
    methods: ["get","head"],
    url: '/zeropay/shield/roles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/zeropay/shield/roles'
*/
ListRoles28e87ebe26403b971f8756acafcf9a7e.url = (options?: RouteQueryOptions) => {
    return ListRoles28e87ebe26403b971f8756acafcf9a7e.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/zeropay/shield/roles'
*/
ListRoles28e87ebe26403b971f8756acafcf9a7e.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRoles28e87ebe26403b971f8756acafcf9a7e.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/zeropay/shield/roles'
*/
ListRoles28e87ebe26403b971f8756acafcf9a7e.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListRoles28e87ebe26403b971f8756acafcf9a7e.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/zeropay/shield/roles'
*/
const ListRoles28e87ebe26403b971f8756acafcf9a7eForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRoles28e87ebe26403b971f8756acafcf9a7e.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/zeropay/shield/roles'
*/
ListRoles28e87ebe26403b971f8756acafcf9a7eForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRoles28e87ebe26403b971f8756acafcf9a7e.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/zeropay/shield/roles'
*/
ListRoles28e87ebe26403b971f8756acafcf9a7eForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRoles28e87ebe26403b971f8756acafcf9a7e.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListRoles28e87ebe26403b971f8756acafcf9a7e.form = ListRoles28e87ebe26403b971f8756acafcf9a7eForm
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titango/shield/roles'
*/
const ListRoles14eacfee2c44daf7934c7160e2edd774 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRoles14eacfee2c44daf7934c7160e2edd774.url(options),
    method: 'get',
})

ListRoles14eacfee2c44daf7934c7160e2edd774.definition = {
    methods: ["get","head"],
    url: '/titango/shield/roles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titango/shield/roles'
*/
ListRoles14eacfee2c44daf7934c7160e2edd774.url = (options?: RouteQueryOptions) => {
    return ListRoles14eacfee2c44daf7934c7160e2edd774.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titango/shield/roles'
*/
ListRoles14eacfee2c44daf7934c7160e2edd774.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRoles14eacfee2c44daf7934c7160e2edd774.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titango/shield/roles'
*/
ListRoles14eacfee2c44daf7934c7160e2edd774.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListRoles14eacfee2c44daf7934c7160e2edd774.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titango/shield/roles'
*/
const ListRoles14eacfee2c44daf7934c7160e2edd774Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRoles14eacfee2c44daf7934c7160e2edd774.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titango/shield/roles'
*/
ListRoles14eacfee2c44daf7934c7160e2edd774Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRoles14eacfee2c44daf7934c7160e2edd774.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titango/shield/roles'
*/
ListRoles14eacfee2c44daf7934c7160e2edd774Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRoles14eacfee2c44daf7934c7160e2edd774.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListRoles14eacfee2c44daf7934c7160e2edd774.form = ListRoles14eacfee2c44daf7934c7160e2edd774Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titansolo/shield/roles'
*/
const ListRolesf4c0aa8abbec1067038f44cacd959ad2 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRolesf4c0aa8abbec1067038f44cacd959ad2.url(options),
    method: 'get',
})

ListRolesf4c0aa8abbec1067038f44cacd959ad2.definition = {
    methods: ["get","head"],
    url: '/titansolo/shield/roles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titansolo/shield/roles'
*/
ListRolesf4c0aa8abbec1067038f44cacd959ad2.url = (options?: RouteQueryOptions) => {
    return ListRolesf4c0aa8abbec1067038f44cacd959ad2.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titansolo/shield/roles'
*/
ListRolesf4c0aa8abbec1067038f44cacd959ad2.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRolesf4c0aa8abbec1067038f44cacd959ad2.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titansolo/shield/roles'
*/
ListRolesf4c0aa8abbec1067038f44cacd959ad2.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListRolesf4c0aa8abbec1067038f44cacd959ad2.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titansolo/shield/roles'
*/
const ListRolesf4c0aa8abbec1067038f44cacd959ad2Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRolesf4c0aa8abbec1067038f44cacd959ad2.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titansolo/shield/roles'
*/
ListRolesf4c0aa8abbec1067038f44cacd959ad2Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRolesf4c0aa8abbec1067038f44cacd959ad2.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titansolo/shield/roles'
*/
ListRolesf4c0aa8abbec1067038f44cacd959ad2Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRolesf4c0aa8abbec1067038f44cacd959ad2.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListRolesf4c0aa8abbec1067038f44cacd959ad2.form = ListRolesf4c0aa8abbec1067038f44cacd959ad2Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanstudio/shield/roles'
*/
const ListRoles36fac020ca0de3c63434f8478b97c21b = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRoles36fac020ca0de3c63434f8478b97c21b.url(options),
    method: 'get',
})

ListRoles36fac020ca0de3c63434f8478b97c21b.definition = {
    methods: ["get","head"],
    url: '/titanstudio/shield/roles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanstudio/shield/roles'
*/
ListRoles36fac020ca0de3c63434f8478b97c21b.url = (options?: RouteQueryOptions) => {
    return ListRoles36fac020ca0de3c63434f8478b97c21b.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanstudio/shield/roles'
*/
ListRoles36fac020ca0de3c63434f8478b97c21b.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRoles36fac020ca0de3c63434f8478b97c21b.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanstudio/shield/roles'
*/
ListRoles36fac020ca0de3c63434f8478b97c21b.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListRoles36fac020ca0de3c63434f8478b97c21b.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanstudio/shield/roles'
*/
const ListRoles36fac020ca0de3c63434f8478b97c21bForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRoles36fac020ca0de3c63434f8478b97c21b.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanstudio/shield/roles'
*/
ListRoles36fac020ca0de3c63434f8478b97c21bForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRoles36fac020ca0de3c63434f8478b97c21b.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titanstudio/shield/roles'
*/
ListRoles36fac020ca0de3c63434f8478b97c21bForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRoles36fac020ca0de3c63434f8478b97c21b.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListRoles36fac020ca0de3c63434f8478b97c21b.form = ListRoles36fac020ca0de3c63434f8478b97c21bForm
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titannexus/shield/roles'
*/
const ListRolesd3d9125226f46edac9db8e243f320f31 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRolesd3d9125226f46edac9db8e243f320f31.url(options),
    method: 'get',
})

ListRolesd3d9125226f46edac9db8e243f320f31.definition = {
    methods: ["get","head"],
    url: '/titannexus/shield/roles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titannexus/shield/roles'
*/
ListRolesd3d9125226f46edac9db8e243f320f31.url = (options?: RouteQueryOptions) => {
    return ListRolesd3d9125226f46edac9db8e243f320f31.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titannexus/shield/roles'
*/
ListRolesd3d9125226f46edac9db8e243f320f31.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRolesd3d9125226f46edac9db8e243f320f31.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titannexus/shield/roles'
*/
ListRolesd3d9125226f46edac9db8e243f320f31.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListRolesd3d9125226f46edac9db8e243f320f31.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titannexus/shield/roles'
*/
const ListRolesd3d9125226f46edac9db8e243f320f31Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRolesd3d9125226f46edac9db8e243f320f31.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titannexus/shield/roles'
*/
ListRolesd3d9125226f46edac9db8e243f320f31Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRolesd3d9125226f46edac9db8e243f320f31.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ListRoles.php:7
* @route '/titannexus/shield/roles'
*/
ListRolesd3d9125226f46edac9db8e243f320f31Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListRolesd3d9125226f46edac9db8e243f320f31.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListRolesd3d9125226f46edac9db8e243f320f31.form = ListRolesd3d9125226f46edac9db8e243f320f31Form

const ListRoles = {
    '/titanpro/shield/roles': ListRolesc2b8e73294a86e5d9c73a13cb9f0c7bd,
    '/groundzero/shield/roles': ListRolesd1b517d8cbe1ab3f21d0aa58835bdc14,
    '/titanquotes/shield/roles': ListRoles4bd6d11284f2466290a0b1365c3c4857,
    '/zeropay/shield/roles': ListRoles28e87ebe26403b971f8756acafcf9a7e,
    '/titango/shield/roles': ListRoles14eacfee2c44daf7934c7160e2edd774,
    '/titansolo/shield/roles': ListRolesf4c0aa8abbec1067038f44cacd959ad2,
    '/titanstudio/shield/roles': ListRoles36fac020ca0de3c63434f8478b97c21b,
    '/titannexus/shield/roles': ListRolesd3d9125226f46edac9db8e243f320f31,
}

export default ListRoles