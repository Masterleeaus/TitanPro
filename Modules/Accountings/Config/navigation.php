<?php

return [
    'label' => 'Money Manager',
    'title' => 'ZeroPay Panel',
    'group' => 'Finance',
    'sort' => 210,
    'icon' => 'heroicon-o-banknotes',
    'permission' => 'money.view',
    'fallback_permissions' => ['einvoice.view', 'accounting.view', 'accountings.view'],
    'slug' => 'zeropay-panel',
    'scope' => 'invoicing_accounting_followup_only',
];
