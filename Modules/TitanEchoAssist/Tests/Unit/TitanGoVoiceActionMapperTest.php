<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use Modules\TitanEchoAssist\Services\TitanGoVoiceActionMapper;
use PHPUnit\Framework\TestCase;

class TitanGoVoiceActionMapperTest extends TestCase
{
    public function test_maps_exact_phrase_to_expected_action_key(): void
    {
        $mapper = new TitanGoVoiceActionMapper();

        $action = $mapper->mapPhraseToAction('create site diary');

        $this->assertSame('site_diary.create', $action);
        $this->assertSame('create site diary', $mapper->matchedPhrase());
    }

    public function test_maps_fuzzy_phrase_to_expected_action_key(): void
    {
        $mapper = new TitanGoVoiceActionMapper();

        $action = $mapper->mapPhraseToAction('summrize job please');

        $this->assertSame('job.summary', $action);
    }

    public function test_returns_null_for_unmatched_phrase(): void
    {
        $mapper = new TitanGoVoiceActionMapper();

        $action = $mapper->mapPhraseToAction('turn on the office lights');

        $this->assertNull($action);
    }
}
