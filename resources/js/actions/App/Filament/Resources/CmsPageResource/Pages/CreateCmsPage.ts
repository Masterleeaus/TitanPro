import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\CmsPageResource\Pages\CreateCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/CreateCmsPage.php:7
* @route '/admin/cms-pages/create'
*/
const CreateCmsPage = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateCmsPage.url(options),
    method: 'get',
})

CreateCmsPage.definition = {
    methods: ["get","head"],
    url: '/admin/cms-pages/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\CreateCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/CreateCmsPage.php:7
* @route '/admin/cms-pages/create'
*/
CreateCmsPage.url = (options?: RouteQueryOptions) => {
    return CreateCmsPage.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\CreateCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/CreateCmsPage.php:7
* @route '/admin/cms-pages/create'
*/
CreateCmsPage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateCmsPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\CreateCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/CreateCmsPage.php:7
* @route '/admin/cms-pages/create'
*/
CreateCmsPage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateCmsPage.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\CreateCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/CreateCmsPage.php:7
* @route '/admin/cms-pages/create'
*/
const CreateCmsPageForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateCmsPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\CreateCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/CreateCmsPage.php:7
* @route '/admin/cms-pages/create'
*/
CreateCmsPageForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateCmsPage.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\CreateCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/CreateCmsPage.php:7
* @route '/admin/cms-pages/create'
*/
CreateCmsPageForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateCmsPage.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateCmsPage.form = CreateCmsPageForm

export default CreateCmsPage