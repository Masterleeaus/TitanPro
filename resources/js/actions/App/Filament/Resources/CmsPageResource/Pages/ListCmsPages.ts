import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/titanpro/cms-pages'
*/
const ListCmsPagesae814fd6960c4216dd442b42091ee619 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCmsPagesae814fd6960c4216dd442b42091ee619.url(options),
    method: 'get',
})

ListCmsPagesae814fd6960c4216dd442b42091ee619.definition = {
    methods: ["get","head"],
    url: '/titanpro/cms-pages',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/titanpro/cms-pages'
*/
ListCmsPagesae814fd6960c4216dd442b42091ee619.url = (options?: RouteQueryOptions) => {
    return ListCmsPagesae814fd6960c4216dd442b42091ee619.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/titanpro/cms-pages'
*/
ListCmsPagesae814fd6960c4216dd442b42091ee619.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCmsPagesae814fd6960c4216dd442b42091ee619.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/titanpro/cms-pages'
*/
ListCmsPagesae814fd6960c4216dd442b42091ee619.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListCmsPagesae814fd6960c4216dd442b42091ee619.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/titanpro/cms-pages'
*/
const ListCmsPagesae814fd6960c4216dd442b42091ee619Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCmsPagesae814fd6960c4216dd442b42091ee619.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/titanpro/cms-pages'
*/
ListCmsPagesae814fd6960c4216dd442b42091ee619Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCmsPagesae814fd6960c4216dd442b42091ee619.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/titanpro/cms-pages'
*/
ListCmsPagesae814fd6960c4216dd442b42091ee619Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCmsPagesae814fd6960c4216dd442b42091ee619.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListCmsPagesae814fd6960c4216dd442b42091ee619.form = ListCmsPagesae814fd6960c4216dd442b42091ee619Form
/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/titanstudio/cms-pages'
*/
const ListCmsPagesec041581146f830a880de0a62ad26718 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCmsPagesec041581146f830a880de0a62ad26718.url(options),
    method: 'get',
})

ListCmsPagesec041581146f830a880de0a62ad26718.definition = {
    methods: ["get","head"],
    url: '/titanstudio/cms-pages',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/titanstudio/cms-pages'
*/
ListCmsPagesec041581146f830a880de0a62ad26718.url = (options?: RouteQueryOptions) => {
    return ListCmsPagesec041581146f830a880de0a62ad26718.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/titanstudio/cms-pages'
*/
ListCmsPagesec041581146f830a880de0a62ad26718.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCmsPagesec041581146f830a880de0a62ad26718.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/titanstudio/cms-pages'
*/
ListCmsPagesec041581146f830a880de0a62ad26718.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListCmsPagesec041581146f830a880de0a62ad26718.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/titanstudio/cms-pages'
*/
const ListCmsPagesec041581146f830a880de0a62ad26718Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCmsPagesec041581146f830a880de0a62ad26718.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/titanstudio/cms-pages'
*/
ListCmsPagesec041581146f830a880de0a62ad26718Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCmsPagesec041581146f830a880de0a62ad26718.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\ListCmsPages::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/ListCmsPages.php:7
* @route '/titanstudio/cms-pages'
*/
ListCmsPagesec041581146f830a880de0a62ad26718Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCmsPagesec041581146f830a880de0a62ad26718.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListCmsPagesec041581146f830a880de0a62ad26718.form = ListCmsPagesec041581146f830a880de0a62ad26718Form

const ListCmsPages = {
    '/titanpro/cms-pages': ListCmsPagesae814fd6960c4216dd442b42091ee619,
    '/titanstudio/cms-pages': ListCmsPagesec041581146f830a880de0a62ad26718,
}

export default ListCmsPages