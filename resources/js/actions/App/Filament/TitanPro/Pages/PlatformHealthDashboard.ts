import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\TitanPro\Pages\PlatformHealthDashboard::__invoke
* @see app/Filament/TitanPro/Pages/PlatformHealthDashboard.php:7
* @route '/titanpro/platform-health-dashboard'
*/
const PlatformHealthDashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PlatformHealthDashboard.url(options),
    method: 'get',
})

PlatformHealthDashboard.definition = {
    methods: ["get","head"],
    url: '/titanpro/platform-health-dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanPro\Pages\PlatformHealthDashboard::__invoke
* @see app/Filament/TitanPro/Pages/PlatformHealthDashboard.php:7
* @route '/titanpro/platform-health-dashboard'
*/
PlatformHealthDashboard.url = (options?: RouteQueryOptions) => {
    return PlatformHealthDashboard.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanPro\Pages\PlatformHealthDashboard::__invoke
* @see app/Filament/TitanPro/Pages/PlatformHealthDashboard.php:7
* @route '/titanpro/platform-health-dashboard'
*/
PlatformHealthDashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PlatformHealthDashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Pages\PlatformHealthDashboard::__invoke
* @see app/Filament/TitanPro/Pages/PlatformHealthDashboard.php:7
* @route '/titanpro/platform-health-dashboard'
*/
PlatformHealthDashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: PlatformHealthDashboard.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanPro\Pages\PlatformHealthDashboard::__invoke
* @see app/Filament/TitanPro/Pages/PlatformHealthDashboard.php:7
* @route '/titanpro/platform-health-dashboard'
*/
const PlatformHealthDashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: PlatformHealthDashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Pages\PlatformHealthDashboard::__invoke
* @see app/Filament/TitanPro/Pages/PlatformHealthDashboard.php:7
* @route '/titanpro/platform-health-dashboard'
*/
PlatformHealthDashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: PlatformHealthDashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Pages\PlatformHealthDashboard::__invoke
* @see app/Filament/TitanPro/Pages/PlatformHealthDashboard.php:7
* @route '/titanpro/platform-health-dashboard'
*/
PlatformHealthDashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: PlatformHealthDashboard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

PlatformHealthDashboard.form = PlatformHealthDashboardForm

export default PlatformHealthDashboard