<?php
namespace Modules\TitanNexus\Services\Contracts;
interface PaymentProviderContract { public function createPaymentLink(array $invoice): array; public function reconcile(array $providerPayload): array; }
