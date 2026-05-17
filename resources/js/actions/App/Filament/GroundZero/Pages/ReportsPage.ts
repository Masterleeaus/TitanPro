import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\GroundZero\Pages\ReportsPage::__invoke
* @see app/Filament/GroundZero/Pages/ReportsPage.php:7
* @route '/groundzero/reports-page'
*/
const ReportsPage = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ReportsPage.url(options),
    method: 'get',
})

ReportsPage.definition = {
    methods: ["get","head"],
    url: '/groundzero/reports-page',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\GroundZero\Pages\ReportsPage::__invoke
* @see app/Filament/GroundZero/Pages/ReportsPage.php:7
* @route '/groundzero/reports-page'
*/
ReportsPage.url = (options?: RouteQueryOptions) => {
    return ReportsPage.definition.url + queryParams(options)
}

/**
* @see \App\Filament\GroundZero\Pages\ReportsPage::__invoke
* @see app/Filament/GroundZero/Pages/ReportsPage.php:7
* @route '/groundzero/reports-page'
*/
ReportsPage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ReportsPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\ReportsPage::__invoke
* @see app/Filament/GroundZero/Pages/ReportsPage.php:7
* @route '/groundzero/reports-page'
*/
ReportsPage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ReportsPage.url(options),
    method: 'head',
})

/**
* @see \App\Filament\GroundZero\Pages\ReportsPage::__invoke
* @see app/Filament/GroundZero/Pages/ReportsPage.php:7
* @route '/groundzero/reports-page'
*/
const ReportsPageForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ReportsPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\ReportsPage::__invoke
* @see app/Filament/GroundZero/Pages/ReportsPage.php:7
* @route '/groundzero/reports-page'
*/
ReportsPageForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ReportsPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\GroundZero\Pages\ReportsPage::__invoke
* @see app/Filament/GroundZero/Pages/ReportsPage.php:7
* @route '/groundzero/reports-page'
*/
ReportsPageForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ReportsPage.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ReportsPage.form = ReportsPageForm

export default ReportsPage