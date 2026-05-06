<?php
namespace Modules\TitanNexus\Services;
final class ConversionAnalyticsService { public function summarize(array $events): array { return ['events'=>count($events),'metrics'=>['sent','replied','booked']]; } }

