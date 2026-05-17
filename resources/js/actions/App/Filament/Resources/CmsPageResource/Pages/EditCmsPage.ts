import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/titanpro/cms-pages/{record}/edit'
*/
const EditCmsPageda76459b49ec617760e42f5f88c2e6b6 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCmsPageda76459b49ec617760e42f5f88c2e6b6.url(args, options),
    method: 'get',
})

EditCmsPageda76459b49ec617760e42f5f88c2e6b6.definition = {
    methods: ["get","head"],
    url: '/titanpro/cms-pages/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/titanpro/cms-pages/{record}/edit'
*/
EditCmsPageda76459b49ec617760e42f5f88c2e6b6.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditCmsPageda76459b49ec617760e42f5f88c2e6b6.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/titanpro/cms-pages/{record}/edit'
*/
EditCmsPageda76459b49ec617760e42f5f88c2e6b6.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCmsPageda76459b49ec617760e42f5f88c2e6b6.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/titanpro/cms-pages/{record}/edit'
*/
EditCmsPageda76459b49ec617760e42f5f88c2e6b6.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditCmsPageda76459b49ec617760e42f5f88c2e6b6.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/titanpro/cms-pages/{record}/edit'
*/
const EditCmsPageda76459b49ec617760e42f5f88c2e6b6Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCmsPageda76459b49ec617760e42f5f88c2e6b6.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/titanpro/cms-pages/{record}/edit'
*/
EditCmsPageda76459b49ec617760e42f5f88c2e6b6Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCmsPageda76459b49ec617760e42f5f88c2e6b6.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/titanpro/cms-pages/{record}/edit'
*/
EditCmsPageda76459b49ec617760e42f5f88c2e6b6Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCmsPageda76459b49ec617760e42f5f88c2e6b6.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditCmsPageda76459b49ec617760e42f5f88c2e6b6.form = EditCmsPageda76459b49ec617760e42f5f88c2e6b6Form
/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/titanstudio/cms-pages/{record}/edit'
*/
const EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006 = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006.url(args, options),
    method: 'get',
})

EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006.definition = {
    methods: ["get","head"],
    url: '/titanstudio/cms-pages/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/titanstudio/cms-pages/{record}/edit'
*/
EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/titanstudio/cms-pages/{record}/edit'
*/
EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/titanstudio/cms-pages/{record}/edit'
*/
EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/titanstudio/cms-pages/{record}/edit'
*/
const EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006Form = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/titanstudio/cms-pages/{record}/edit'
*/
EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006Form.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\CmsPageResource\Pages\EditCmsPage::__invoke
* @see app/Filament/Resources/CmsPageResource/Pages/EditCmsPage.php:7
* @route '/titanstudio/cms-pages/{record}/edit'
*/
EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006Form.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006.form = EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006Form

const EditCmsPage = {
    '/titanpro/cms-pages/{record}/edit': EditCmsPageda76459b49ec617760e42f5f88c2e6b6,
    '/titanstudio/cms-pages/{record}/edit': EditCmsPage3a66f7435512eb0f1daeeeb6af1c1006,
}

export default EditCmsPage