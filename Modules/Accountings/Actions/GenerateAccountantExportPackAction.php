<?php
namespace Modules\Accountings\Actions;
use Modules\Accountings\Services\StatementExportService;
class GenerateAccountantExportPackAction { public function __construct(protected StatementExportService $exports) {} public function execute(array $data=[]): array { return $this->exports->accountantPack($data); } }
