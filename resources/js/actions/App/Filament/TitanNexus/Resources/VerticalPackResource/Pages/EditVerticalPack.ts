import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\EditVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/EditVerticalPack.php:7
* @route '/titannexus/verticals/{record}/edit'
*/
const EditVerticalPack = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditVerticalPack.url(args, options),
    method: 'get',
})

EditVerticalPack.definition = {
    methods: ["get","head"],
    url: '/titannexus/verticals/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\EditVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/EditVerticalPack.php:7
* @route '/titannexus/verticals/{record}/edit'
*/
EditVerticalPack.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditVerticalPack.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\EditVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/EditVerticalPack.php:7
* @route '/titannexus/verticals/{record}/edit'
*/
EditVerticalPack.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditVerticalPack.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\EditVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/EditVerticalPack.php:7
* @route '/titannexus/verticals/{record}/edit'
*/
EditVerticalPack.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditVerticalPack.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\EditVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/EditVerticalPack.php:7
* @route '/titannexus/verticals/{record}/edit'
*/
const EditVerticalPackForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditVerticalPack.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\EditVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/EditVerticalPack.php:7
* @route '/titannexus/verticals/{record}/edit'
*/
EditVerticalPackForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditVerticalPack.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Resources\VerticalPackResource\Pages\EditVerticalPack::__invoke
* @see app/Filament/TitanNexus/Resources/VerticalPackResource/Pages/EditVerticalPack.php:7
* @route '/titannexus/verticals/{record}/edit'
*/
EditVerticalPackForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditVerticalPack.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditVerticalPack.form = EditVerticalPackForm

export default EditVerticalPack