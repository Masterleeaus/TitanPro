<?php
return [
 'auto_post'=>true,
 'locked_periods'=>true,
 'journal_templates'=>[
  'invoice_sent'=>[
   ['side'=>'debit','account'=>'accounts_receivable','source'=>'invoice.total'],
   ['side'=>'credit','account'=>'revenue','source'=>'invoice.subtotal'],
   ['side'=>'credit','account'=>'gst_payable','source'=>'invoice.tax_total'],
  ],
  'write_off'=>[
   ['side'=>'debit','account'=>'bad_debt_expense','source'=>'invoice.balance_due'],
   ['side'=>'credit','account'=>'accounts_receivable','source'=>'invoice.balance_due'],
  ],
 ],
];
