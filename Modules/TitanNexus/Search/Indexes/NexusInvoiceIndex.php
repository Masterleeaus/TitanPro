<?php
namespace Modules\TitanNexus\Search\Indexes;
class NexusInvoiceIndex { public function fields(): array { return ['id','tenant_id','status','updated_at']; } }
