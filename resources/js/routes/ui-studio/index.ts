import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\UiStudio\MotionPreviewController::__invoke
* @see app/Http/Controllers/UiStudio/MotionPreviewController.php:16
* @route '/titan-ui-studio/motion-preview'
*/
export const motionPreview = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motionPreview.url(options),
    method: 'get',
})

motionPreview.definition = {
    methods: ["get","head"],
    url: '/titan-ui-studio/motion-preview',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\UiStudio\MotionPreviewController::__invoke
* @see app/Http/Controllers/UiStudio/MotionPreviewController.php:16
* @route '/titan-ui-studio/motion-preview'
*/
motionPreview.url = (options?: RouteQueryOptions) => {
    return motionPreview.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\UiStudio\MotionPreviewController::__invoke
* @see app/Http/Controllers/UiStudio/MotionPreviewController.php:16
* @route '/titan-ui-studio/motion-preview'
*/
motionPreview.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motionPreview.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UiStudio\MotionPreviewController::__invoke
* @see app/Http/Controllers/UiStudio/MotionPreviewController.php:16
* @route '/titan-ui-studio/motion-preview'
*/
motionPreview.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: motionPreview.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\UiStudio\MotionPreviewController::__invoke
* @see app/Http/Controllers/UiStudio/MotionPreviewController.php:16
* @route '/titan-ui-studio/motion-preview'
*/
const motionPreviewForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motionPreview.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UiStudio\MotionPreviewController::__invoke
* @see app/Http/Controllers/UiStudio/MotionPreviewController.php:16
* @route '/titan-ui-studio/motion-preview'
*/
motionPreviewForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motionPreview.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UiStudio\MotionPreviewController::__invoke
* @see app/Http/Controllers/UiStudio/MotionPreviewController.php:16
* @route '/titan-ui-studio/motion-preview'
*/
motionPreviewForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motionPreview.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

motionPreview.form = motionPreviewForm

const uiStudio = {
    motionPreview: Object.assign(motionPreview, motionPreview),
}

export default uiStudio