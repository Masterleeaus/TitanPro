<?php
namespace Modules\TitanNexus\Search\Indexes;
class NexusPaymentIndex { public function fields(): array { return ['id','tenant_id','status','updated_at']; } }
