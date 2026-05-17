import SiteSettings from './SiteSettings'
import OperationsReports from './OperationsReports'
import Reports from './Reports'
import ThemeManager from './ThemeManager'
import UiStudio from './UiStudio'

const Pages = {
    SiteSettings: Object.assign(SiteSettings, SiteSettings),
    OperationsReports: Object.assign(OperationsReports, OperationsReports),
    Reports: Object.assign(Reports, Reports),
    ThemeManager: Object.assign(ThemeManager, ThemeManager),
    UiStudio: Object.assign(UiStudio, UiStudio),
}

export default Pages