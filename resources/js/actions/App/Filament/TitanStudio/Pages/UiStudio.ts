import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\TitanStudio\Pages\UiStudio::__invoke
* @see app/Filament/TitanStudio/Pages/UiStudio.php:7
* @route '/titanstudio/ui-studio'
*/
const UiStudio = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: UiStudio.url(options),
    method: 'get',
})

UiStudio.definition = {
    methods: ["get","head"],
    url: '/titanstudio/ui-studio',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanStudio\Pages\UiStudio::__invoke
* @see app/Filament/TitanStudio/Pages/UiStudio.php:7
* @route '/titanstudio/ui-studio'
*/
UiStudio.url = (options?: RouteQueryOptions) => {
    return UiStudio.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanStudio\Pages\UiStudio::__invoke
* @see app/Filament/TitanStudio/Pages/UiStudio.php:7
* @route '/titanstudio/ui-studio'
*/
UiStudio.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: UiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanStudio\Pages\UiStudio::__invoke
* @see app/Filament/TitanStudio/Pages/UiStudio.php:7
* @route '/titanstudio/ui-studio'
*/
UiStudio.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: UiStudio.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanStudio\Pages\UiStudio::__invoke
* @see app/Filament/TitanStudio/Pages/UiStudio.php:7
* @route '/titanstudio/ui-studio'
*/
const UiStudioForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: UiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanStudio\Pages\UiStudio::__invoke
* @see app/Filament/TitanStudio/Pages/UiStudio.php:7
* @route '/titanstudio/ui-studio'
*/
UiStudioForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: UiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanStudio\Pages\UiStudio::__invoke
* @see app/Filament/TitanStudio/Pages/UiStudio.php:7
* @route '/titanstudio/ui-studio'
*/
UiStudioForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: UiStudio.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

UiStudio.form = UiStudioForm

export default UiStudio