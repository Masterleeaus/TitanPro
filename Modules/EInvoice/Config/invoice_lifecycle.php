<?php
return [
 'states'=>['draft','pending_send','sent','viewed','due','overdue','escalation_stage_1','escalation_stage_2','escalation_stage_3','payment_plan_suggested','written_off','closed','void'],
 'auto_send'=>['enabled'=>true,'delay_minutes'=>0,'suppress_if_missing_customer_email'=>true],
 'zero_pay_handoff_only'=>true,
];
