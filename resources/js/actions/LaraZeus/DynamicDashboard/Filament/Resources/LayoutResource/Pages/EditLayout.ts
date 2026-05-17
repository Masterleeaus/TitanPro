import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titanpro/layouts/{record}/edit'
*/
const EditLayout44b181eb029a922ef158ab822d713af6 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLayout44b181eb029a922ef158ab822d713af6.url(args, options),
    method: 'get',
})

EditLayout44b181eb029a922ef158ab822d713af6.definition = {
    methods: ["get","head"],
    url: '/titanpro/layouts/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titanpro/layouts/{record}/edit'
*/
EditLayout44b181eb029a922ef158ab822d713af6.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditLayout44b181eb029a922ef158ab822d713af6.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titanpro/layouts/{record}/edit'
*/
EditLayout44b181eb029a922ef158ab822d713af6.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLayout44b181eb029a922ef158ab822d713af6.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titanpro/layouts/{record}/edit'
*/
EditLayout44b181eb029a922ef158ab822d713af6.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditLayout44b181eb029a922ef158ab822d713af6.url(args, options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titanpro/layouts/{record}/edit'
*/
const EditLayout44b181eb029a922ef158ab822d713af6Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLayout44b181eb029a922ef158ab822d713af6.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titanpro/layouts/{record}/edit'
*/
EditLayout44b181eb029a922ef158ab822d713af6Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLayout44b181eb029a922ef158ab822d713af6.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titanpro/layouts/{record}/edit'
*/
EditLayout44b181eb029a922ef158ab822d713af6Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLayout44b181eb029a922ef158ab822d713af6.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditLayout44b181eb029a922ef158ab822d713af6.form = EditLayout44b181eb029a922ef158ab822d713af6Form
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/groundzero/layouts/{record}/edit'
*/
const EditLayoutf7b6354c3fb3abb65e815d68cd8b69cc = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLayoutf7b6354c3fb3abb65e815d68cd8b69cc.url(args, options),
    method: 'get',
})

EditLayoutf7b6354c3fb3abb65e815d68cd8b69cc.definition = {
    methods: ["get","head"],
    url: '/groundzero/layouts/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/groundzero/layouts/{record}/edit'
*/
EditLayoutf7b6354c3fb3abb65e815d68cd8b69cc.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditLayoutf7b6354c3fb3abb65e815d68cd8b69cc.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/groundzero/layouts/{record}/edit'
*/
EditLayoutf7b6354c3fb3abb65e815d68cd8b69cc.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLayoutf7b6354c3fb3abb65e815d68cd8b69cc.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/groundzero/layouts/{record}/edit'
*/
EditLayoutf7b6354c3fb3abb65e815d68cd8b69cc.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditLayoutf7b6354c3fb3abb65e815d68cd8b69cc.url(args, options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/groundzero/layouts/{record}/edit'
*/
const EditLayoutf7b6354c3fb3abb65e815d68cd8b69ccForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLayoutf7b6354c3fb3abb65e815d68cd8b69cc.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/groundzero/layouts/{record}/edit'
*/
EditLayoutf7b6354c3fb3abb65e815d68cd8b69ccForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLayoutf7b6354c3fb3abb65e815d68cd8b69cc.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/groundzero/layouts/{record}/edit'
*/
EditLayoutf7b6354c3fb3abb65e815d68cd8b69ccForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLayoutf7b6354c3fb3abb65e815d68cd8b69cc.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditLayoutf7b6354c3fb3abb65e815d68cd8b69cc.form = EditLayoutf7b6354c3fb3abb65e815d68cd8b69ccForm
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titango/layouts/{record}/edit'
*/
const EditLayout0519c831da6f5223f960f7484f73a87b = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLayout0519c831da6f5223f960f7484f73a87b.url(args, options),
    method: 'get',
})

EditLayout0519c831da6f5223f960f7484f73a87b.definition = {
    methods: ["get","head"],
    url: '/titango/layouts/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titango/layouts/{record}/edit'
*/
EditLayout0519c831da6f5223f960f7484f73a87b.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditLayout0519c831da6f5223f960f7484f73a87b.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titango/layouts/{record}/edit'
*/
EditLayout0519c831da6f5223f960f7484f73a87b.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLayout0519c831da6f5223f960f7484f73a87b.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titango/layouts/{record}/edit'
*/
EditLayout0519c831da6f5223f960f7484f73a87b.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditLayout0519c831da6f5223f960f7484f73a87b.url(args, options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titango/layouts/{record}/edit'
*/
const EditLayout0519c831da6f5223f960f7484f73a87bForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLayout0519c831da6f5223f960f7484f73a87b.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titango/layouts/{record}/edit'
*/
EditLayout0519c831da6f5223f960f7484f73a87bForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLayout0519c831da6f5223f960f7484f73a87b.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titango/layouts/{record}/edit'
*/
EditLayout0519c831da6f5223f960f7484f73a87bForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLayout0519c831da6f5223f960f7484f73a87b.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditLayout0519c831da6f5223f960f7484f73a87b.form = EditLayout0519c831da6f5223f960f7484f73a87bForm
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titansolo/layouts/{record}/edit'
*/
const EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7ea = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7ea.url(args, options),
    method: 'get',
})

EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7ea.definition = {
    methods: ["get","head"],
    url: '/titansolo/layouts/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titansolo/layouts/{record}/edit'
*/
EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7ea.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7ea.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titansolo/layouts/{record}/edit'
*/
EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7ea.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7ea.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titansolo/layouts/{record}/edit'
*/
EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7ea.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7ea.url(args, options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titansolo/layouts/{record}/edit'
*/
const EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7eaForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7ea.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titansolo/layouts/{record}/edit'
*/
EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7eaForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7ea.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titansolo/layouts/{record}/edit'
*/
EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7eaForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7ea.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7ea.form = EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7eaForm
/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titannexus/layouts/{record}/edit'
*/
const EditLayout36b446e14d58a24522cf80a186e4365d = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLayout36b446e14d58a24522cf80a186e4365d.url(args, options),
    method: 'get',
})

EditLayout36b446e14d58a24522cf80a186e4365d.definition = {
    methods: ["get","head"],
    url: '/titannexus/layouts/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titannexus/layouts/{record}/edit'
*/
EditLayout36b446e14d58a24522cf80a186e4365d.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditLayout36b446e14d58a24522cf80a186e4365d.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titannexus/layouts/{record}/edit'
*/
EditLayout36b446e14d58a24522cf80a186e4365d.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLayout36b446e14d58a24522cf80a186e4365d.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titannexus/layouts/{record}/edit'
*/
EditLayout36b446e14d58a24522cf80a186e4365d.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditLayout36b446e14d58a24522cf80a186e4365d.url(args, options),
    method: 'head',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titannexus/layouts/{record}/edit'
*/
const EditLayout36b446e14d58a24522cf80a186e4365dForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLayout36b446e14d58a24522cf80a186e4365d.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titannexus/layouts/{record}/edit'
*/
EditLayout36b446e14d58a24522cf80a186e4365dForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLayout36b446e14d58a24522cf80a186e4365d.url(args, options),
    method: 'get',
})

/**
* @see \LaraZeus\DynamicDashboard\Filament\Resources\LayoutResource\Pages\EditLayout::__invoke
* @see vendor/lara-zeus/dynamic-dashboard/src/Filament/Resources/LayoutResource/Pages/EditLayout.php:7
* @route '/titannexus/layouts/{record}/edit'
*/
EditLayout36b446e14d58a24522cf80a186e4365dForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLayout36b446e14d58a24522cf80a186e4365d.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditLayout36b446e14d58a24522cf80a186e4365d.form = EditLayout36b446e14d58a24522cf80a186e4365dForm

const EditLayout = {
    '/titanpro/layouts/{record}/edit': EditLayout44b181eb029a922ef158ab822d713af6,
    '/groundzero/layouts/{record}/edit': EditLayoutf7b6354c3fb3abb65e815d68cd8b69cc,
    '/titango/layouts/{record}/edit': EditLayout0519c831da6f5223f960f7484f73a87b,
    '/titansolo/layouts/{record}/edit': EditLayoutf4e0b03732fd9fc9dbce4d8fda72b7ea,
    '/titannexus/layouts/{record}/edit': EditLayout36b446e14d58a24522cf80a186e4365d,
}

export default EditLayout