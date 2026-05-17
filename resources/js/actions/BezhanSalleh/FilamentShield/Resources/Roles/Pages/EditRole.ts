import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanpro/shield/roles/{record}/edit'
*/
const EditRole7861039ca6994287c0ac3f0f3c470b9e = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRole7861039ca6994287c0ac3f0f3c470b9e.url(args, options),
    method: 'get',
})

EditRole7861039ca6994287c0ac3f0f3c470b9e.definition = {
    methods: ["get","head"],
    url: '/titanpro/shield/roles/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanpro/shield/roles/{record}/edit'
*/
EditRole7861039ca6994287c0ac3f0f3c470b9e.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditRole7861039ca6994287c0ac3f0f3c470b9e.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanpro/shield/roles/{record}/edit'
*/
EditRole7861039ca6994287c0ac3f0f3c470b9e.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRole7861039ca6994287c0ac3f0f3c470b9e.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanpro/shield/roles/{record}/edit'
*/
EditRole7861039ca6994287c0ac3f0f3c470b9e.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditRole7861039ca6994287c0ac3f0f3c470b9e.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanpro/shield/roles/{record}/edit'
*/
const EditRole7861039ca6994287c0ac3f0f3c470b9eForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRole7861039ca6994287c0ac3f0f3c470b9e.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanpro/shield/roles/{record}/edit'
*/
EditRole7861039ca6994287c0ac3f0f3c470b9eForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRole7861039ca6994287c0ac3f0f3c470b9e.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanpro/shield/roles/{record}/edit'
*/
EditRole7861039ca6994287c0ac3f0f3c470b9eForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRole7861039ca6994287c0ac3f0f3c470b9e.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditRole7861039ca6994287c0ac3f0f3c470b9e.form = EditRole7861039ca6994287c0ac3f0f3c470b9eForm
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/groundzero/shield/roles/{record}/edit'
*/
const EditRole86cec2825c08577d6b6920d19b986203 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRole86cec2825c08577d6b6920d19b986203.url(args, options),
    method: 'get',
})

EditRole86cec2825c08577d6b6920d19b986203.definition = {
    methods: ["get","head"],
    url: '/groundzero/shield/roles/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/groundzero/shield/roles/{record}/edit'
*/
EditRole86cec2825c08577d6b6920d19b986203.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditRole86cec2825c08577d6b6920d19b986203.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/groundzero/shield/roles/{record}/edit'
*/
EditRole86cec2825c08577d6b6920d19b986203.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRole86cec2825c08577d6b6920d19b986203.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/groundzero/shield/roles/{record}/edit'
*/
EditRole86cec2825c08577d6b6920d19b986203.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditRole86cec2825c08577d6b6920d19b986203.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/groundzero/shield/roles/{record}/edit'
*/
const EditRole86cec2825c08577d6b6920d19b986203Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRole86cec2825c08577d6b6920d19b986203.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/groundzero/shield/roles/{record}/edit'
*/
EditRole86cec2825c08577d6b6920d19b986203Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRole86cec2825c08577d6b6920d19b986203.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/groundzero/shield/roles/{record}/edit'
*/
EditRole86cec2825c08577d6b6920d19b986203Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRole86cec2825c08577d6b6920d19b986203.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditRole86cec2825c08577d6b6920d19b986203.form = EditRole86cec2825c08577d6b6920d19b986203Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanquotes/shield/roles/{record}/edit'
*/
const EditRole93a67b51be08402988b06dc0b6d67b53 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRole93a67b51be08402988b06dc0b6d67b53.url(args, options),
    method: 'get',
})

EditRole93a67b51be08402988b06dc0b6d67b53.definition = {
    methods: ["get","head"],
    url: '/titanquotes/shield/roles/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanquotes/shield/roles/{record}/edit'
*/
EditRole93a67b51be08402988b06dc0b6d67b53.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditRole93a67b51be08402988b06dc0b6d67b53.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanquotes/shield/roles/{record}/edit'
*/
EditRole93a67b51be08402988b06dc0b6d67b53.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRole93a67b51be08402988b06dc0b6d67b53.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanquotes/shield/roles/{record}/edit'
*/
EditRole93a67b51be08402988b06dc0b6d67b53.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditRole93a67b51be08402988b06dc0b6d67b53.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanquotes/shield/roles/{record}/edit'
*/
const EditRole93a67b51be08402988b06dc0b6d67b53Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRole93a67b51be08402988b06dc0b6d67b53.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanquotes/shield/roles/{record}/edit'
*/
EditRole93a67b51be08402988b06dc0b6d67b53Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRole93a67b51be08402988b06dc0b6d67b53.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanquotes/shield/roles/{record}/edit'
*/
EditRole93a67b51be08402988b06dc0b6d67b53Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRole93a67b51be08402988b06dc0b6d67b53.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditRole93a67b51be08402988b06dc0b6d67b53.form = EditRole93a67b51be08402988b06dc0b6d67b53Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/zeropay/shield/roles/{record}/edit'
*/
const EditRolebbd3bb60b5053e74bd2bebb09e741b70 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRolebbd3bb60b5053e74bd2bebb09e741b70.url(args, options),
    method: 'get',
})

EditRolebbd3bb60b5053e74bd2bebb09e741b70.definition = {
    methods: ["get","head"],
    url: '/zeropay/shield/roles/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/zeropay/shield/roles/{record}/edit'
*/
EditRolebbd3bb60b5053e74bd2bebb09e741b70.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditRolebbd3bb60b5053e74bd2bebb09e741b70.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/zeropay/shield/roles/{record}/edit'
*/
EditRolebbd3bb60b5053e74bd2bebb09e741b70.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRolebbd3bb60b5053e74bd2bebb09e741b70.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/zeropay/shield/roles/{record}/edit'
*/
EditRolebbd3bb60b5053e74bd2bebb09e741b70.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditRolebbd3bb60b5053e74bd2bebb09e741b70.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/zeropay/shield/roles/{record}/edit'
*/
const EditRolebbd3bb60b5053e74bd2bebb09e741b70Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRolebbd3bb60b5053e74bd2bebb09e741b70.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/zeropay/shield/roles/{record}/edit'
*/
EditRolebbd3bb60b5053e74bd2bebb09e741b70Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRolebbd3bb60b5053e74bd2bebb09e741b70.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/zeropay/shield/roles/{record}/edit'
*/
EditRolebbd3bb60b5053e74bd2bebb09e741b70Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRolebbd3bb60b5053e74bd2bebb09e741b70.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditRolebbd3bb60b5053e74bd2bebb09e741b70.form = EditRolebbd3bb60b5053e74bd2bebb09e741b70Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titango/shield/roles/{record}/edit'
*/
const EditRole5194c91330caaacfa42328be8176121e = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRole5194c91330caaacfa42328be8176121e.url(args, options),
    method: 'get',
})

EditRole5194c91330caaacfa42328be8176121e.definition = {
    methods: ["get","head"],
    url: '/titango/shield/roles/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titango/shield/roles/{record}/edit'
*/
EditRole5194c91330caaacfa42328be8176121e.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditRole5194c91330caaacfa42328be8176121e.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titango/shield/roles/{record}/edit'
*/
EditRole5194c91330caaacfa42328be8176121e.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRole5194c91330caaacfa42328be8176121e.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titango/shield/roles/{record}/edit'
*/
EditRole5194c91330caaacfa42328be8176121e.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditRole5194c91330caaacfa42328be8176121e.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titango/shield/roles/{record}/edit'
*/
const EditRole5194c91330caaacfa42328be8176121eForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRole5194c91330caaacfa42328be8176121e.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titango/shield/roles/{record}/edit'
*/
EditRole5194c91330caaacfa42328be8176121eForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRole5194c91330caaacfa42328be8176121e.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titango/shield/roles/{record}/edit'
*/
EditRole5194c91330caaacfa42328be8176121eForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRole5194c91330caaacfa42328be8176121e.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditRole5194c91330caaacfa42328be8176121e.form = EditRole5194c91330caaacfa42328be8176121eForm
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titansolo/shield/roles/{record}/edit'
*/
const EditRoleebb4df3ac39642e05b111e705261b1e0 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRoleebb4df3ac39642e05b111e705261b1e0.url(args, options),
    method: 'get',
})

EditRoleebb4df3ac39642e05b111e705261b1e0.definition = {
    methods: ["get","head"],
    url: '/titansolo/shield/roles/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titansolo/shield/roles/{record}/edit'
*/
EditRoleebb4df3ac39642e05b111e705261b1e0.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditRoleebb4df3ac39642e05b111e705261b1e0.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titansolo/shield/roles/{record}/edit'
*/
EditRoleebb4df3ac39642e05b111e705261b1e0.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRoleebb4df3ac39642e05b111e705261b1e0.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titansolo/shield/roles/{record}/edit'
*/
EditRoleebb4df3ac39642e05b111e705261b1e0.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditRoleebb4df3ac39642e05b111e705261b1e0.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titansolo/shield/roles/{record}/edit'
*/
const EditRoleebb4df3ac39642e05b111e705261b1e0Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRoleebb4df3ac39642e05b111e705261b1e0.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titansolo/shield/roles/{record}/edit'
*/
EditRoleebb4df3ac39642e05b111e705261b1e0Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRoleebb4df3ac39642e05b111e705261b1e0.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titansolo/shield/roles/{record}/edit'
*/
EditRoleebb4df3ac39642e05b111e705261b1e0Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRoleebb4df3ac39642e05b111e705261b1e0.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditRoleebb4df3ac39642e05b111e705261b1e0.form = EditRoleebb4df3ac39642e05b111e705261b1e0Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanstudio/shield/roles/{record}/edit'
*/
const EditRole2cd853d0b361c82a17287bb85739ba8b = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRole2cd853d0b361c82a17287bb85739ba8b.url(args, options),
    method: 'get',
})

EditRole2cd853d0b361c82a17287bb85739ba8b.definition = {
    methods: ["get","head"],
    url: '/titanstudio/shield/roles/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanstudio/shield/roles/{record}/edit'
*/
EditRole2cd853d0b361c82a17287bb85739ba8b.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditRole2cd853d0b361c82a17287bb85739ba8b.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanstudio/shield/roles/{record}/edit'
*/
EditRole2cd853d0b361c82a17287bb85739ba8b.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRole2cd853d0b361c82a17287bb85739ba8b.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanstudio/shield/roles/{record}/edit'
*/
EditRole2cd853d0b361c82a17287bb85739ba8b.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditRole2cd853d0b361c82a17287bb85739ba8b.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanstudio/shield/roles/{record}/edit'
*/
const EditRole2cd853d0b361c82a17287bb85739ba8bForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRole2cd853d0b361c82a17287bb85739ba8b.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanstudio/shield/roles/{record}/edit'
*/
EditRole2cd853d0b361c82a17287bb85739ba8bForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRole2cd853d0b361c82a17287bb85739ba8b.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titanstudio/shield/roles/{record}/edit'
*/
EditRole2cd853d0b361c82a17287bb85739ba8bForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRole2cd853d0b361c82a17287bb85739ba8b.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditRole2cd853d0b361c82a17287bb85739ba8b.form = EditRole2cd853d0b361c82a17287bb85739ba8bForm
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titannexus/shield/roles/{record}/edit'
*/
const EditRolefad42a12f3b46e299b6374915ab91e54 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRolefad42a12f3b46e299b6374915ab91e54.url(args, options),
    method: 'get',
})

EditRolefad42a12f3b46e299b6374915ab91e54.definition = {
    methods: ["get","head"],
    url: '/titannexus/shield/roles/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titannexus/shield/roles/{record}/edit'
*/
EditRolefad42a12f3b46e299b6374915ab91e54.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditRolefad42a12f3b46e299b6374915ab91e54.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titannexus/shield/roles/{record}/edit'
*/
EditRolefad42a12f3b46e299b6374915ab91e54.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditRolefad42a12f3b46e299b6374915ab91e54.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titannexus/shield/roles/{record}/edit'
*/
EditRolefad42a12f3b46e299b6374915ab91e54.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditRolefad42a12f3b46e299b6374915ab91e54.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titannexus/shield/roles/{record}/edit'
*/
const EditRolefad42a12f3b46e299b6374915ab91e54Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRolefad42a12f3b46e299b6374915ab91e54.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titannexus/shield/roles/{record}/edit'
*/
EditRolefad42a12f3b46e299b6374915ab91e54Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRolefad42a12f3b46e299b6374915ab91e54.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/EditRole.php:7
* @route '/titannexus/shield/roles/{record}/edit'
*/
EditRolefad42a12f3b46e299b6374915ab91e54Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditRolefad42a12f3b46e299b6374915ab91e54.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditRolefad42a12f3b46e299b6374915ab91e54.form = EditRolefad42a12f3b46e299b6374915ab91e54Form

const EditRole = {
    '/titanpro/shield/roles/{record}/edit': EditRole7861039ca6994287c0ac3f0f3c470b9e,
    '/groundzero/shield/roles/{record}/edit': EditRole86cec2825c08577d6b6920d19b986203,
    '/titanquotes/shield/roles/{record}/edit': EditRole93a67b51be08402988b06dc0b6d67b53,
    '/zeropay/shield/roles/{record}/edit': EditRolebbd3bb60b5053e74bd2bebb09e741b70,
    '/titango/shield/roles/{record}/edit': EditRole5194c91330caaacfa42328be8176121e,
    '/titansolo/shield/roles/{record}/edit': EditRoleebb4df3ac39642e05b111e705261b1e0,
    '/titanstudio/shield/roles/{record}/edit': EditRole2cd853d0b361c82a17287bb85739ba8b,
    '/titannexus/shield/roles/{record}/edit': EditRolefad42a12f3b46e299b6374915ab91e54,
}

export default EditRole