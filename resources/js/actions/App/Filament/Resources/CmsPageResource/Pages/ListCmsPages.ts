import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/admin/cms-pages'
*/
const ListCmsPages = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCmsPages.url(options),
    method: 'get',
})

ListCmsPages.definition = {
    methods: ["get","head"],
    url: '/admin/cms-pages',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/admin/cms-pages'
*/
ListCmsPages.url = (options?: RouteQueryOptions) => {
    return ListCmsPages.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/admin/cms-pages'
*/
ListCmsPages.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCmsPages.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/admin/cms-pages'
*/
ListCmsPages.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListCmsPages.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/admin/cms-pages'
*/
const ListCmsPagesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCmsPages.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/admin/cms-pages'
*/
ListCmsPagesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCmsPages.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/admin/cms-pages'
*/
ListCmsPagesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCmsPages.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListCmsPages.form = ListCmsPagesForm

export default ListCmsPages