<?php

return [
    App\Providers\AppServiceProvider::class,
    // Titan platform providers — boot order matters:
    // 1. Module layer first (registry must exist before AI / security resolve it)
    App\Providers\TitanModuleServiceProvider::class,
    App\Providers\AutomationEngineServiceProvider::class,
    App\Providers\TitanBlueprintServiceProvider::class,
    // 2. AI and security layers depend on the module registry being available
    App\Providers\TitanAiRuntimeServiceProvider::class,
    App\Providers\TitanModelRuntimeServiceProvider::class,
    App\Providers\TitanModuleSecurityServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\Filament\TitanProPanelProvider::class,
    App\Providers\Filament\GroundZeroPanelProvider::class,
    App\Providers\Filament\TitanQuotesPanelProvider::class,
    App\Providers\Filament\ZeroPayPanelProvider::class,
    App\Providers\Filament\TitanGoPanelProvider::class,
    App\Providers\Filament\ZeroFussPanelProvider::class,
    App\Providers\Filament\TitanSoloPanelProvider::class,
    App\Providers\Filament\TitanStudioPanelProvider::class,
    App\Providers\Filament\TitanNexusPanelProvider::class,
    App\Providers\Filament\TitanZeroPanelProvider::class,
    App\Providers\Filament\TitanEchoPanelProvider::class,
    App\Providers\Filament\TitanLockerPanelProvider::class,
    App\Providers\Filament\TitanMoneyPanelProvider::class,
    App\Providers\Filament\TitanPixelPanelProvider::class,
    App\Providers\Filament\TitanSocialPanelProvider::class,
    App\Providers\Filament\TitanTeamPanelProvider::class,
    App\Providers\Filament\ZeroIssuesPanelProvider::class,
    App\Providers\Filament\QuoteAssistantPanelProvider::class,
    App\Providers\Filament\TitanBookingsPanelProvider::class,
    App\Providers\Filament\BookingsAssistantPanelProvider::class,
    App\Providers\Filament\SiteInspectorAssistantPanelProvider::class,
    App\Providers\Filament\OnTheJobTrainingAssistantPanelProvider::class,
    App\Providers\Filament\ChemicalsAssistantPanelProvider::class,
    App\Providers\Filament\TitanPayrollPanelProvider::class,
    App\Providers\Filament\BudgetingPanelProvider::class,
    App\Providers\Filament\TitanAssetsAndEquipmentPanelProvider::class,
    App\Providers\Filament\TitanFleetPanelProvider::class,
    App\Providers\Filament\TitanSuppliersAndInventoryPanelProvider::class,
    App\Providers\Filament\TrainingPanelProvider::class,
    App\Providers\Filament\QualityAuditsPanelProvider::class,
    App\Providers\Filament\DocsAndContractsPanelProvider::class,
    App\Providers\Filament\FacilityManagementPanelProvider::class,
    App\Providers\Filament\ComplianceAndSafetyPanelProvider::class,
    App\Providers\Filament\FeedbackAndComplaintsPanelProvider::class,
    App\Providers\Filament\TitanWebsitesPanelProvider::class,
    App\Providers\Filament\SeoPanelProvider::class,
    App\Providers\Filament\CustomerMarketingPanelProvider::class,
    App\Providers\Filament\IntegrationsPanelProvider::class,
    App\Providers\Filament\RevenueForecastPanelProvider::class,
    App\Providers\Filament\JobProfitabilityAnalyzerPanelProvider::class,
    App\Providers\Filament\CrewOptimizerPanelProvider::class,
    App\Providers\Filament\RouteAndDispatchAiPanelProvider::class,
    App\Providers\Filament\ComplianceAssistantPanelProvider::class,
    App\Providers\Filament\SafetyAuditBotPanelProvider::class,
    App\Providers\Filament\LeadScorerPanelProvider::class,
    App\Providers\Filament\ChurnEarlyWarningPanelProvider::class,
    App\Providers\Filament\KnowledgeBaseBuilderPanelProvider::class,
    App\Providers\Filament\SkillGapAnalyzerPanelProvider::class,
    App\Providers\Filament\CashFlowForecasterPanelProvider::class,
    App\Providers\Filament\LaborCostTrackerPanelProvider::class,
    App\Providers\FortifyServiceProvider::class,
    // Guard module providers that may not exist in this build.  When the
    // CRMCore module is absent, this provider will not be registered to avoid
    // fatal errors.  Additional modules can be guarded in the same way.
    ...(class_exists(\Modules\CRMCore\Providers\ModuleServiceProvider::class)
        ? [\Modules\CRMCore\Providers\ModuleServiceProvider::class]
        : []),
    // Dispatch module — guard-wrapped so absent builds don't fatal.
    ...(class_exists(\Modules\Dispatch\Providers\ModuleServiceProvider::class)
        ? [
            \Modules\Dispatch\Providers\ModuleServiceProvider::class,
            \Modules\Dispatch\Providers\RouteServiceProvider::class,
            \Modules\Dispatch\Providers\EventServiceProvider::class,
            \Modules\Dispatch\Providers\FilamentServiceProvider::class,
        ]
        : []),
    ...(class_exists(\Modules\TitanCommand\Providers\TitanCommandServiceProvider::class)
        ? [\Modules\TitanCommand\Providers\TitanCommandServiceProvider::class]
        : []),
    // Only registered when Telescope is installed (dev environments only)
    ...(class_exists(\Laravel\Telescope\TelescopeApplicationServiceProvider::class)
        ? [App\Providers\TelescopeServiceProvider::class]
        : []),
];
