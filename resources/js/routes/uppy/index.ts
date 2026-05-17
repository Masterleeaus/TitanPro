import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \SpykApp\UppyUpload\Http\Controllers\UppyUploadController::upload
* @see vendor/spykapps/filament-uppy-upload/src/Http/Controllers/UppyUploadController.php:13
* @route '/uppy/upload'
*/
export const upload = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: upload.url(options),
    method: 'post',
})

upload.definition = {
    methods: ["post"],
    url: '/uppy/upload',
} satisfies RouteDefinition<["post"]>

/**
* @see \SpykApp\UppyUpload\Http\Controllers\UppyUploadController::upload
* @see vendor/spykapps/filament-uppy-upload/src/Http/Controllers/UppyUploadController.php:13
* @route '/uppy/upload'
*/
upload.url = (options?: RouteQueryOptions) => {
    return upload.definition.url + queryParams(options)
}

/**
* @see \SpykApp\UppyUpload\Http\Controllers\UppyUploadController::upload
* @see vendor/spykapps/filament-uppy-upload/src/Http/Controllers/UppyUploadController.php:13
* @route '/uppy/upload'
*/
upload.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: upload.url(options),
    method: 'post',
})

/**
* @see \SpykApp\UppyUpload\Http\Controllers\UppyUploadController::upload
* @see vendor/spykapps/filament-uppy-upload/src/Http/Controllers/UppyUploadController.php:13
* @route '/uppy/upload'
*/
const uploadForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: upload.url(options),
    method: 'post',
})

/**
* @see \SpykApp\UppyUpload\Http\Controllers\UppyUploadController::upload
* @see vendor/spykapps/filament-uppy-upload/src/Http/Controllers/UppyUploadController.php:13
* @route '/uppy/upload'
*/
uploadForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: upload.url(options),
    method: 'post',
})

upload.form = uploadForm

/**
* @see \SpykApp\UppyUpload\Http\Controllers\UppyUploadController::uploadSingle
* @see vendor/spykapps/filament-uppy-upload/src/Http/Controllers/UppyUploadController.php:129
* @route '/uppy/upload-single'
*/
export const uploadSingle = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: uploadSingle.url(options),
    method: 'post',
})

uploadSingle.definition = {
    methods: ["post"],
    url: '/uppy/upload-single',
} satisfies RouteDefinition<["post"]>

/**
* @see \SpykApp\UppyUpload\Http\Controllers\UppyUploadController::uploadSingle
* @see vendor/spykapps/filament-uppy-upload/src/Http/Controllers/UppyUploadController.php:129
* @route '/uppy/upload-single'
*/
uploadSingle.url = (options?: RouteQueryOptions) => {
    return uploadSingle.definition.url + queryParams(options)
}

/**
* @see \SpykApp\UppyUpload\Http\Controllers\UppyUploadController::uploadSingle
* @see vendor/spykapps/filament-uppy-upload/src/Http/Controllers/UppyUploadController.php:129
* @route '/uppy/upload-single'
*/
uploadSingle.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: uploadSingle.url(options),
    method: 'post',
})

/**
* @see \SpykApp\UppyUpload\Http\Controllers\UppyUploadController::uploadSingle
* @see vendor/spykapps/filament-uppy-upload/src/Http/Controllers/UppyUploadController.php:129
* @route '/uppy/upload-single'
*/
const uploadSingleForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: uploadSingle.url(options),
    method: 'post',
})

/**
* @see \SpykApp\UppyUpload\Http\Controllers\UppyUploadController::uploadSingle
* @see vendor/spykapps/filament-uppy-upload/src/Http/Controllers/UppyUploadController.php:129
* @route '/uppy/upload-single'
*/
uploadSingleForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: uploadSingle.url(options),
    method: 'post',
})

uploadSingle.form = uploadSingleForm

/**
* @see \SpykApp\UppyUpload\Http\Controllers\UppyUploadController::deleteMethod
* @see vendor/spykapps/filament-uppy-upload/src/Http/Controllers/UppyUploadController.php:165
* @route '/uppy/delete'
*/
export const deleteMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: deleteMethod.url(options),
    method: 'post',
})

deleteMethod.definition = {
    methods: ["post"],
    url: '/uppy/delete',
} satisfies RouteDefinition<["post"]>

/**
* @see \SpykApp\UppyUpload\Http\Controllers\UppyUploadController::deleteMethod
* @see vendor/spykapps/filament-uppy-upload/src/Http/Controllers/UppyUploadController.php:165
* @route '/uppy/delete'
*/
deleteMethod.url = (options?: RouteQueryOptions) => {
    return deleteMethod.definition.url + queryParams(options)
}

/**
* @see \SpykApp\UppyUpload\Http\Controllers\UppyUploadController::deleteMethod
* @see vendor/spykapps/filament-uppy-upload/src/Http/Controllers/UppyUploadController.php:165
* @route '/uppy/delete'
*/
deleteMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: deleteMethod.url(options),
    method: 'post',
})

/**
* @see \SpykApp\UppyUpload\Http\Controllers\UppyUploadController::deleteMethod
* @see vendor/spykapps/filament-uppy-upload/src/Http/Controllers/UppyUploadController.php:165
* @route '/uppy/delete'
*/
const deleteMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: deleteMethod.url(options),
    method: 'post',
})

/**
* @see \SpykApp\UppyUpload\Http\Controllers\UppyUploadController::deleteMethod
* @see vendor/spykapps/filament-uppy-upload/src/Http/Controllers/UppyUploadController.php:165
* @route '/uppy/delete'
*/
deleteMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: deleteMethod.url(options),
    method: 'post',
})

deleteMethod.form = deleteMethodForm

const uppy = {
    upload: Object.assign(upload, upload),
    uploadSingle: Object.assign(uploadSingle, uploadSingle),
    delete: Object.assign(deleteMethod, deleteMethod),
}

export default uppy