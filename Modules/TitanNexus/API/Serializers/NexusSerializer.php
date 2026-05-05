<?php
namespace Modules\TitanNexus\API\Serializers;
final class NexusSerializer { public function serialize(array $payload): string { return json_encode($payload); } }

