<?php

namespace Modules\TitanEchoAssist\Billing;

use RuntimeException;

class CapExceededException extends RuntimeException
{
    public function __construct(
        string          $message,
        public readonly int $companyId,
        int             $code = 429,
        ?\Throwable     $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
