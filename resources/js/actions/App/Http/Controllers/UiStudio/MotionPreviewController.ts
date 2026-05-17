import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\UiStudio\MotionPreviewController::__invoke
* @see app/Http/Controllers/UiStudio/MotionPreviewController.php:16
* @route '/titan-ui-studio/motion-preview'
*/
const MotionPreviewController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MotionPreviewController.url(options),
    method: 'get',
})

MotionPreviewController.definition = {
    methods: ["get","head"],
    url: '/titan-ui-studio/motion-preview',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\UiStudio\MotionPreviewController::__invoke
* @see app/Http/Controllers/UiStudio/MotionPreviewController.php:16
* @route '/titan-ui-studio/motion-preview'
*/
MotionPreviewController.url = (options?: RouteQueryOptions) => {
    return MotionPreviewController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\UiStudio\MotionPreviewController::__invoke
* @see app/Http/Controllers/UiStudio/MotionPreviewController.php:16
* @route '/titan-ui-studio/motion-preview'
*/
MotionPreviewController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MotionPreviewController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UiStudio\MotionPreviewController::__invoke
* @see app/Http/Controllers/UiStudio/MotionPreviewController.php:16
* @route '/titan-ui-studio/motion-preview'
*/
MotionPreviewController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: MotionPreviewController.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\UiStudio\MotionPreviewController::__invoke
* @see app/Http/Controllers/UiStudio/MotionPreviewController.php:16
* @route '/titan-ui-studio/motion-preview'
*/
const MotionPreviewControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MotionPreviewController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UiStudio\MotionPreviewController::__invoke
* @see app/Http/Controllers/UiStudio/MotionPreviewController.php:16
* @route '/titan-ui-studio/motion-preview'
*/
MotionPreviewControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MotionPreviewController.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UiStudio\MotionPreviewController::__invoke
* @see app/Http/Controllers/UiStudio/MotionPreviewController.php:16
* @route '/titan-ui-studio/motion-preview'
*/
MotionPreviewControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MotionPreviewController.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

MotionPreviewController.form = MotionPreviewControllerForm

export default MotionPreviewController