import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \Filament\Auth\Pages\Login::__invoke
* @see vendor/filament/filament/src/Auth/Pages/Login.php:7
* @route '/titanpro/login'
*/
export const login = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})

login.definition = {
    methods: ["get","head"],
    url: '/titanpro/login',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Auth\Pages\Login::__invoke
* @see vendor/filament/filament/src/Auth/Pages/Login.php:7
* @route '/titanpro/login'
*/
login.url = (options?: RouteQueryOptions) => {
    return login.definition.url + queryParams(options)
}

/**
* @see \Filament\Auth\Pages\Login::__invoke
* @see vendor/filament/filament/src/Auth/Pages/Login.php:7
* @route '/titanpro/login'
*/
login.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})

/**
* @see \Filament\Auth\Pages\Login::__invoke
* @see vendor/filament/filament/src/Auth/Pages/Login.php:7
* @route '/titanpro/login'
*/
login.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: login.url(options),
    method: 'head',
})

/**
* @see \Filament\Auth\Pages\Login::__invoke
* @see vendor/filament/filament/src/Auth/Pages/Login.php:7
* @route '/titanpro/login'
*/
const loginForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: login.url(options),
    method: 'get',
})

/**
* @see \Filament\Auth\Pages\Login::__invoke
* @see vendor/filament/filament/src/Auth/Pages/Login.php:7
* @route '/titanpro/login'
*/
loginForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: login.url(options),
    method: 'get',
})

/**
* @see \Filament\Auth\Pages\Login::__invoke
* @see vendor/filament/filament/src/Auth/Pages/Login.php:7
* @route '/titanpro/login'
*/
loginForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: login.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

login.form = loginForm

/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
* @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
* @route '/titanpro/logout'
*/
export const logout = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

logout.definition = {
    methods: ["post"],
    url: '/titanpro/logout',
} satisfies RouteDefinition<["post"]>

/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
* @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
* @route '/titanpro/logout'
*/
logout.url = (options?: RouteQueryOptions) => {
    return logout.definition.url + queryParams(options)
}

/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
* @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
* @route '/titanpro/logout'
*/
logout.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
* @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
* @route '/titanpro/logout'
*/
const logoutForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: logout.url(options),
    method: 'post',
})

/**
* @see \Filament\Auth\Http\Controllers\LogoutController::__invoke
* @see vendor/filament/filament/src/Auth/Http/Controllers/LogoutController.php:10
* @route '/titanpro/logout'
*/
logoutForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: logout.url(options),
    method: 'post',
})

logout.form = logoutForm

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\TwoFactorPage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/TwoFactorPage.php:7
* @route '/titanpro/two-factor-authentication'
*/
export const twoFactor = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: twoFactor.url(options),
    method: 'get',
})

twoFactor.definition = {
    methods: ["get","head"],
    url: '/titanpro/two-factor-authentication',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\TwoFactorPage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/TwoFactorPage.php:7
* @route '/titanpro/two-factor-authentication'
*/
twoFactor.url = (options?: RouteQueryOptions) => {
    return twoFactor.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\TwoFactorPage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/TwoFactorPage.php:7
* @route '/titanpro/two-factor-authentication'
*/
twoFactor.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: twoFactor.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\TwoFactorPage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/TwoFactorPage.php:7
* @route '/titanpro/two-factor-authentication'
*/
twoFactor.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: twoFactor.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\TwoFactorPage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/TwoFactorPage.php:7
* @route '/titanpro/two-factor-authentication'
*/
const twoFactorForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: twoFactor.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\TwoFactorPage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/TwoFactorPage.php:7
* @route '/titanpro/two-factor-authentication'
*/
twoFactorForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: twoFactor.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\TwoFactorPage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/TwoFactorPage.php:7
* @route '/titanpro/two-factor-authentication'
*/
twoFactorForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: twoFactor.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

twoFactor.form = twoFactorForm

const auth = {
    login: Object.assign(login, login),
    logout: Object.assign(logout, logout),
    twoFactor: Object.assign(twoFactor, twoFactor),
}

export default auth