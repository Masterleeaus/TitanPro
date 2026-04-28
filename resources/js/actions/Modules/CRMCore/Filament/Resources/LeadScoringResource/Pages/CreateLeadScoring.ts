import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\CreateLeadScoring::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/CreateLeadScoring.php:7
* @route '/admin/lead-scorings/create'
*/
const CreateLeadScoring = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLeadScoring.url(options),
    method: 'get',
})

CreateLeadScoring.definition = {
    methods: ["get","head"],
    url: '/admin/lead-scorings/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\CreateLeadScoring::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/CreateLeadScoring.php:7
* @route '/admin/lead-scorings/create'
*/
CreateLeadScoring.url = (options?: RouteQueryOptions) => {
    return CreateLeadScoring.definition.url + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\CreateLeadScoring::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/CreateLeadScoring.php:7
* @route '/admin/lead-scorings/create'
*/
CreateLeadScoring.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLeadScoring.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\CreateLeadScoring::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/CreateLeadScoring.php:7
* @route '/admin/lead-scorings/create'
*/
CreateLeadScoring.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateLeadScoring.url(options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\CreateLeadScoring::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/CreateLeadScoring.php:7
* @route '/admin/lead-scorings/create'
*/
const CreateLeadScoringForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLeadScoring.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\CreateLeadScoring::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/CreateLeadScoring.php:7
* @route '/admin/lead-scorings/create'
*/
CreateLeadScoringForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLeadScoring.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\CreateLeadScoring::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/CreateLeadScoring.php:7
* @route '/admin/lead-scorings/create'
*/
CreateLeadScoringForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateLeadScoring.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateLeadScoring.form = CreateLeadScoringForm

export default CreateLeadScoring