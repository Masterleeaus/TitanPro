import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanpro/shield/roles/create'
*/
const CreateRole1bb905933c9a7c5ec65911ae37400cda = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRole1bb905933c9a7c5ec65911ae37400cda.url(options),
    method: 'get',
})

CreateRole1bb905933c9a7c5ec65911ae37400cda.definition = {
    methods: ["get","head"],
    url: '/titanpro/shield/roles/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanpro/shield/roles/create'
*/
CreateRole1bb905933c9a7c5ec65911ae37400cda.url = (options?: RouteQueryOptions) => {
    return CreateRole1bb905933c9a7c5ec65911ae37400cda.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanpro/shield/roles/create'
*/
CreateRole1bb905933c9a7c5ec65911ae37400cda.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRole1bb905933c9a7c5ec65911ae37400cda.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanpro/shield/roles/create'
*/
CreateRole1bb905933c9a7c5ec65911ae37400cda.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateRole1bb905933c9a7c5ec65911ae37400cda.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanpro/shield/roles/create'
*/
const CreateRole1bb905933c9a7c5ec65911ae37400cdaForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRole1bb905933c9a7c5ec65911ae37400cda.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanpro/shield/roles/create'
*/
CreateRole1bb905933c9a7c5ec65911ae37400cdaForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRole1bb905933c9a7c5ec65911ae37400cda.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanpro/shield/roles/create'
*/
CreateRole1bb905933c9a7c5ec65911ae37400cdaForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRole1bb905933c9a7c5ec65911ae37400cda.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateRole1bb905933c9a7c5ec65911ae37400cda.form = CreateRole1bb905933c9a7c5ec65911ae37400cdaForm
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/groundzero/shield/roles/create'
*/
const CreateRoleb84d8c252ee41fcc8b5af42e069ebd24 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRoleb84d8c252ee41fcc8b5af42e069ebd24.url(options),
    method: 'get',
})

CreateRoleb84d8c252ee41fcc8b5af42e069ebd24.definition = {
    methods: ["get","head"],
    url: '/groundzero/shield/roles/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/groundzero/shield/roles/create'
*/
CreateRoleb84d8c252ee41fcc8b5af42e069ebd24.url = (options?: RouteQueryOptions) => {
    return CreateRoleb84d8c252ee41fcc8b5af42e069ebd24.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/groundzero/shield/roles/create'
*/
CreateRoleb84d8c252ee41fcc8b5af42e069ebd24.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRoleb84d8c252ee41fcc8b5af42e069ebd24.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/groundzero/shield/roles/create'
*/
CreateRoleb84d8c252ee41fcc8b5af42e069ebd24.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateRoleb84d8c252ee41fcc8b5af42e069ebd24.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/groundzero/shield/roles/create'
*/
const CreateRoleb84d8c252ee41fcc8b5af42e069ebd24Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRoleb84d8c252ee41fcc8b5af42e069ebd24.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/groundzero/shield/roles/create'
*/
CreateRoleb84d8c252ee41fcc8b5af42e069ebd24Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRoleb84d8c252ee41fcc8b5af42e069ebd24.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/groundzero/shield/roles/create'
*/
CreateRoleb84d8c252ee41fcc8b5af42e069ebd24Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRoleb84d8c252ee41fcc8b5af42e069ebd24.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateRoleb84d8c252ee41fcc8b5af42e069ebd24.form = CreateRoleb84d8c252ee41fcc8b5af42e069ebd24Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanquotes/shield/roles/create'
*/
const CreateRolea62c302014c0bb63fa8171869cac1255 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRolea62c302014c0bb63fa8171869cac1255.url(options),
    method: 'get',
})

CreateRolea62c302014c0bb63fa8171869cac1255.definition = {
    methods: ["get","head"],
    url: '/titanquotes/shield/roles/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanquotes/shield/roles/create'
*/
CreateRolea62c302014c0bb63fa8171869cac1255.url = (options?: RouteQueryOptions) => {
    return CreateRolea62c302014c0bb63fa8171869cac1255.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanquotes/shield/roles/create'
*/
CreateRolea62c302014c0bb63fa8171869cac1255.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRolea62c302014c0bb63fa8171869cac1255.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanquotes/shield/roles/create'
*/
CreateRolea62c302014c0bb63fa8171869cac1255.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateRolea62c302014c0bb63fa8171869cac1255.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanquotes/shield/roles/create'
*/
const CreateRolea62c302014c0bb63fa8171869cac1255Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRolea62c302014c0bb63fa8171869cac1255.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanquotes/shield/roles/create'
*/
CreateRolea62c302014c0bb63fa8171869cac1255Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRolea62c302014c0bb63fa8171869cac1255.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanquotes/shield/roles/create'
*/
CreateRolea62c302014c0bb63fa8171869cac1255Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRolea62c302014c0bb63fa8171869cac1255.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateRolea62c302014c0bb63fa8171869cac1255.form = CreateRolea62c302014c0bb63fa8171869cac1255Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/zeropay/shield/roles/create'
*/
const CreateRole859b276c44ea36dfb42c86897e9828ce = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRole859b276c44ea36dfb42c86897e9828ce.url(options),
    method: 'get',
})

CreateRole859b276c44ea36dfb42c86897e9828ce.definition = {
    methods: ["get","head"],
    url: '/zeropay/shield/roles/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/zeropay/shield/roles/create'
*/
CreateRole859b276c44ea36dfb42c86897e9828ce.url = (options?: RouteQueryOptions) => {
    return CreateRole859b276c44ea36dfb42c86897e9828ce.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/zeropay/shield/roles/create'
*/
CreateRole859b276c44ea36dfb42c86897e9828ce.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRole859b276c44ea36dfb42c86897e9828ce.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/zeropay/shield/roles/create'
*/
CreateRole859b276c44ea36dfb42c86897e9828ce.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateRole859b276c44ea36dfb42c86897e9828ce.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/zeropay/shield/roles/create'
*/
const CreateRole859b276c44ea36dfb42c86897e9828ceForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRole859b276c44ea36dfb42c86897e9828ce.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/zeropay/shield/roles/create'
*/
CreateRole859b276c44ea36dfb42c86897e9828ceForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRole859b276c44ea36dfb42c86897e9828ce.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/zeropay/shield/roles/create'
*/
CreateRole859b276c44ea36dfb42c86897e9828ceForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRole859b276c44ea36dfb42c86897e9828ce.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateRole859b276c44ea36dfb42c86897e9828ce.form = CreateRole859b276c44ea36dfb42c86897e9828ceForm
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titango/shield/roles/create'
*/
const CreateRolef86b29b91ce3339aa52723dde46e8bca = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRolef86b29b91ce3339aa52723dde46e8bca.url(options),
    method: 'get',
})

CreateRolef86b29b91ce3339aa52723dde46e8bca.definition = {
    methods: ["get","head"],
    url: '/titango/shield/roles/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titango/shield/roles/create'
*/
CreateRolef86b29b91ce3339aa52723dde46e8bca.url = (options?: RouteQueryOptions) => {
    return CreateRolef86b29b91ce3339aa52723dde46e8bca.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titango/shield/roles/create'
*/
CreateRolef86b29b91ce3339aa52723dde46e8bca.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRolef86b29b91ce3339aa52723dde46e8bca.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titango/shield/roles/create'
*/
CreateRolef86b29b91ce3339aa52723dde46e8bca.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateRolef86b29b91ce3339aa52723dde46e8bca.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titango/shield/roles/create'
*/
const CreateRolef86b29b91ce3339aa52723dde46e8bcaForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRolef86b29b91ce3339aa52723dde46e8bca.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titango/shield/roles/create'
*/
CreateRolef86b29b91ce3339aa52723dde46e8bcaForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRolef86b29b91ce3339aa52723dde46e8bca.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titango/shield/roles/create'
*/
CreateRolef86b29b91ce3339aa52723dde46e8bcaForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRolef86b29b91ce3339aa52723dde46e8bca.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateRolef86b29b91ce3339aa52723dde46e8bca.form = CreateRolef86b29b91ce3339aa52723dde46e8bcaForm
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titansolo/shield/roles/create'
*/
const CreateRole0a99718cf5dd6d4e290ea100edbf8798 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRole0a99718cf5dd6d4e290ea100edbf8798.url(options),
    method: 'get',
})

CreateRole0a99718cf5dd6d4e290ea100edbf8798.definition = {
    methods: ["get","head"],
    url: '/titansolo/shield/roles/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titansolo/shield/roles/create'
*/
CreateRole0a99718cf5dd6d4e290ea100edbf8798.url = (options?: RouteQueryOptions) => {
    return CreateRole0a99718cf5dd6d4e290ea100edbf8798.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titansolo/shield/roles/create'
*/
CreateRole0a99718cf5dd6d4e290ea100edbf8798.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRole0a99718cf5dd6d4e290ea100edbf8798.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titansolo/shield/roles/create'
*/
CreateRole0a99718cf5dd6d4e290ea100edbf8798.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateRole0a99718cf5dd6d4e290ea100edbf8798.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titansolo/shield/roles/create'
*/
const CreateRole0a99718cf5dd6d4e290ea100edbf8798Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRole0a99718cf5dd6d4e290ea100edbf8798.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titansolo/shield/roles/create'
*/
CreateRole0a99718cf5dd6d4e290ea100edbf8798Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRole0a99718cf5dd6d4e290ea100edbf8798.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titansolo/shield/roles/create'
*/
CreateRole0a99718cf5dd6d4e290ea100edbf8798Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRole0a99718cf5dd6d4e290ea100edbf8798.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateRole0a99718cf5dd6d4e290ea100edbf8798.form = CreateRole0a99718cf5dd6d4e290ea100edbf8798Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanstudio/shield/roles/create'
*/
const CreateRolef192f759550e8fa875995ea9759c5f57 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRolef192f759550e8fa875995ea9759c5f57.url(options),
    method: 'get',
})

CreateRolef192f759550e8fa875995ea9759c5f57.definition = {
    methods: ["get","head"],
    url: '/titanstudio/shield/roles/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanstudio/shield/roles/create'
*/
CreateRolef192f759550e8fa875995ea9759c5f57.url = (options?: RouteQueryOptions) => {
    return CreateRolef192f759550e8fa875995ea9759c5f57.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanstudio/shield/roles/create'
*/
CreateRolef192f759550e8fa875995ea9759c5f57.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRolef192f759550e8fa875995ea9759c5f57.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanstudio/shield/roles/create'
*/
CreateRolef192f759550e8fa875995ea9759c5f57.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateRolef192f759550e8fa875995ea9759c5f57.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanstudio/shield/roles/create'
*/
const CreateRolef192f759550e8fa875995ea9759c5f57Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRolef192f759550e8fa875995ea9759c5f57.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanstudio/shield/roles/create'
*/
CreateRolef192f759550e8fa875995ea9759c5f57Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRolef192f759550e8fa875995ea9759c5f57.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titanstudio/shield/roles/create'
*/
CreateRolef192f759550e8fa875995ea9759c5f57Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRolef192f759550e8fa875995ea9759c5f57.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateRolef192f759550e8fa875995ea9759c5f57.form = CreateRolef192f759550e8fa875995ea9759c5f57Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titannexus/shield/roles/create'
*/
const CreateRole1a59ff8e7daff48c0e07526c693a493e = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRole1a59ff8e7daff48c0e07526c693a493e.url(options),
    method: 'get',
})

CreateRole1a59ff8e7daff48c0e07526c693a493e.definition = {
    methods: ["get","head"],
    url: '/titannexus/shield/roles/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titannexus/shield/roles/create'
*/
CreateRole1a59ff8e7daff48c0e07526c693a493e.url = (options?: RouteQueryOptions) => {
    return CreateRole1a59ff8e7daff48c0e07526c693a493e.definition.url + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titannexus/shield/roles/create'
*/
CreateRole1a59ff8e7daff48c0e07526c693a493e.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRole1a59ff8e7daff48c0e07526c693a493e.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titannexus/shield/roles/create'
*/
CreateRole1a59ff8e7daff48c0e07526c693a493e.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateRole1a59ff8e7daff48c0e07526c693a493e.url(options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titannexus/shield/roles/create'
*/
const CreateRole1a59ff8e7daff48c0e07526c693a493eForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRole1a59ff8e7daff48c0e07526c693a493e.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titannexus/shield/roles/create'
*/
CreateRole1a59ff8e7daff48c0e07526c693a493eForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRole1a59ff8e7daff48c0e07526c693a493e.url(options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/CreateRole.php:7
* @route '/titannexus/shield/roles/create'
*/
CreateRole1a59ff8e7daff48c0e07526c693a493eForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateRole1a59ff8e7daff48c0e07526c693a493e.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateRole1a59ff8e7daff48c0e07526c693a493e.form = CreateRole1a59ff8e7daff48c0e07526c693a493eForm

const CreateRole = {
    '/titanpro/shield/roles/create': CreateRole1bb905933c9a7c5ec65911ae37400cda,
    '/groundzero/shield/roles/create': CreateRoleb84d8c252ee41fcc8b5af42e069ebd24,
    '/titanquotes/shield/roles/create': CreateRolea62c302014c0bb63fa8171869cac1255,
    '/zeropay/shield/roles/create': CreateRole859b276c44ea36dfb42c86897e9828ce,
    '/titango/shield/roles/create': CreateRolef86b29b91ce3339aa52723dde46e8bca,
    '/titansolo/shield/roles/create': CreateRole0a99718cf5dd6d4e290ea100edbf8798,
    '/titanstudio/shield/roles/create': CreateRolef192f759550e8fa875995ea9759c5f57,
    '/titannexus/shield/roles/create': CreateRole1a59ff8e7daff48c0e07526c693a493e,
}

export default CreateRole