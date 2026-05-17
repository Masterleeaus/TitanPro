<?php

namespace Modules\Payroll\Support\Enums;

enum PayrollComponentType: string
{
    case Earning = 'earning';
    case Deduction = 'deduction';
    case EmployerContribution = 'employer_contribution';
    case Reimbursement = 'reimbursement';
    case Tax = 'tax';
}
