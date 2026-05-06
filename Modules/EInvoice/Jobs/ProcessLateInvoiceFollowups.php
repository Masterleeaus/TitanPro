<?php
namespace Modules\EInvoice\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
class ProcessLateInvoiceFollowups implements ShouldQueue { use Dispatchable, InteractsWithQueue, Queueable, SerializesModels; public function __construct(public array $invoiceIds=[]) {} public function handle(): void { /* Host app resolves invoice models then calls ProcessOverdueInvoicesAction. */ } }
