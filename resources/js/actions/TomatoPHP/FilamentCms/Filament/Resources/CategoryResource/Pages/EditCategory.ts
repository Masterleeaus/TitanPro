import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\EditCategory::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/EditCategory.php:7
* @route '/titanpro/categories/{record}/edit'
*/
const EditCategory = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCategory.url(args, options),
    method: 'get',
})

EditCategory.definition = {
    methods: ["get","head"],
    url: '/titanpro/categories/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\EditCategory::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/EditCategory.php:7
* @route '/titanpro/categories/{record}/edit'
*/
EditCategory.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditCategory.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\EditCategory::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/EditCategory.php:7
* @route '/titanpro/categories/{record}/edit'
*/
EditCategory.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCategory.url(args, options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\EditCategory::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/EditCategory.php:7
* @route '/titanpro/categories/{record}/edit'
*/
EditCategory.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditCategory.url(args, options),
    method: 'head',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\EditCategory::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/EditCategory.php:7
* @route '/titanpro/categories/{record}/edit'
*/
const EditCategoryForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCategory.url(args, options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\EditCategory::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/EditCategory.php:7
* @route '/titanpro/categories/{record}/edit'
*/
EditCategoryForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCategory.url(args, options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\EditCategory::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/EditCategory.php:7
* @route '/titanpro/categories/{record}/edit'
*/
EditCategoryForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditCategory.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditCategory.form = EditCategoryForm

export default EditCategory