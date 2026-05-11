import shield from './shield'
import clientPipelines from './client-pipelines'
import leadScorings from './lead-scorings'
import dealProjects from './deal-projects'
import cRMCoreActivityLogs from './c-r-m-core-activity-logs'
import attachments from './attachments'
import cmsPages from './cms-pages'
import customers from './customers'
import driverLocations from './driver-locations'
import estimatePackages from './estimate-packages'
import estimates from './estimates'
import invoices from './invoices'
import items from './items'
import jobChecklistItems from './job-checklist-items'
import jobMessages from './job-messages'
import jobs from './jobs'
import jobTypeChecklistItems from './job-type-checklist-items'
import jobTypes from './job-types'
import messageTemplates from './message-templates'
import organizationSettings from './organization-settings'
import payments from './payments'
import properties from './properties'

const resources = {
    shield: Object.assign(shield, shield),
    clientPipelines: Object.assign(clientPipelines, clientPipelines),
    leadScorings: Object.assign(leadScorings, leadScorings),
    dealProjects: Object.assign(dealProjects, dealProjects),
    cRMCoreActivityLogs: Object.assign(cRMCoreActivityLogs, cRMCoreActivityLogs),
    attachments: Object.assign(attachments, attachments),
    cmsPages: Object.assign(cmsPages, cmsPages),
    customers: Object.assign(customers, customers),
    driverLocations: Object.assign(driverLocations, driverLocations),
    estimatePackages: Object.assign(estimatePackages, estimatePackages),
    estimates: Object.assign(estimates, estimates),
    invoices: Object.assign(invoices, invoices),
    items: Object.assign(items, items),
    jobChecklistItems: Object.assign(jobChecklistItems, jobChecklistItems),
    jobMessages: Object.assign(jobMessages, jobMessages),
    jobs: Object.assign(jobs, jobs),
    jobTypeChecklistItems: Object.assign(jobTypeChecklistItems, jobTypeChecklistItems),
    jobTypes: Object.assign(jobTypes, jobTypes),
    messageTemplates: Object.assign(messageTemplates, messageTemplates),
    organizationSettings: Object.assign(organizationSettings, organizationSettings),
    payments: Object.assign(payments, payments),
    properties: Object.assign(properties, properties),
}

export default resources