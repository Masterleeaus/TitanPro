import DashboardController from './DashboardController'
import ModuleAdminDashboardController from './ModuleAdminDashboardController'
import ModuleAuditLogController from './ModuleAuditLogController'
import TitanModuleAdminApiController from './TitanModuleAdminApiController'
import ThemeImportController from './ThemeImportController'

const Platform = {
    DashboardController: Object.assign(DashboardController, DashboardController),
    ModuleAdminDashboardController: Object.assign(ModuleAdminDashboardController, ModuleAdminDashboardController),
    ModuleAuditLogController: Object.assign(ModuleAuditLogController, ModuleAuditLogController),
    TitanModuleAdminApiController: Object.assign(TitanModuleAdminApiController, TitanModuleAdminApiController),
    ThemeImportController: Object.assign(ThemeImportController, ThemeImportController),
}

export default Platform