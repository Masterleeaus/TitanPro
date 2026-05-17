import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \Awcodes\Curator\Resources\Media\Pages\ListMedia::__invoke
* @see vendor/awcodes/filament-curator/src/Resources/Media/Pages/ListMedia.php:7
* @route '/titanpro/media'
*/
const ListMedia = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListMedia.url(options),
    method: 'get',
})

ListMedia.definition = {
    methods: ["get","head"],
    url: '/titanpro/media',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Awcodes\Curator\Resources\Media\Pages\ListMedia::__invoke
* @see vendor/awcodes/filament-curator/src/Resources/Media/Pages/ListMedia.php:7
* @route '/titanpro/media'
*/
ListMedia.url = (options?: RouteQueryOptions) => {
    return ListMedia.definition.url + queryParams(options)
}

/**
* @see \Awcodes\Curator\Resources\Media\Pages\ListMedia::__invoke
* @see vendor/awcodes/filament-curator/src/Resources/Media/Pages/ListMedia.php:7
* @route '/titanpro/media'
*/
ListMedia.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListMedia.url(options),
    method: 'get',
})

/**
* @see \Awcodes\Curator\Resources\Media\Pages\ListMedia::__invoke
* @see vendor/awcodes/filament-curator/src/Resources/Media/Pages/ListMedia.php:7
* @route '/titanpro/media'
*/
ListMedia.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListMedia.url(options),
    method: 'head',
})

/**
* @see \Awcodes\Curator\Resources\Media\Pages\ListMedia::__invoke
* @see vendor/awcodes/filament-curator/src/Resources/Media/Pages/ListMedia.php:7
* @route '/titanpro/media'
*/
const ListMediaForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMedia.url(options),
    method: 'get',
})

/**
* @see \Awcodes\Curator\Resources\Media\Pages\ListMedia::__invoke
* @see vendor/awcodes/filament-curator/src/Resources/Media/Pages/ListMedia.php:7
* @route '/titanpro/media'
*/
ListMediaForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMedia.url(options),
    method: 'get',
})

/**
* @see \Awcodes\Curator\Resources\Media\Pages\ListMedia::__invoke
* @see vendor/awcodes/filament-curator/src/Resources/Media/Pages/ListMedia.php:7
* @route '/titanpro/media'
*/
ListMediaForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMedia.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListMedia.form = ListMediaForm

export default ListMedia