import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/admin/estimates/{record}/edit'
*/
const EditEstimate = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditEstimate.url(args, options),
    method: 'get',
})

EditEstimate.definition = {
    methods: ["get","head"],
    url: '/admin/estimates/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/admin/estimates/{record}/edit'
*/
EditEstimate.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditEstimate.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/admin/estimates/{record}/edit'
*/
EditEstimate.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditEstimate.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/admin/estimates/{record}/edit'
*/
EditEstimate.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditEstimate.url(args, options),
    method: 'head',
})

/**
* @see \App\Filament\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/admin/estimates/{record}/edit'
*/
const EditEstimateForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditEstimate.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/admin/estimates/{record}/edit'
*/
EditEstimateForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditEstimate.url(args, options),
    method: 'get',
})

/**
* @see \App\Filament\Resources\EstimateResource\Pages\EditEstimate::__invoke
* @see app/Filament/Resources/EstimateResource/Pages/EditEstimate.php:7
* @route '/admin/estimates/{record}/edit'
*/
EditEstimateForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditEstimate.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditEstimate.form = EditEstimateForm

export default EditEstimate