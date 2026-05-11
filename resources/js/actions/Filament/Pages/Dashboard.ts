import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanpro'
*/
const Dashboardc47cf31f0e3788c27315d4900ec55400 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboardc47cf31f0e3788c27315d4900ec55400.url(options),
    method: 'get',
})

Dashboardc47cf31f0e3788c27315d4900ec55400.definition = {
    methods: ["get","head"],
    url: '/titanpro',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanpro'
*/
Dashboardc47cf31f0e3788c27315d4900ec55400.url = (options?: RouteQueryOptions) => {
    return Dashboardc47cf31f0e3788c27315d4900ec55400.definition.url + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanpro'
*/
Dashboardc47cf31f0e3788c27315d4900ec55400.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboardc47cf31f0e3788c27315d4900ec55400.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanpro'
*/
Dashboardc47cf31f0e3788c27315d4900ec55400.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Dashboardc47cf31f0e3788c27315d4900ec55400.url(options),
    method: 'head',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanpro'
*/
const Dashboardc47cf31f0e3788c27315d4900ec55400Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboardc47cf31f0e3788c27315d4900ec55400.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanpro'
*/
Dashboardc47cf31f0e3788c27315d4900ec55400Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboardc47cf31f0e3788c27315d4900ec55400.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanpro'
*/
Dashboardc47cf31f0e3788c27315d4900ec55400Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboardc47cf31f0e3788c27315d4900ec55400.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Dashboardc47cf31f0e3788c27315d4900ec55400.form = Dashboardc47cf31f0e3788c27315d4900ec55400Form
/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/groundzero'
*/
const Dashboard4aa9acda582d01a928eee0ab1d3f9aae = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard4aa9acda582d01a928eee0ab1d3f9aae.url(options),
    method: 'get',
})

Dashboard4aa9acda582d01a928eee0ab1d3f9aae.definition = {
    methods: ["get","head"],
    url: '/groundzero',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/groundzero'
*/
Dashboard4aa9acda582d01a928eee0ab1d3f9aae.url = (options?: RouteQueryOptions) => {
    return Dashboard4aa9acda582d01a928eee0ab1d3f9aae.definition.url + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/groundzero'
*/
Dashboard4aa9acda582d01a928eee0ab1d3f9aae.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard4aa9acda582d01a928eee0ab1d3f9aae.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/groundzero'
*/
Dashboard4aa9acda582d01a928eee0ab1d3f9aae.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Dashboard4aa9acda582d01a928eee0ab1d3f9aae.url(options),
    method: 'head',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/groundzero'
*/
const Dashboard4aa9acda582d01a928eee0ab1d3f9aaeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboard4aa9acda582d01a928eee0ab1d3f9aae.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/groundzero'
*/
Dashboard4aa9acda582d01a928eee0ab1d3f9aaeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboard4aa9acda582d01a928eee0ab1d3f9aae.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/groundzero'
*/
Dashboard4aa9acda582d01a928eee0ab1d3f9aaeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboard4aa9acda582d01a928eee0ab1d3f9aae.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Dashboard4aa9acda582d01a928eee0ab1d3f9aae.form = Dashboard4aa9acda582d01a928eee0ab1d3f9aaeForm
/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanquotes'
*/
const Dashboardf9dd50881c1c7a9161f7840b8d216cd4 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboardf9dd50881c1c7a9161f7840b8d216cd4.url(options),
    method: 'get',
})

Dashboardf9dd50881c1c7a9161f7840b8d216cd4.definition = {
    methods: ["get","head"],
    url: '/titanquotes',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanquotes'
*/
Dashboardf9dd50881c1c7a9161f7840b8d216cd4.url = (options?: RouteQueryOptions) => {
    return Dashboardf9dd50881c1c7a9161f7840b8d216cd4.definition.url + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanquotes'
*/
Dashboardf9dd50881c1c7a9161f7840b8d216cd4.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboardf9dd50881c1c7a9161f7840b8d216cd4.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanquotes'
*/
Dashboardf9dd50881c1c7a9161f7840b8d216cd4.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Dashboardf9dd50881c1c7a9161f7840b8d216cd4.url(options),
    method: 'head',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanquotes'
*/
const Dashboardf9dd50881c1c7a9161f7840b8d216cd4Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboardf9dd50881c1c7a9161f7840b8d216cd4.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanquotes'
*/
Dashboardf9dd50881c1c7a9161f7840b8d216cd4Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboardf9dd50881c1c7a9161f7840b8d216cd4.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanquotes'
*/
Dashboardf9dd50881c1c7a9161f7840b8d216cd4Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboardf9dd50881c1c7a9161f7840b8d216cd4.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Dashboardf9dd50881c1c7a9161f7840b8d216cd4.form = Dashboardf9dd50881c1c7a9161f7840b8d216cd4Form
/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanstudio'
*/
const Dashboardf4f540317fcbf36669f8f35ad9543ff8 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboardf4f540317fcbf36669f8f35ad9543ff8.url(options),
    method: 'get',
})

Dashboardf4f540317fcbf36669f8f35ad9543ff8.definition = {
    methods: ["get","head"],
    url: '/titanstudio',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanstudio'
*/
Dashboardf4f540317fcbf36669f8f35ad9543ff8.url = (options?: RouteQueryOptions) => {
    return Dashboardf4f540317fcbf36669f8f35ad9543ff8.definition.url + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanstudio'
*/
Dashboardf4f540317fcbf36669f8f35ad9543ff8.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboardf4f540317fcbf36669f8f35ad9543ff8.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanstudio'
*/
Dashboardf4f540317fcbf36669f8f35ad9543ff8.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Dashboardf4f540317fcbf36669f8f35ad9543ff8.url(options),
    method: 'head',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanstudio'
*/
const Dashboardf4f540317fcbf36669f8f35ad9543ff8Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboardf4f540317fcbf36669f8f35ad9543ff8.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanstudio'
*/
Dashboardf4f540317fcbf36669f8f35ad9543ff8Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboardf4f540317fcbf36669f8f35ad9543ff8.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titanstudio'
*/
Dashboardf4f540317fcbf36669f8f35ad9543ff8Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboardf4f540317fcbf36669f8f35ad9543ff8.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Dashboardf4f540317fcbf36669f8f35ad9543ff8.form = Dashboardf4f540317fcbf36669f8f35ad9543ff8Form
/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titannexus'
*/
const Dashboardaa9613295f0ee4a882fd1c371acf0100 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboardaa9613295f0ee4a882fd1c371acf0100.url(options),
    method: 'get',
})

Dashboardaa9613295f0ee4a882fd1c371acf0100.definition = {
    methods: ["get","head"],
    url: '/titannexus',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titannexus'
*/
Dashboardaa9613295f0ee4a882fd1c371acf0100.url = (options?: RouteQueryOptions) => {
    return Dashboardaa9613295f0ee4a882fd1c371acf0100.definition.url + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titannexus'
*/
Dashboardaa9613295f0ee4a882fd1c371acf0100.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboardaa9613295f0ee4a882fd1c371acf0100.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titannexus'
*/
Dashboardaa9613295f0ee4a882fd1c371acf0100.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Dashboardaa9613295f0ee4a882fd1c371acf0100.url(options),
    method: 'head',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titannexus'
*/
const Dashboardaa9613295f0ee4a882fd1c371acf0100Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboardaa9613295f0ee4a882fd1c371acf0100.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titannexus'
*/
Dashboardaa9613295f0ee4a882fd1c371acf0100Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboardaa9613295f0ee4a882fd1c371acf0100.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titannexus'
*/
Dashboardaa9613295f0ee4a882fd1c371acf0100Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboardaa9613295f0ee4a882fd1c371acf0100.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Dashboardaa9613295f0ee4a882fd1c371acf0100.form = Dashboardaa9613295f0ee4a882fd1c371acf0100Form

const Dashboard = {
    '/titanpro': Dashboardc47cf31f0e3788c27315d4900ec55400,
    '/groundzero': Dashboard4aa9acda582d01a928eee0ab1d3f9aae,
    '/titanquotes': Dashboardf9dd50881c1c7a9161f7840b8d216cd4,
    '/titanstudio': Dashboardf4f540317fcbf36669f8f35ad9543ff8,
    '/titannexus': Dashboardaa9613295f0ee4a882fd1c371acf0100,
}

export default Dashboard