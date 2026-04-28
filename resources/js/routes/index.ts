import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../wayfinder'
/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::login
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:19
* @route '/login'
*/
export const login = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})

login.definition = {
    methods: ["get","head"],
    url: '/login',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::login
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:19
* @route '/login'
*/
login.url = (options?: RouteQueryOptions) => {
    return login.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::login
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:19
* @route '/login'
*/
login.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::login
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:19
* @route '/login'
*/
login.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: login.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::login
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:19
* @route '/login'
*/
const loginForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: login.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::login
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:19
* @route '/login'
*/
loginForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: login.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::login
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:19
* @route '/login'
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
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::logout
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:52
* @route '/logout'
*/
export const logout = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

logout.definition = {
    methods: ["post"],
    url: '/logout',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::logout
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:52
* @route '/logout'
*/
logout.url = (options?: RouteQueryOptions) => {
    return logout.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::logout
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:52
* @route '/logout'
*/
logout.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::logout
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:52
* @route '/logout'
*/
const logoutForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: logout.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::logout
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:52
* @route '/logout'
*/
logoutForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: logout.url(options),
    method: 'post',
})

logout.form = logoutForm

/**
* @see \App\Http\Controllers\Auth\RegisteredUserController::register
* @see app/Http/Controllers/Auth/RegisteredUserController.php:22
* @route '/register'
*/
export const register = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

register.definition = {
    methods: ["get","head"],
    url: '/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\RegisteredUserController::register
* @see app/Http/Controllers/Auth/RegisteredUserController.php:22
* @route '/register'
*/
register.url = (options?: RouteQueryOptions) => {
    return register.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\RegisteredUserController::register
* @see app/Http/Controllers/Auth/RegisteredUserController.php:22
* @route '/register'
*/
register.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\RegisteredUserController::register
* @see app/Http/Controllers/Auth/RegisteredUserController.php:22
* @route '/register'
*/
register.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: register.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\RegisteredUserController::register
* @see app/Http/Controllers/Auth/RegisteredUserController.php:22
* @route '/register'
*/
const registerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: register.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\RegisteredUserController::register
* @see app/Http/Controllers/Auth/RegisteredUserController.php:22
* @route '/register'
*/
registerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: register.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\RegisteredUserController::register
* @see app/Http/Controllers/Auth/RegisteredUserController.php:22
* @route '/register'
*/
registerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: register.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

register.form = registerForm

/**
* @see vendor/pxlrbt/filament-excel/routes/web.php:6
* @route '/filament-excel/{path}'
*/
export const filamentExcelDownload = (args: { path: string | number } | [path: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: filamentExcelDownload.url(args, options),
    method: 'get',
})

filamentExcelDownload.definition = {
    methods: ["get","head"],
    url: '/filament-excel/{path}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see vendor/pxlrbt/filament-excel/routes/web.php:6
* @route '/filament-excel/{path}'
*/
filamentExcelDownload.url = (args: { path: string | number } | [path: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { path: args }
    }

    if (Array.isArray(args)) {
        args = {
            path: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        path: args.path,
    }

    return filamentExcelDownload.definition.url
            .replace('{path}', parsedArgs.path.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see vendor/pxlrbt/filament-excel/routes/web.php:6
* @route '/filament-excel/{path}'
*/
filamentExcelDownload.get = (args: { path: string | number } | [path: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: filamentExcelDownload.url(args, options),
    method: 'get',
})

/**
* @see vendor/pxlrbt/filament-excel/routes/web.php:6
* @route '/filament-excel/{path}'
*/
filamentExcelDownload.head = (args: { path: string | number } | [path: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: filamentExcelDownload.url(args, options),
    method: 'head',
})

/**
* @see vendor/pxlrbt/filament-excel/routes/web.php:6
* @route '/filament-excel/{path}'
*/
const filamentExcelDownloadForm = (args: { path: string | number } | [path: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: filamentExcelDownload.url(args, options),
    method: 'get',
})

/**
* @see vendor/pxlrbt/filament-excel/routes/web.php:6
* @route '/filament-excel/{path}'
*/
filamentExcelDownloadForm.get = (args: { path: string | number } | [path: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: filamentExcelDownload.url(args, options),
    method: 'get',
})

/**
* @see vendor/pxlrbt/filament-excel/routes/web.php:6
* @route '/filament-excel/{path}'
*/
filamentExcelDownloadForm.head = (args: { path: string | number } | [path: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: filamentExcelDownload.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

filamentExcelDownload.form = filamentExcelDownloadForm

/**
* @see routes/web.php:28
* @route '/'
*/
export const home = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: home.url(options),
    method: 'get',
})

home.definition = {
    methods: ["get","head"],
    url: '/',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:28
* @route '/'
*/
home.url = (options?: RouteQueryOptions) => {
    return home.definition.url + queryParams(options)
}

/**
* @see routes/web.php:28
* @route '/'
*/
home.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: home.url(options),
    method: 'get',
})

/**
* @see routes/web.php:28
* @route '/'
*/
home.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: home.url(options),
    method: 'head',
})

/**
* @see routes/web.php:28
* @route '/'
*/
const homeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: home.url(options),
    method: 'get',
})

/**
* @see routes/web.php:28
* @route '/'
*/
homeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: home.url(options),
    method: 'get',
})

/**
* @see routes/web.php:28
* @route '/'
*/
homeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: home.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

home.form = homeForm

/**
* @see routes/web.php:43
* @route '/dashboard'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:43
* @route '/dashboard'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see routes/web.php:43
* @route '/dashboard'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see routes/web.php:43
* @route '/dashboard'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see routes/web.php:43
* @route '/dashboard'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see routes/web.php:43
* @route '/dashboard'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see routes/web.php:43
* @route '/dashboard'
*/
dashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

dashboard.form = dashboardForm

/**
* @see \App\Http\Controllers\HealthController::health
* @see app/Http/Controllers/HealthController.php:16
* @route '/health'
*/
export const health = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: health.url(options),
    method: 'get',
})

health.definition = {
    methods: ["get","head"],
    url: '/health',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\HealthController::health
* @see app/Http/Controllers/HealthController.php:16
* @route '/health'
*/
health.url = (options?: RouteQueryOptions) => {
    return health.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\HealthController::health
* @see app/Http/Controllers/HealthController.php:16
* @route '/health'
*/
health.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: health.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\HealthController::health
* @see app/Http/Controllers/HealthController.php:16
* @route '/health'
*/
health.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: health.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\HealthController::health
* @see app/Http/Controllers/HealthController.php:16
* @route '/health'
*/
const healthForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: health.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\HealthController::health
* @see app/Http/Controllers/HealthController.php:16
* @route '/health'
*/
healthForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: health.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\HealthController::health
* @see app/Http/Controllers/HealthController.php:16
* @route '/health'
*/
healthForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: health.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

health.form = healthForm

/**
* @see routes/web.php:181
* @route '/pricing'
*/
export const pricing = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pricing.url(options),
    method: 'get',
})

pricing.definition = {
    methods: ["get","head"],
    url: '/pricing',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:181
* @route '/pricing'
*/
pricing.url = (options?: RouteQueryOptions) => {
    return pricing.definition.url + queryParams(options)
}

/**
* @see routes/web.php:181
* @route '/pricing'
*/
pricing.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pricing.url(options),
    method: 'get',
})

/**
* @see routes/web.php:181
* @route '/pricing'
*/
pricing.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: pricing.url(options),
    method: 'head',
})

/**
* @see routes/web.php:181
* @route '/pricing'
*/
const pricingForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: pricing.url(options),
    method: 'get',
})

/**
* @see routes/web.php:181
* @route '/pricing'
*/
pricingForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: pricing.url(options),
    method: 'get',
})

/**
* @see routes/web.php:181
* @route '/pricing'
*/
pricingForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: pricing.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

pricing.form = pricingForm

/**
* @see routes/web.php:182
* @route '/zero-philosophy'
*/
export const zeroPhilosophy = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: zeroPhilosophy.url(options),
    method: 'get',
})

zeroPhilosophy.definition = {
    methods: ["get","head"],
    url: '/zero-philosophy',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:182
* @route '/zero-philosophy'
*/
zeroPhilosophy.url = (options?: RouteQueryOptions) => {
    return zeroPhilosophy.definition.url + queryParams(options)
}

/**
* @see routes/web.php:182
* @route '/zero-philosophy'
*/
zeroPhilosophy.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: zeroPhilosophy.url(options),
    method: 'get',
})

/**
* @see routes/web.php:182
* @route '/zero-philosophy'
*/
zeroPhilosophy.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: zeroPhilosophy.url(options),
    method: 'head',
})

/**
* @see routes/web.php:182
* @route '/zero-philosophy'
*/
const zeroPhilosophyForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: zeroPhilosophy.url(options),
    method: 'get',
})

/**
* @see routes/web.php:182
* @route '/zero-philosophy'
*/
zeroPhilosophyForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: zeroPhilosophy.url(options),
    method: 'get',
})

/**
* @see routes/web.php:182
* @route '/zero-philosophy'
*/
zeroPhilosophyForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: zeroPhilosophy.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

zeroPhilosophy.form = zeroPhilosophyForm

/**
* @see routes/web.php:183
* @route '/zero'
*/
export const zero = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: zero.url(options),
    method: 'get',
})

zero.definition = {
    methods: ["get","head"],
    url: '/zero',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:183
* @route '/zero'
*/
zero.url = (options?: RouteQueryOptions) => {
    return zero.definition.url + queryParams(options)
}

/**
* @see routes/web.php:183
* @route '/zero'
*/
zero.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: zero.url(options),
    method: 'get',
})

/**
* @see routes/web.php:183
* @route '/zero'
*/
zero.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: zero.url(options),
    method: 'head',
})

/**
* @see routes/web.php:183
* @route '/zero'
*/
const zeroForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: zero.url(options),
    method: 'get',
})

/**
* @see routes/web.php:183
* @route '/zero'
*/
zeroForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: zero.url(options),
    method: 'get',
})

/**
* @see routes/web.php:183
* @route '/zero'
*/
zeroForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: zero.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

zero.form = zeroForm

/**
* @see routes/web.php:184
* @route '/security'
*/
export const security = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: security.url(options),
    method: 'get',
})

security.definition = {
    methods: ["get","head"],
    url: '/security',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:184
* @route '/security'
*/
security.url = (options?: RouteQueryOptions) => {
    return security.definition.url + queryParams(options)
}

/**
* @see routes/web.php:184
* @route '/security'
*/
security.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: security.url(options),
    method: 'get',
})

/**
* @see routes/web.php:184
* @route '/security'
*/
security.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: security.url(options),
    method: 'head',
})

/**
* @see routes/web.php:184
* @route '/security'
*/
const securityForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: security.url(options),
    method: 'get',
})

/**
* @see routes/web.php:184
* @route '/security'
*/
securityForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: security.url(options),
    method: 'get',
})

/**
* @see routes/web.php:184
* @route '/security'
*/
securityForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: security.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

security.form = securityForm

/**
* @see routes/web.php:185
* @route '/ai-strategy'
*/
export const aiStrategy = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: aiStrategy.url(options),
    method: 'get',
})

aiStrategy.definition = {
    methods: ["get","head"],
    url: '/ai-strategy',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:185
* @route '/ai-strategy'
*/
aiStrategy.url = (options?: RouteQueryOptions) => {
    return aiStrategy.definition.url + queryParams(options)
}

/**
* @see routes/web.php:185
* @route '/ai-strategy'
*/
aiStrategy.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: aiStrategy.url(options),
    method: 'get',
})

/**
* @see routes/web.php:185
* @route '/ai-strategy'
*/
aiStrategy.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: aiStrategy.url(options),
    method: 'head',
})

/**
* @see routes/web.php:185
* @route '/ai-strategy'
*/
const aiStrategyForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: aiStrategy.url(options),
    method: 'get',
})

/**
* @see routes/web.php:185
* @route '/ai-strategy'
*/
aiStrategyForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: aiStrategy.url(options),
    method: 'get',
})

/**
* @see routes/web.php:185
* @route '/ai-strategy'
*/
aiStrategyForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: aiStrategy.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

aiStrategy.form = aiStrategyForm

/**
* @see routes/web.php:186
* @route '/automation-engine'
*/
export const automationEngine = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: automationEngine.url(options),
    method: 'get',
})

automationEngine.definition = {
    methods: ["get","head"],
    url: '/automation-engine',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:186
* @route '/automation-engine'
*/
automationEngine.url = (options?: RouteQueryOptions) => {
    return automationEngine.definition.url + queryParams(options)
}

/**
* @see routes/web.php:186
* @route '/automation-engine'
*/
automationEngine.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: automationEngine.url(options),
    method: 'get',
})

/**
* @see routes/web.php:186
* @route '/automation-engine'
*/
automationEngine.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: automationEngine.url(options),
    method: 'head',
})

/**
* @see routes/web.php:186
* @route '/automation-engine'
*/
const automationEngineForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: automationEngine.url(options),
    method: 'get',
})

/**
* @see routes/web.php:186
* @route '/automation-engine'
*/
automationEngineForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: automationEngine.url(options),
    method: 'get',
})

/**
* @see routes/web.php:186
* @route '/automation-engine'
*/
automationEngineForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: automationEngine.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

automationEngine.form = automationEngineForm

/**
* @see routes/web.php:187
* @route '/how-it-works'
*/
export const howItWorks = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: howItWorks.url(options),
    method: 'get',
})

howItWorks.definition = {
    methods: ["get","head"],
    url: '/how-it-works',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:187
* @route '/how-it-works'
*/
howItWorks.url = (options?: RouteQueryOptions) => {
    return howItWorks.definition.url + queryParams(options)
}

/**
* @see routes/web.php:187
* @route '/how-it-works'
*/
howItWorks.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: howItWorks.url(options),
    method: 'get',
})

/**
* @see routes/web.php:187
* @route '/how-it-works'
*/
howItWorks.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: howItWorks.url(options),
    method: 'head',
})

/**
* @see routes/web.php:187
* @route '/how-it-works'
*/
const howItWorksForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: howItWorks.url(options),
    method: 'get',
})

/**
* @see routes/web.php:187
* @route '/how-it-works'
*/
howItWorksForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: howItWorks.url(options),
    method: 'get',
})

/**
* @see routes/web.php:187
* @route '/how-it-works'
*/
howItWorksForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: howItWorks.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

howItWorks.form = howItWorksForm

/**
* @see routes/web.php:188
* @route '/features'
*/
export const features = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: features.url(options),
    method: 'get',
})

features.definition = {
    methods: ["get","head"],
    url: '/features',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:188
* @route '/features'
*/
features.url = (options?: RouteQueryOptions) => {
    return features.definition.url + queryParams(options)
}

/**
* @see routes/web.php:188
* @route '/features'
*/
features.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: features.url(options),
    method: 'get',
})

/**
* @see routes/web.php:188
* @route '/features'
*/
features.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: features.url(options),
    method: 'head',
})

/**
* @see routes/web.php:188
* @route '/features'
*/
const featuresForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: features.url(options),
    method: 'get',
})

/**
* @see routes/web.php:188
* @route '/features'
*/
featuresForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: features.url(options),
    method: 'get',
})

/**
* @see routes/web.php:188
* @route '/features'
*/
featuresForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: features.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

features.form = featuresForm

/**
* @see routes/web.php:189
* @route '/faq'
*/
export const faq = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: faq.url(options),
    method: 'get',
})

faq.definition = {
    methods: ["get","head"],
    url: '/faq',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:189
* @route '/faq'
*/
faq.url = (options?: RouteQueryOptions) => {
    return faq.definition.url + queryParams(options)
}

/**
* @see routes/web.php:189
* @route '/faq'
*/
faq.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: faq.url(options),
    method: 'get',
})

/**
* @see routes/web.php:189
* @route '/faq'
*/
faq.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: faq.url(options),
    method: 'head',
})

/**
* @see routes/web.php:189
* @route '/faq'
*/
const faqForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: faq.url(options),
    method: 'get',
})

/**
* @see routes/web.php:189
* @route '/faq'
*/
faqForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: faq.url(options),
    method: 'get',
})

/**
* @see routes/web.php:189
* @route '/faq'
*/
faqForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: faq.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

faq.form = faqForm

/**
* @see routes/web.php:190
* @route '/about'
*/
export const about = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: about.url(options),
    method: 'get',
})

about.definition = {
    methods: ["get","head"],
    url: '/about',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:190
* @route '/about'
*/
about.url = (options?: RouteQueryOptions) => {
    return about.definition.url + queryParams(options)
}

/**
* @see routes/web.php:190
* @route '/about'
*/
about.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: about.url(options),
    method: 'get',
})

/**
* @see routes/web.php:190
* @route '/about'
*/
about.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: about.url(options),
    method: 'head',
})

/**
* @see routes/web.php:190
* @route '/about'
*/
const aboutForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: about.url(options),
    method: 'get',
})

/**
* @see routes/web.php:190
* @route '/about'
*/
aboutForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: about.url(options),
    method: 'get',
})

/**
* @see routes/web.php:190
* @route '/about'
*/
aboutForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: about.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

about.form = aboutForm

/**
* @see routes/web.php:191
* @route '/contact'
*/
export const contact = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: contact.url(options),
    method: 'get',
})

contact.definition = {
    methods: ["get","head"],
    url: '/contact',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:191
* @route '/contact'
*/
contact.url = (options?: RouteQueryOptions) => {
    return contact.definition.url + queryParams(options)
}

/**
* @see routes/web.php:191
* @route '/contact'
*/
contact.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: contact.url(options),
    method: 'get',
})

/**
* @see routes/web.php:191
* @route '/contact'
*/
contact.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: contact.url(options),
    method: 'head',
})

/**
* @see routes/web.php:191
* @route '/contact'
*/
const contactForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: contact.url(options),
    method: 'get',
})

/**
* @see routes/web.php:191
* @route '/contact'
*/
contactForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: contact.url(options),
    method: 'get',
})

/**
* @see routes/web.php:191
* @route '/contact'
*/
contactForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: contact.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

contact.form = contactForm

/**
* @see routes/web.php:192
* @route '/zeropay'
*/
export const zeropay = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: zeropay.url(options),
    method: 'get',
})

zeropay.definition = {
    methods: ["get","head"],
    url: '/zeropay',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:192
* @route '/zeropay'
*/
zeropay.url = (options?: RouteQueryOptions) => {
    return zeropay.definition.url + queryParams(options)
}

/**
* @see routes/web.php:192
* @route '/zeropay'
*/
zeropay.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: zeropay.url(options),
    method: 'get',
})

/**
* @see routes/web.php:192
* @route '/zeropay'
*/
zeropay.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: zeropay.url(options),
    method: 'head',
})

/**
* @see routes/web.php:192
* @route '/zeropay'
*/
const zeropayForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: zeropay.url(options),
    method: 'get',
})

/**
* @see routes/web.php:192
* @route '/zeropay'
*/
zeropayForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: zeropay.url(options),
    method: 'get',
})

/**
* @see routes/web.php:192
* @route '/zeropay'
*/
zeropayForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: zeropay.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

zeropay.form = zeropayForm
