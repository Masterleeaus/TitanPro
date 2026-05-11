import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/admin/cms-pages/{record}/edit'
*/
const EditCmsPage = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCmsPage.url(args, options),
    method: 'get',
})

EditCmsPage.definition = {
    methods: ["get","head"],
    url: '/admin/cms-pages/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/admin/cms-pages/{record}/edit'
*/
EditCmsPage.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditCmsPage.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/admin/cms-pages/{record}/edit'
*/
EditCmsPage.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCmsPage.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/admin/cms-pages/{record}/edit'
*/
EditCmsPage.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditCmsPage.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/admin/cms-pages/{record}/edit'
*/
const EditCmsPageForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCmsPage.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/admin/cms-pages/{record}/edit'
*/
EditCmsPageForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCmsPage.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/admin/cms-pages/{record}/edit'
*/
EditCmsPageForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCmsPage.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditCmsPage.form = EditCmsPageForm

export default EditCmsPage