import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titannexus'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/titannexus',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titannexus'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titannexus'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titannexus'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titannexus'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titannexus'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \Filament\Pages\Dashboard::__invoke
* @see vendor/filament/filament/src/Pages/Dashboard.php:7
* @route '/titannexus'
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
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titannexus/my-profile'
*/
export const myProfile = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: myProfile.url(options),
    method: 'get',
})

myProfile.definition = {
    methods: ["get","head"],
    url: '/titannexus/my-profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titannexus/my-profile'
*/
myProfile.url = (options?: RouteQueryOptions) => {
    return myProfile.definition.url + queryParams(options)
}

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titannexus/my-profile'
*/
myProfile.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titannexus/my-profile'
*/
myProfile.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: myProfile.url(options),
    method: 'head',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titannexus/my-profile'
*/
const myProfileForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titannexus/my-profile'
*/
myProfileForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: myProfile.url(options),
    method: 'get',
})

/**
* @see \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::__invoke
* @see vendor/jeffgreco13/filament-breezy/src/Pages/MyProfilePage.php:7
* @route '/titannexus/my-profile'
*/
myProfileForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: myProfile.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

myProfile.form = myProfileForm

/**
* @see \App\Filament\TitanNexus\Pages\LeadPipeline::__invoke
* @see app/Filament/TitanNexus/Pages/LeadPipeline.php:7
* @route '/titannexus/lead-pipeline'
*/
export const leadPipeline = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: leadPipeline.url(options),
    method: 'get',
})

leadPipeline.definition = {
    methods: ["get","head"],
    url: '/titannexus/lead-pipeline',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Pages\LeadPipeline::__invoke
* @see app/Filament/TitanNexus/Pages/LeadPipeline.php:7
* @route '/titannexus/lead-pipeline'
*/
leadPipeline.url = (options?: RouteQueryOptions) => {
    return leadPipeline.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Pages\LeadPipeline::__invoke
* @see app/Filament/TitanNexus/Pages/LeadPipeline.php:7
* @route '/titannexus/lead-pipeline'
*/
leadPipeline.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: leadPipeline.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\LeadPipeline::__invoke
* @see app/Filament/TitanNexus/Pages/LeadPipeline.php:7
* @route '/titannexus/lead-pipeline'
*/
leadPipeline.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: leadPipeline.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Pages\LeadPipeline::__invoke
* @see app/Filament/TitanNexus/Pages/LeadPipeline.php:7
* @route '/titannexus/lead-pipeline'
*/
const leadPipelineForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: leadPipeline.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\LeadPipeline::__invoke
* @see app/Filament/TitanNexus/Pages/LeadPipeline.php:7
* @route '/titannexus/lead-pipeline'
*/
leadPipelineForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: leadPipeline.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\LeadPipeline::__invoke
* @see app/Filament/TitanNexus/Pages/LeadPipeline.php:7
* @route '/titannexus/lead-pipeline'
*/
leadPipelineForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: leadPipeline.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

leadPipeline.form = leadPipelineForm

/**
* @see \App\Filament\TitanNexus\Pages\MarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Pages/MarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
export const marketingCampaigns = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marketingCampaigns.url(options),
    method: 'get',
})

marketingCampaigns.definition = {
    methods: ["get","head"],
    url: '/titannexus/marketing-campaigns',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Pages\MarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Pages/MarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
marketingCampaigns.url = (options?: RouteQueryOptions) => {
    return marketingCampaigns.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Pages\MarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Pages/MarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
marketingCampaigns.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marketingCampaigns.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\MarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Pages/MarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
marketingCampaigns.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: marketingCampaigns.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Pages\MarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Pages/MarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
const marketingCampaignsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marketingCampaigns.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\MarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Pages/MarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
marketingCampaignsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marketingCampaigns.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\MarketingCampaigns::__invoke
* @see app/Filament/TitanNexus/Pages/MarketingCampaigns.php:7
* @route '/titannexus/marketing-campaigns'
*/
marketingCampaignsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marketingCampaigns.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

marketingCampaigns.form = marketingCampaignsForm

/**
* @see \App\Filament\TitanNexus\Pages\TrainingContent::__invoke
* @see app/Filament/TitanNexus/Pages/TrainingContent.php:7
* @route '/titannexus/training-content'
*/
export const trainingContent = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trainingContent.url(options),
    method: 'get',
})

trainingContent.definition = {
    methods: ["get","head"],
    url: '/titannexus/training-content',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Pages\TrainingContent::__invoke
* @see app/Filament/TitanNexus/Pages/TrainingContent.php:7
* @route '/titannexus/training-content'
*/
trainingContent.url = (options?: RouteQueryOptions) => {
    return trainingContent.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Pages\TrainingContent::__invoke
* @see app/Filament/TitanNexus/Pages/TrainingContent.php:7
* @route '/titannexus/training-content'
*/
trainingContent.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trainingContent.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\TrainingContent::__invoke
* @see app/Filament/TitanNexus/Pages/TrainingContent.php:7
* @route '/titannexus/training-content'
*/
trainingContent.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: trainingContent.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Pages\TrainingContent::__invoke
* @see app/Filament/TitanNexus/Pages/TrainingContent.php:7
* @route '/titannexus/training-content'
*/
const trainingContentForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: trainingContent.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\TrainingContent::__invoke
* @see app/Filament/TitanNexus/Pages/TrainingContent.php:7
* @route '/titannexus/training-content'
*/
trainingContentForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: trainingContent.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\TrainingContent::__invoke
* @see app/Filament/TitanNexus/Pages/TrainingContent.php:7
* @route '/titannexus/training-content'
*/
trainingContentForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: trainingContent.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

trainingContent.form = trainingContentForm

/**
* @see \App\Filament\TitanNexus\Pages\Verticals::__invoke
* @see app/Filament/TitanNexus/Pages/Verticals.php:7
* @route '/titannexus/verticals'
*/
export const verticals = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: verticals.url(options),
    method: 'get',
})

verticals.definition = {
    methods: ["get","head"],
    url: '/titannexus/verticals',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanNexus\Pages\Verticals::__invoke
* @see app/Filament/TitanNexus/Pages/Verticals.php:7
* @route '/titannexus/verticals'
*/
verticals.url = (options?: RouteQueryOptions) => {
    return verticals.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanNexus\Pages\Verticals::__invoke
* @see app/Filament/TitanNexus/Pages/Verticals.php:7
* @route '/titannexus/verticals'
*/
verticals.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: verticals.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\Verticals::__invoke
* @see app/Filament/TitanNexus/Pages/Verticals.php:7
* @route '/titannexus/verticals'
*/
verticals.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: verticals.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanNexus\Pages\Verticals::__invoke
* @see app/Filament/TitanNexus/Pages/Verticals.php:7
* @route '/titannexus/verticals'
*/
const verticalsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: verticals.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\Verticals::__invoke
* @see app/Filament/TitanNexus/Pages/Verticals.php:7
* @route '/titannexus/verticals'
*/
verticalsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: verticals.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanNexus\Pages\Verticals::__invoke
* @see app/Filament/TitanNexus/Pages/Verticals.php:7
* @route '/titannexus/verticals'
*/
verticalsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: verticals.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

verticals.form = verticalsForm

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titannexus/ui-studio'
*/
export const uiStudio = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: uiStudio.url(options),
    method: 'get',
})

uiStudio.definition = {
    methods: ["get","head"],
    url: '/titannexus/ui-studio',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titannexus/ui-studio'
*/
uiStudio.url = (options?: RouteQueryOptions) => {
    return uiStudio.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titannexus/ui-studio'
*/
uiStudio.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: uiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titannexus/ui-studio'
*/
uiStudio.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: uiStudio.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titannexus/ui-studio'
*/
const uiStudioForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: uiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titannexus/ui-studio'
*/
uiStudioForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: uiStudio.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\UiStudio::__invoke
* @see app/Filament/Pages/UiStudio.php:7
* @route '/titannexus/ui-studio'
*/
uiStudioForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: uiStudio.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

uiStudio.form = uiStudioForm

const pages = {
    dashboard: Object.assign(dashboard, dashboard),
    myProfile: Object.assign(myProfile, myProfile),
    leadPipeline: Object.assign(leadPipeline, leadPipeline),
    marketingCampaigns: Object.assign(marketingCampaigns, marketingCampaigns),
    trainingContent: Object.assign(trainingContent, trainingContent),
    verticals: Object.assign(verticals, verticals),
    uiStudio: Object.assign(uiStudio, uiStudio),
}

export default pages