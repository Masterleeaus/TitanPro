import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\CreateCategory::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/CreateCategory.php:7
* @route '/titanpro/categories/create'
*/
const CreateCategory = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateCategory.url(options),
    method: 'get',
})

CreateCategory.definition = {
    methods: ["get","head"],
    url: '/titanpro/categories/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\CreateCategory::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/CreateCategory.php:7
* @route '/titanpro/categories/create'
*/
CreateCategory.url = (options?: RouteQueryOptions) => {
    return CreateCategory.definition.url + queryParams(options)
}

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\CreateCategory::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/CreateCategory.php:7
* @route '/titanpro/categories/create'
*/
CreateCategory.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateCategory.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\CreateCategory::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/CreateCategory.php:7
* @route '/titanpro/categories/create'
*/
CreateCategory.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateCategory.url(options),
    method: 'head',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\CreateCategory::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/CreateCategory.php:7
* @route '/titanpro/categories/create'
*/
const CreateCategoryForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateCategory.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\CreateCategory::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/CreateCategory.php:7
* @route '/titanpro/categories/create'
*/
CreateCategoryForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateCategory.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\CreateCategory::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/CreateCategory.php:7
* @route '/titanpro/categories/create'
*/
CreateCategoryForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateCategory.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateCategory.form = CreateCategoryForm

export default CreateCategory