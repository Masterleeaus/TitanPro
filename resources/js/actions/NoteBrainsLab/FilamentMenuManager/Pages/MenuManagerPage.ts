import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \NoteBrainsLab\FilamentMenuManager\Pages\MenuManagerPage::__invoke
* @see vendor/notebrainslab/filament-menu-manager/src/Pages/MenuManagerPage.php:7
* @route '/titanpro/menu-manager-page'
*/
const MenuManagerPage = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MenuManagerPage.url(options),
    method: 'get',
})

MenuManagerPage.definition = {
    methods: ["get","head"],
    url: '/titanpro/menu-manager-page',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \NoteBrainsLab\FilamentMenuManager\Pages\MenuManagerPage::__invoke
* @see vendor/notebrainslab/filament-menu-manager/src/Pages/MenuManagerPage.php:7
* @route '/titanpro/menu-manager-page'
*/
MenuManagerPage.url = (options?: RouteQueryOptions) => {
    return MenuManagerPage.definition.url + queryParams(options)
}

/**
* @see \NoteBrainsLab\FilamentMenuManager\Pages\MenuManagerPage::__invoke
* @see vendor/notebrainslab/filament-menu-manager/src/Pages/MenuManagerPage.php:7
* @route '/titanpro/menu-manager-page'
*/
MenuManagerPage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MenuManagerPage.url(options),
    method: 'get',
})

/**
* @see \NoteBrainsLab\FilamentMenuManager\Pages\MenuManagerPage::__invoke
* @see vendor/notebrainslab/filament-menu-manager/src/Pages/MenuManagerPage.php:7
* @route '/titanpro/menu-manager-page'
*/
MenuManagerPage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: MenuManagerPage.url(options),
    method: 'head',
})

/**
* @see \NoteBrainsLab\FilamentMenuManager\Pages\MenuManagerPage::__invoke
* @see vendor/notebrainslab/filament-menu-manager/src/Pages/MenuManagerPage.php:7
* @route '/titanpro/menu-manager-page'
*/
const MenuManagerPageForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MenuManagerPage.url(options),
    method: 'get',
})

/**
* @see \NoteBrainsLab\FilamentMenuManager\Pages\MenuManagerPage::__invoke
* @see vendor/notebrainslab/filament-menu-manager/src/Pages/MenuManagerPage.php:7
* @route '/titanpro/menu-manager-page'
*/
MenuManagerPageForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MenuManagerPage.url(options),
    method: 'get',
})

/**
* @see \NoteBrainsLab\FilamentMenuManager\Pages\MenuManagerPage::__invoke
* @see vendor/notebrainslab/filament-menu-manager/src/Pages/MenuManagerPage.php:7
* @route '/titanpro/menu-manager-page'
*/
MenuManagerPageForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MenuManagerPage.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

MenuManagerPage.form = MenuManagerPageForm

export default MenuManagerPage