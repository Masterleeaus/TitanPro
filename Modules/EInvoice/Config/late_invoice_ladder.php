<?php
return [
 'enabled'=>true,
 'stages'=>[
  ['day'=>3,'code'=>'friendly_reminder','tone'=>'friendly','channels'=>['email']],
  ['day'=>7,'code'=>'direct_reminder','tone'=>'direct','channels'=>['email','sms_optional']],
  ['day'=>14,'code'=>'call_script','tone'=>'firm','channels'=>['task','email']],
  ['day'=>21,'code'=>'payment_plan','tone'=>'supportive','channels'=>['email','task']],
  ['day'=>30,'code'=>'escalation_recommendation','tone'=>'escalation','channels'=>['task']],
 ],
 'throttle_hours'=>20,
 'suppressions'=>['dispute_open','payment_plan_active','written_off','closed'],
];
