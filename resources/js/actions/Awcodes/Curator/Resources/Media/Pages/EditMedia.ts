import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \Awcodes\Curator\Resources\Media\Pages\EditMedia::__invoke
* @see vendor/awcodes/filament-curator/src/Resources/Media/Pages/EditMedia.php:7
* @route '/titanpro/media/{record}/edit'
*/
const EditMedia = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditMedia.url(args, options),
    method: 'get',
})

EditMedia.definition = {
    methods: ["get","head"],
    url: '/titanpro/media/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Awcodes\Curator\Resources\Media\Pages\EditMedia::__invoke
* @see vendor/awcodes/filament-curator/src/Resources/Media/Pages/EditMedia.php:7
* @route '/titanpro/media/{record}/edit'
*/
EditMedia.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditMedia.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Awcodes\Curator\Resources\Media\Pages\EditMedia::__invoke
* @see vendor/awcodes/filament-curator/src/Resources/Media/Pages/EditMedia.php:7
* @route '/titanpro/media/{record}/edit'
*/
EditMedia.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditMedia.url(args, options),
    method: 'get',
})

/**
* @see \Awcodes\Curator\Resources\Media\Pages\EditMedia::__invoke
* @see vendor/awcodes/filament-curator/src/Resources/Media/Pages/EditMedia.php:7
* @route '/titanpro/media/{record}/edit'
*/
EditMedia.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditMedia.url(args, options),
    method: 'head',
})

/**
* @see \Awcodes\Curator\Resources\Media\Pages\EditMedia::__invoke
* @see vendor/awcodes/filament-curator/src/Resources/Media/Pages/EditMedia.php:7
* @route '/titanpro/media/{record}/edit'
*/
const EditMediaForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditMedia.url(args, options),
    method: 'get',
})

/**
* @see \Awcodes\Curator\Resources\Media\Pages\EditMedia::__invoke
* @see vendor/awcodes/filament-curator/src/Resources/Media/Pages/EditMedia.php:7
* @route '/titanpro/media/{record}/edit'
*/
EditMediaForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditMedia.url(args, options),
    method: 'get',
})

/**
* @see \Awcodes\Curator\Resources\Media\Pages\EditMedia::__invoke
* @see vendor/awcodes/filament-curator/src/Resources/Media/Pages/EditMedia.php:7
* @route '/titanpro/media/{record}/edit'
*/
EditMediaForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditMedia.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditMedia.form = EditMediaForm

export default EditMedia