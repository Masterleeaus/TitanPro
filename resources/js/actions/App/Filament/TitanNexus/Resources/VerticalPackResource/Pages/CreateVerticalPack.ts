import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\CreateVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/CreateVerticalPack.php:7
* @route '/titannexus/verticals/create'
*/
const CreateVerticalPack = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateVerticalPack.url(options),
    method: 'get',
})

CreateVerticalPack.definition = {
    methods: ["get","head"],
    url: '/titannexus/verticals/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\CreateVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/CreateVerticalPack.php:7
* @route '/titannexus/verticals/create'
*/
CreateVerticalPack.url = (options?: RouteQueryOptions) => {
    return CreateVerticalPack.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\CreateVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/CreateVerticalPack.php:7
* @route '/titannexus/verticals/create'
*/
CreateVerticalPack.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateVerticalPack.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\CreateVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/CreateVerticalPack.php:7
* @route '/titannexus/verticals/create'
*/
CreateVerticalPack.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateVerticalPack.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\CreateVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/CreateVerticalPack.php:7
* @route '/titannexus/verticals/create'
*/
const CreateVerticalPackForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateVerticalPack.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\CreateVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/CreateVerticalPack.php:7
* @route '/titannexus/verticals/create'
*/
CreateVerticalPackForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateVerticalPack.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\CreateVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/CreateVerticalPack.php:7
* @route '/titannexus/verticals/create'
*/
CreateVerticalPackForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateVerticalPack.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateVerticalPack.form = CreateVerticalPackForm

export default CreateVerticalPack