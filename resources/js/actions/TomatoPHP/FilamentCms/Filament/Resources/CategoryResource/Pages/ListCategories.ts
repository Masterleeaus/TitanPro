import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\ListCategories::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/ListCategories.php:7
* @route '/titanpro/categories'
*/
const ListCategories = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCategories.url(options),
    method: 'get',
})

ListCategories.definition = {
    methods: ["get","head"],
    url: '/titanpro/categories',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\ListCategories::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/ListCategories.php:7
* @route '/titanpro/categories'
*/
ListCategories.url = (options?: RouteQueryOptions) => {
    return ListCategories.definition.url + queryParams(options)
}

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\ListCategories::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/ListCategories.php:7
* @route '/titanpro/categories'
*/
ListCategories.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCategories.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\ListCategories::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/ListCategories.php:7
* @route '/titanpro/categories'
*/
ListCategories.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListCategories.url(options),
    method: 'head',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\ListCategories::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/ListCategories.php:7
* @route '/titanpro/categories'
*/
const ListCategoriesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCategories.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\ListCategories::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/ListCategories.php:7
* @route '/titanpro/categories'
*/
ListCategoriesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCategories.url(options),
    method: 'get',
})

/**
* @see \TomatoPHP\FilamentCms\Filament\Resources\CategoryResource\Pages\ListCategories::__invoke
* @see vendor/tomatophp/filament-cms/src/Filament/Resources/CategoryResource/Pages/ListCategories.php:7
* @route '/titanpro/categories'
*/
ListCategoriesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCategories.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListCategories.form = ListCategoriesForm

export default ListCategories