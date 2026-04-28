import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\EditLeadScoring::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/EditLeadScoring.php:7
* @route '/admin/lead-scorings/{record}/edit'
*/
const EditLeadScoring = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLeadScoring.url(args, options),
    method: 'get',
})

EditLeadScoring.definition = {
    methods: ["get","head"],
    url: '/admin/lead-scorings/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\EditLeadScoring::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/EditLeadScoring.php:7
* @route '/admin/lead-scorings/{record}/edit'
*/
EditLeadScoring.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditLeadScoring.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\EditLeadScoring::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/EditLeadScoring.php:7
* @route '/admin/lead-scorings/{record}/edit'
*/
EditLeadScoring.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLeadScoring.url(args, options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\EditLeadScoring::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/EditLeadScoring.php:7
* @route '/admin/lead-scorings/{record}/edit'
*/
EditLeadScoring.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditLeadScoring.url(args, options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\EditLeadScoring::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/EditLeadScoring.php:7
* @route '/admin/lead-scorings/{record}/edit'
*/
const EditLeadScoringForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLeadScoring.url(args, options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\EditLeadScoring::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/EditLeadScoring.php:7
* @route '/admin/lead-scorings/{record}/edit'
*/
EditLeadScoringForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLeadScoring.url(args, options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\EditLeadScoring::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/EditLeadScoring.php:7
* @route '/admin/lead-scorings/{record}/edit'
*/
EditLeadScoringForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditLeadScoring.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditLeadScoring.form = EditLeadScoringForm

export default EditLeadScoring