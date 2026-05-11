import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\ListLeadScorings::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/ListLeadScorings.php:7
* @route '/admin/lead-scorings'
*/
const ListLeadScorings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLeadScorings.url(options),
    method: 'get',
})

ListLeadScorings.definition = {
    methods: ["get","head"],
    url: '/admin/lead-scorings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\ListLeadScorings::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/ListLeadScorings.php:7
* @route '/admin/lead-scorings'
*/
ListLeadScorings.url = (options?: RouteQueryOptions) => {
    return ListLeadScorings.definition.url + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\ListLeadScorings::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/ListLeadScorings.php:7
* @route '/admin/lead-scorings'
*/
ListLeadScorings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLeadScorings.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\ListLeadScorings::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/ListLeadScorings.php:7
* @route '/admin/lead-scorings'
*/
ListLeadScorings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListLeadScorings.url(options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\ListLeadScorings::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/ListLeadScorings.php:7
* @route '/admin/lead-scorings'
*/
const ListLeadScoringsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLeadScorings.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\ListLeadScorings::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/ListLeadScorings.php:7
* @route '/admin/lead-scorings'
*/
ListLeadScoringsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLeadScorings.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages\ListLeadScorings::__invoke
* @see Modules/CRMCore/Filament/Resources/LeadScoringResource/Pages/ListLeadScorings.php:7
* @route '/admin/lead-scorings'
*/
ListLeadScoringsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListLeadScorings.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListLeadScorings.form = ListLeadScoringsForm

export default ListLeadScorings