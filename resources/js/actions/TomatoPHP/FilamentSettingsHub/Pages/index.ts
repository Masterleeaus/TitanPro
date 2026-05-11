import SiteSettings from './SiteSettings'
import SocialMenuSettings from './SocialMenuSettings'
import SettingsHub from './SettingsHub'

const Pages = {
    SiteSettings: Object.assign(SiteSettings, SiteSettings),
    SocialMenuSettings: Object.assign(SocialMenuSettings, SocialMenuSettings),
    SettingsHub: Object.assign(SettingsHub, SettingsHub),
}

export default Pages