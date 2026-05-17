<?php

namespace Modules\TitanEchoAssist\Support;

class WorkcoreSchemaMap
{
    public const JOBS_TABLE = 'field_jobs';
    public const CUSTOMERS_TABLE = 'customers';
    public const INVOICES_TABLE = 'invoices';
    public const QUOTES_TABLE = 'estimates';
    public const PROPERTIES_TABLE = 'properties';
    public const CHECKLIST_TABLE = 'job_checklist_items';
    public const JOB_MESSAGES_TABLE = 'job_messages';
    public const RECURRING_SERVICES_TABLE = 'ext_chatbot_portal_recurring_services';
    public const DOCUMENT_LINKS_TABLE = 'ext_chatbot_portal_document_links';

    public const JOB_ID_FIELD = 'id';
    public const CUSTOMER_ID_FIELD = 'customer_id';
    public const PROPERTY_ID_FIELD = 'property_id';
    public const COMPANY_ID_FIELD = 'organization_id';
    public const LEGACY_COMPANY_ID_FIELD = 'company_id';
    public const STATUS_FIELD = 'status';
    public const SCHEDULED_AT_FIELD = 'scheduled_at';
    public const COMPLETED_AT_FIELD = 'completed_at';
    public const DUE_AT_FIELD = 'due_at';
    public const BALANCE_DUE_FIELD = 'balance_due';
}
