import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanpro/shield/roles/{record}'
*/
const ViewRole93cae8dfcb74a414ca2b90df41146f77 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRole93cae8dfcb74a414ca2b90df41146f77.url(args, options),
    method: 'get',
})

ViewRole93cae8dfcb74a414ca2b90df41146f77.definition = {
    methods: ["get","head"],
    url: '/titanpro/shield/roles/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanpro/shield/roles/{record}'
*/
ViewRole93cae8dfcb74a414ca2b90df41146f77.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ViewRole93cae8dfcb74a414ca2b90df41146f77.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanpro/shield/roles/{record}'
*/
ViewRole93cae8dfcb74a414ca2b90df41146f77.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRole93cae8dfcb74a414ca2b90df41146f77.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanpro/shield/roles/{record}'
*/
ViewRole93cae8dfcb74a414ca2b90df41146f77.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewRole93cae8dfcb74a414ca2b90df41146f77.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanpro/shield/roles/{record}'
*/
const ViewRole93cae8dfcb74a414ca2b90df41146f77Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRole93cae8dfcb74a414ca2b90df41146f77.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanpro/shield/roles/{record}'
*/
ViewRole93cae8dfcb74a414ca2b90df41146f77Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRole93cae8dfcb74a414ca2b90df41146f77.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanpro/shield/roles/{record}'
*/
ViewRole93cae8dfcb74a414ca2b90df41146f77Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRole93cae8dfcb74a414ca2b90df41146f77.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ViewRole93cae8dfcb74a414ca2b90df41146f77.form = ViewRole93cae8dfcb74a414ca2b90df41146f77Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/groundzero/shield/roles/{record}'
*/
const ViewRoledeef07cf8c20c34c882a05897bf1c73b = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRoledeef07cf8c20c34c882a05897bf1c73b.url(args, options),
    method: 'get',
})

ViewRoledeef07cf8c20c34c882a05897bf1c73b.definition = {
    methods: ["get","head"],
    url: '/groundzero/shield/roles/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/groundzero/shield/roles/{record}'
*/
ViewRoledeef07cf8c20c34c882a05897bf1c73b.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ViewRoledeef07cf8c20c34c882a05897bf1c73b.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/groundzero/shield/roles/{record}'
*/
ViewRoledeef07cf8c20c34c882a05897bf1c73b.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRoledeef07cf8c20c34c882a05897bf1c73b.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/groundzero/shield/roles/{record}'
*/
ViewRoledeef07cf8c20c34c882a05897bf1c73b.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewRoledeef07cf8c20c34c882a05897bf1c73b.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/groundzero/shield/roles/{record}'
*/
const ViewRoledeef07cf8c20c34c882a05897bf1c73bForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRoledeef07cf8c20c34c882a05897bf1c73b.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/groundzero/shield/roles/{record}'
*/
ViewRoledeef07cf8c20c34c882a05897bf1c73bForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRoledeef07cf8c20c34c882a05897bf1c73b.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/groundzero/shield/roles/{record}'
*/
ViewRoledeef07cf8c20c34c882a05897bf1c73bForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRoledeef07cf8c20c34c882a05897bf1c73b.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ViewRoledeef07cf8c20c34c882a05897bf1c73b.form = ViewRoledeef07cf8c20c34c882a05897bf1c73bForm
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanquotes/shield/roles/{record}'
*/
const ViewRole5771abb6721e822a99f003180bee30f2 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRole5771abb6721e822a99f003180bee30f2.url(args, options),
    method: 'get',
})

ViewRole5771abb6721e822a99f003180bee30f2.definition = {
    methods: ["get","head"],
    url: '/titanquotes/shield/roles/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanquotes/shield/roles/{record}'
*/
ViewRole5771abb6721e822a99f003180bee30f2.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ViewRole5771abb6721e822a99f003180bee30f2.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanquotes/shield/roles/{record}'
*/
ViewRole5771abb6721e822a99f003180bee30f2.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRole5771abb6721e822a99f003180bee30f2.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanquotes/shield/roles/{record}'
*/
ViewRole5771abb6721e822a99f003180bee30f2.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewRole5771abb6721e822a99f003180bee30f2.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanquotes/shield/roles/{record}'
*/
const ViewRole5771abb6721e822a99f003180bee30f2Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRole5771abb6721e822a99f003180bee30f2.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanquotes/shield/roles/{record}'
*/
ViewRole5771abb6721e822a99f003180bee30f2Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRole5771abb6721e822a99f003180bee30f2.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanquotes/shield/roles/{record}'
*/
ViewRole5771abb6721e822a99f003180bee30f2Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRole5771abb6721e822a99f003180bee30f2.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ViewRole5771abb6721e822a99f003180bee30f2.form = ViewRole5771abb6721e822a99f003180bee30f2Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/zeropay/shield/roles/{record}'
*/
const ViewRole0dcd6b370c4d36c8758ab623bf5f29d7 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRole0dcd6b370c4d36c8758ab623bf5f29d7.url(args, options),
    method: 'get',
})

ViewRole0dcd6b370c4d36c8758ab623bf5f29d7.definition = {
    methods: ["get","head"],
    url: '/zeropay/shield/roles/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/zeropay/shield/roles/{record}'
*/
ViewRole0dcd6b370c4d36c8758ab623bf5f29d7.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ViewRole0dcd6b370c4d36c8758ab623bf5f29d7.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/zeropay/shield/roles/{record}'
*/
ViewRole0dcd6b370c4d36c8758ab623bf5f29d7.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRole0dcd6b370c4d36c8758ab623bf5f29d7.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/zeropay/shield/roles/{record}'
*/
ViewRole0dcd6b370c4d36c8758ab623bf5f29d7.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewRole0dcd6b370c4d36c8758ab623bf5f29d7.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/zeropay/shield/roles/{record}'
*/
const ViewRole0dcd6b370c4d36c8758ab623bf5f29d7Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRole0dcd6b370c4d36c8758ab623bf5f29d7.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/zeropay/shield/roles/{record}'
*/
ViewRole0dcd6b370c4d36c8758ab623bf5f29d7Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRole0dcd6b370c4d36c8758ab623bf5f29d7.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/zeropay/shield/roles/{record}'
*/
ViewRole0dcd6b370c4d36c8758ab623bf5f29d7Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRole0dcd6b370c4d36c8758ab623bf5f29d7.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ViewRole0dcd6b370c4d36c8758ab623bf5f29d7.form = ViewRole0dcd6b370c4d36c8758ab623bf5f29d7Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titango/shield/roles/{record}'
*/
const ViewRole96c762727afb6e06925e55c7bff76dfa = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRole96c762727afb6e06925e55c7bff76dfa.url(args, options),
    method: 'get',
})

ViewRole96c762727afb6e06925e55c7bff76dfa.definition = {
    methods: ["get","head"],
    url: '/titango/shield/roles/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titango/shield/roles/{record}'
*/
ViewRole96c762727afb6e06925e55c7bff76dfa.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ViewRole96c762727afb6e06925e55c7bff76dfa.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titango/shield/roles/{record}'
*/
ViewRole96c762727afb6e06925e55c7bff76dfa.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRole96c762727afb6e06925e55c7bff76dfa.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titango/shield/roles/{record}'
*/
ViewRole96c762727afb6e06925e55c7bff76dfa.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewRole96c762727afb6e06925e55c7bff76dfa.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titango/shield/roles/{record}'
*/
const ViewRole96c762727afb6e06925e55c7bff76dfaForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRole96c762727afb6e06925e55c7bff76dfa.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titango/shield/roles/{record}'
*/
ViewRole96c762727afb6e06925e55c7bff76dfaForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRole96c762727afb6e06925e55c7bff76dfa.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titango/shield/roles/{record}'
*/
ViewRole96c762727afb6e06925e55c7bff76dfaForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRole96c762727afb6e06925e55c7bff76dfa.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ViewRole96c762727afb6e06925e55c7bff76dfa.form = ViewRole96c762727afb6e06925e55c7bff76dfaForm
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titansolo/shield/roles/{record}'
*/
const ViewRolea3896e783f9620f2609e86ba37c72a08 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRolea3896e783f9620f2609e86ba37c72a08.url(args, options),
    method: 'get',
})

ViewRolea3896e783f9620f2609e86ba37c72a08.definition = {
    methods: ["get","head"],
    url: '/titansolo/shield/roles/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titansolo/shield/roles/{record}'
*/
ViewRolea3896e783f9620f2609e86ba37c72a08.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ViewRolea3896e783f9620f2609e86ba37c72a08.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titansolo/shield/roles/{record}'
*/
ViewRolea3896e783f9620f2609e86ba37c72a08.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRolea3896e783f9620f2609e86ba37c72a08.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titansolo/shield/roles/{record}'
*/
ViewRolea3896e783f9620f2609e86ba37c72a08.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewRolea3896e783f9620f2609e86ba37c72a08.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titansolo/shield/roles/{record}'
*/
const ViewRolea3896e783f9620f2609e86ba37c72a08Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRolea3896e783f9620f2609e86ba37c72a08.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titansolo/shield/roles/{record}'
*/
ViewRolea3896e783f9620f2609e86ba37c72a08Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRolea3896e783f9620f2609e86ba37c72a08.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titansolo/shield/roles/{record}'
*/
ViewRolea3896e783f9620f2609e86ba37c72a08Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRolea3896e783f9620f2609e86ba37c72a08.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ViewRolea3896e783f9620f2609e86ba37c72a08.form = ViewRolea3896e783f9620f2609e86ba37c72a08Form
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanstudio/shield/roles/{record}'
*/
const ViewRolecffde131b9f474f54ab5eba0adc006eb = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRolecffde131b9f474f54ab5eba0adc006eb.url(args, options),
    method: 'get',
})

ViewRolecffde131b9f474f54ab5eba0adc006eb.definition = {
    methods: ["get","head"],
    url: '/titanstudio/shield/roles/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanstudio/shield/roles/{record}'
*/
ViewRolecffde131b9f474f54ab5eba0adc006eb.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ViewRolecffde131b9f474f54ab5eba0adc006eb.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanstudio/shield/roles/{record}'
*/
ViewRolecffde131b9f474f54ab5eba0adc006eb.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRolecffde131b9f474f54ab5eba0adc006eb.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanstudio/shield/roles/{record}'
*/
ViewRolecffde131b9f474f54ab5eba0adc006eb.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewRolecffde131b9f474f54ab5eba0adc006eb.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanstudio/shield/roles/{record}'
*/
const ViewRolecffde131b9f474f54ab5eba0adc006ebForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRolecffde131b9f474f54ab5eba0adc006eb.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanstudio/shield/roles/{record}'
*/
ViewRolecffde131b9f474f54ab5eba0adc006ebForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRolecffde131b9f474f54ab5eba0adc006eb.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titanstudio/shield/roles/{record}'
*/
ViewRolecffde131b9f474f54ab5eba0adc006ebForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRolecffde131b9f474f54ab5eba0adc006eb.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ViewRolecffde131b9f474f54ab5eba0adc006eb.form = ViewRolecffde131b9f474f54ab5eba0adc006ebForm
/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titannexus/shield/roles/{record}'
*/
const ViewRole88800083bf8fffa4e6b2209b4d9c8480 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRole88800083bf8fffa4e6b2209b4d9c8480.url(args, options),
    method: 'get',
})

ViewRole88800083bf8fffa4e6b2209b4d9c8480.definition = {
    methods: ["get","head"],
    url: '/titannexus/shield/roles/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titannexus/shield/roles/{record}'
*/
ViewRole88800083bf8fffa4e6b2209b4d9c8480.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ViewRole88800083bf8fffa4e6b2209b4d9c8480.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titannexus/shield/roles/{record}'
*/
ViewRole88800083bf8fffa4e6b2209b4d9c8480.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewRole88800083bf8fffa4e6b2209b4d9c8480.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titannexus/shield/roles/{record}'
*/
ViewRole88800083bf8fffa4e6b2209b4d9c8480.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewRole88800083bf8fffa4e6b2209b4d9c8480.url(args, options),
    method: 'head',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titannexus/shield/roles/{record}'
*/
const ViewRole88800083bf8fffa4e6b2209b4d9c8480Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRole88800083bf8fffa4e6b2209b4d9c8480.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titannexus/shield/roles/{record}'
*/
ViewRole88800083bf8fffa4e6b2209b4d9c8480Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRole88800083bf8fffa4e6b2209b4d9c8480.url(args, options),
    method: 'get',
})

/**
* @see \BezhanSalleh\FilamentShield\Resources\Roles\Pages\ViewRole::__invoke
* @see vendor/bezhansalleh/filament-shield/src/Resources/Roles/Pages/ViewRole.php:7
* @route '/titannexus/shield/roles/{record}'
*/
ViewRole88800083bf8fffa4e6b2209b4d9c8480Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ViewRole88800083bf8fffa4e6b2209b4d9c8480.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ViewRole88800083bf8fffa4e6b2209b4d9c8480.form = ViewRole88800083bf8fffa4e6b2209b4d9c8480Form

const ViewRole = {
    '/titanpro/shield/roles/{record}': ViewRole93cae8dfcb74a414ca2b90df41146f77,
    '/groundzero/shield/roles/{record}': ViewRoledeef07cf8c20c34c882a05897bf1c73b,
    '/titanquotes/shield/roles/{record}': ViewRole5771abb6721e822a99f003180bee30f2,
    '/zeropay/shield/roles/{record}': ViewRole0dcd6b370c4d36c8758ab623bf5f29d7,
    '/titango/shield/roles/{record}': ViewRole96c762727afb6e06925e55c7bff76dfa,
    '/titansolo/shield/roles/{record}': ViewRolea3896e783f9620f2609e86ba37c72a08,
    '/titanstudio/shield/roles/{record}': ViewRolecffde131b9f474f54ab5eba0adc006eb,
    '/titannexus/shield/roles/{record}': ViewRole88800083bf8fffa4e6b2209b4d9c8480,
}

export default ViewRole