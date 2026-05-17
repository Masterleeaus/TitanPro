import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanpro/my-profile'
*/
const MyProfilePagec73974b323bbfd0863a4a4ac9e38166a = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePagec73974b323bbfd0863a4a4ac9e38166a.url(options),
    method: 'get',
})

MyProfilePagec73974b323bbfd0863a4a4ac9e38166a.definition = {
    methods: ["get","head"],
    url: '/titanpro/my-profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanpro/my-profile'
*/
MyProfilePagec73974b323bbfd0863a4a4ac9e38166a.url = (options?: RouteQueryOptions) => {
    return MyProfilePagec73974b323bbfd0863a4a4ac9e38166a.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanpro/my-profile'
*/
MyProfilePagec73974b323bbfd0863a4a4ac9e38166a.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePagec73974b323bbfd0863a4a4ac9e38166a.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanpro/my-profile'
*/
MyProfilePagec73974b323bbfd0863a4a4ac9e38166a.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: MyProfilePagec73974b323bbfd0863a4a4ac9e38166a.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanpro/my-profile'
*/
const MyProfilePagec73974b323bbfd0863a4a4ac9e38166aForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePagec73974b323bbfd0863a4a4ac9e38166a.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanpro/my-profile'
*/
MyProfilePagec73974b323bbfd0863a4a4ac9e38166aForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePagec73974b323bbfd0863a4a4ac9e38166a.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanpro/my-profile'
*/
MyProfilePagec73974b323bbfd0863a4a4ac9e38166aForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePagec73974b323bbfd0863a4a4ac9e38166a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

MyProfilePagec73974b323bbfd0863a4a4ac9e38166a.form = MyProfilePagec73974b323bbfd0863a4a4ac9e38166aForm
/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/groundzero/my-profile'
*/
const MyProfilePagef3c3151f0d8d1ebef2d5824352b05dab = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePagef3c3151f0d8d1ebef2d5824352b05dab.url(options),
    method: 'get',
})

MyProfilePagef3c3151f0d8d1ebef2d5824352b05dab.definition = {
    methods: ["get","head"],
    url: '/groundzero/my-profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/groundzero/my-profile'
*/
MyProfilePagef3c3151f0d8d1ebef2d5824352b05dab.url = (options?: RouteQueryOptions) => {
    return MyProfilePagef3c3151f0d8d1ebef2d5824352b05dab.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/groundzero/my-profile'
*/
MyProfilePagef3c3151f0d8d1ebef2d5824352b05dab.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePagef3c3151f0d8d1ebef2d5824352b05dab.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/groundzero/my-profile'
*/
MyProfilePagef3c3151f0d8d1ebef2d5824352b05dab.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: MyProfilePagef3c3151f0d8d1ebef2d5824352b05dab.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/groundzero/my-profile'
*/
const MyProfilePagef3c3151f0d8d1ebef2d5824352b05dabForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePagef3c3151f0d8d1ebef2d5824352b05dab.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/groundzero/my-profile'
*/
MyProfilePagef3c3151f0d8d1ebef2d5824352b05dabForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePagef3c3151f0d8d1ebef2d5824352b05dab.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/groundzero/my-profile'
*/
MyProfilePagef3c3151f0d8d1ebef2d5824352b05dabForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePagef3c3151f0d8d1ebef2d5824352b05dab.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

MyProfilePagef3c3151f0d8d1ebef2d5824352b05dab.form = MyProfilePagef3c3151f0d8d1ebef2d5824352b05dabForm
/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanquotes/my-profile'
*/
const MyProfilePage66286618e1373c169cab556e29e413d8 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePage66286618e1373c169cab556e29e413d8.url(options),
    method: 'get',
})

MyProfilePage66286618e1373c169cab556e29e413d8.definition = {
    methods: ["get","head"],
    url: '/titanquotes/my-profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanquotes/my-profile'
*/
MyProfilePage66286618e1373c169cab556e29e413d8.url = (options?: RouteQueryOptions) => {
    return MyProfilePage66286618e1373c169cab556e29e413d8.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanquotes/my-profile'
*/
MyProfilePage66286618e1373c169cab556e29e413d8.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePage66286618e1373c169cab556e29e413d8.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanquotes/my-profile'
*/
MyProfilePage66286618e1373c169cab556e29e413d8.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: MyProfilePage66286618e1373c169cab556e29e413d8.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanquotes/my-profile'
*/
const MyProfilePage66286618e1373c169cab556e29e413d8Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage66286618e1373c169cab556e29e413d8.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanquotes/my-profile'
*/
MyProfilePage66286618e1373c169cab556e29e413d8Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage66286618e1373c169cab556e29e413d8.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanquotes/my-profile'
*/
MyProfilePage66286618e1373c169cab556e29e413d8Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage66286618e1373c169cab556e29e413d8.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

MyProfilePage66286618e1373c169cab556e29e413d8.form = MyProfilePage66286618e1373c169cab556e29e413d8Form
/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zeropay/my-profile'
*/
const MyProfilePage6153f17ed796d56625105176fc9aa00e = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePage6153f17ed796d56625105176fc9aa00e.url(options),
    method: 'get',
})

MyProfilePage6153f17ed796d56625105176fc9aa00e.definition = {
    methods: ["get","head"],
    url: '/zeropay/my-profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zeropay/my-profile'
*/
MyProfilePage6153f17ed796d56625105176fc9aa00e.url = (options?: RouteQueryOptions) => {
    return MyProfilePage6153f17ed796d56625105176fc9aa00e.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zeropay/my-profile'
*/
MyProfilePage6153f17ed796d56625105176fc9aa00e.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePage6153f17ed796d56625105176fc9aa00e.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zeropay/my-profile'
*/
MyProfilePage6153f17ed796d56625105176fc9aa00e.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: MyProfilePage6153f17ed796d56625105176fc9aa00e.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zeropay/my-profile'
*/
const MyProfilePage6153f17ed796d56625105176fc9aa00eForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage6153f17ed796d56625105176fc9aa00e.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zeropay/my-profile'
*/
MyProfilePage6153f17ed796d56625105176fc9aa00eForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage6153f17ed796d56625105176fc9aa00e.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zeropay/my-profile'
*/
MyProfilePage6153f17ed796d56625105176fc9aa00eForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage6153f17ed796d56625105176fc9aa00e.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

MyProfilePage6153f17ed796d56625105176fc9aa00e.form = MyProfilePage6153f17ed796d56625105176fc9aa00eForm
/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titango/my-profile'
*/
const MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141.url(options),
    method: 'get',
})

MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141.definition = {
    methods: ["get","head"],
    url: '/titango/my-profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titango/my-profile'
*/
MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141.url = (options?: RouteQueryOptions) => {
    return MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titango/my-profile'
*/
MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titango/my-profile'
*/
MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titango/my-profile'
*/
const MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titango/my-profile'
*/
MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titango/my-profile'
*/
MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141.form = MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141Form
/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zerofuss/my-profile'
*/
const MyProfilePage900d8574541a303f656fb38d1c219ae2 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePage900d8574541a303f656fb38d1c219ae2.url(options),
    method: 'get',
})

MyProfilePage900d8574541a303f656fb38d1c219ae2.definition = {
    methods: ["get","head"],
    url: '/zerofuss/my-profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zerofuss/my-profile'
*/
MyProfilePage900d8574541a303f656fb38d1c219ae2.url = (options?: RouteQueryOptions) => {
    return MyProfilePage900d8574541a303f656fb38d1c219ae2.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zerofuss/my-profile'
*/
MyProfilePage900d8574541a303f656fb38d1c219ae2.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePage900d8574541a303f656fb38d1c219ae2.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zerofuss/my-profile'
*/
MyProfilePage900d8574541a303f656fb38d1c219ae2.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: MyProfilePage900d8574541a303f656fb38d1c219ae2.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zerofuss/my-profile'
*/
const MyProfilePage900d8574541a303f656fb38d1c219ae2Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage900d8574541a303f656fb38d1c219ae2.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zerofuss/my-profile'
*/
MyProfilePage900d8574541a303f656fb38d1c219ae2Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage900d8574541a303f656fb38d1c219ae2.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/zerofuss/my-profile'
*/
MyProfilePage900d8574541a303f656fb38d1c219ae2Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage900d8574541a303f656fb38d1c219ae2.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

MyProfilePage900d8574541a303f656fb38d1c219ae2.form = MyProfilePage900d8574541a303f656fb38d1c219ae2Form
/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titansolo/my-profile'
*/
const MyProfilePage3247debf25fb353b1098db801a2a8194 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePage3247debf25fb353b1098db801a2a8194.url(options),
    method: 'get',
})

MyProfilePage3247debf25fb353b1098db801a2a8194.definition = {
    methods: ["get","head"],
    url: '/titansolo/my-profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titansolo/my-profile'
*/
MyProfilePage3247debf25fb353b1098db801a2a8194.url = (options?: RouteQueryOptions) => {
    return MyProfilePage3247debf25fb353b1098db801a2a8194.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titansolo/my-profile'
*/
MyProfilePage3247debf25fb353b1098db801a2a8194.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePage3247debf25fb353b1098db801a2a8194.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titansolo/my-profile'
*/
MyProfilePage3247debf25fb353b1098db801a2a8194.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: MyProfilePage3247debf25fb353b1098db801a2a8194.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titansolo/my-profile'
*/
const MyProfilePage3247debf25fb353b1098db801a2a8194Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage3247debf25fb353b1098db801a2a8194.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titansolo/my-profile'
*/
MyProfilePage3247debf25fb353b1098db801a2a8194Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage3247debf25fb353b1098db801a2a8194.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titansolo/my-profile'
*/
MyProfilePage3247debf25fb353b1098db801a2a8194Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage3247debf25fb353b1098db801a2a8194.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

MyProfilePage3247debf25fb353b1098db801a2a8194.form = MyProfilePage3247debf25fb353b1098db801a2a8194Form
/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanstudio/my-profile'
*/
const MyProfilePage9b67d515ed0f0d91942be701326f56e0 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePage9b67d515ed0f0d91942be701326f56e0.url(options),
    method: 'get',
})

MyProfilePage9b67d515ed0f0d91942be701326f56e0.definition = {
    methods: ["get","head"],
    url: '/titanstudio/my-profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanstudio/my-profile'
*/
MyProfilePage9b67d515ed0f0d91942be701326f56e0.url = (options?: RouteQueryOptions) => {
    return MyProfilePage9b67d515ed0f0d91942be701326f56e0.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanstudio/my-profile'
*/
MyProfilePage9b67d515ed0f0d91942be701326f56e0.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePage9b67d515ed0f0d91942be701326f56e0.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanstudio/my-profile'
*/
MyProfilePage9b67d515ed0f0d91942be701326f56e0.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: MyProfilePage9b67d515ed0f0d91942be701326f56e0.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanstudio/my-profile'
*/
const MyProfilePage9b67d515ed0f0d91942be701326f56e0Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage9b67d515ed0f0d91942be701326f56e0.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanstudio/my-profile'
*/
MyProfilePage9b67d515ed0f0d91942be701326f56e0Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage9b67d515ed0f0d91942be701326f56e0.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titanstudio/my-profile'
*/
MyProfilePage9b67d515ed0f0d91942be701326f56e0Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage9b67d515ed0f0d91942be701326f56e0.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

MyProfilePage9b67d515ed0f0d91942be701326f56e0.form = MyProfilePage9b67d515ed0f0d91942be701326f56e0Form
/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titannexus/my-profile'
*/
const MyProfilePage1d2c2bccd24d57bf69c379918c7a547a = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePage1d2c2bccd24d57bf69c379918c7a547a.url(options),
    method: 'get',
})

MyProfilePage1d2c2bccd24d57bf69c379918c7a547a.definition = {
    methods: ["get","head"],
    url: '/titannexus/my-profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titannexus/my-profile'
*/
MyProfilePage1d2c2bccd24d57bf69c379918c7a547a.url = (options?: RouteQueryOptions) => {
    return MyProfilePage1d2c2bccd24d57bf69c379918c7a547a.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titannexus/my-profile'
*/
MyProfilePage1d2c2bccd24d57bf69c379918c7a547a.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyProfilePage1d2c2bccd24d57bf69c379918c7a547a.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titannexus/my-profile'
*/
MyProfilePage1d2c2bccd24d57bf69c379918c7a547a.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: MyProfilePage1d2c2bccd24d57bf69c379918c7a547a.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titannexus/my-profile'
*/
const MyProfilePage1d2c2bccd24d57bf69c379918c7a547aForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage1d2c2bccd24d57bf69c379918c7a547a.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titannexus/my-profile'
*/
MyProfilePage1d2c2bccd24d57bf69c379918c7a547aForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage1d2c2bccd24d57bf69c379918c7a547a.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titannexus/my-profile'
*/
MyProfilePage1d2c2bccd24d57bf69c379918c7a547aForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MyProfilePage1d2c2bccd24d57bf69c379918c7a547a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

MyProfilePage1d2c2bccd24d57bf69c379918c7a547a.form = MyProfilePage1d2c2bccd24d57bf69c379918c7a547aForm

const MyProfilePage = {
    '/titanpro/my-profile': MyProfilePagec73974b323bbfd0863a4a4ac9e38166a,
    '/groundzero/my-profile': MyProfilePagef3c3151f0d8d1ebef2d5824352b05dab,
    '/titanquotes/my-profile': MyProfilePage66286618e1373c169cab556e29e413d8,
    '/zeropay/my-profile': MyProfilePage6153f17ed796d56625105176fc9aa00e,
    '/titango/my-profile': MyProfilePagebe6e37755a2c5c4a95a5dd8e5687b141,
    '/zerofuss/my-profile': MyProfilePage900d8574541a303f656fb38d1c219ae2,
    '/titansolo/my-profile': MyProfilePage3247debf25fb353b1098db801a2a8194,
    '/titanstudio/my-profile': MyProfilePage9b67d515ed0f0d91942be701326f56e0,
    '/titannexus/my-profile': MyProfilePage1d2c2bccd24d57bf69c379918c7a547a,
}

export default MyProfilePage