<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use Modules\TitanEchoAssist\Services\Embedders\OpenAIEmbedder;
use PHPUnit\Framework\TestCase;

class OpenAIEmbedderTest extends TestCase
{
    public function test_embed_returns_1536_length_float_array(): void
    {
        putenv('OPENAI_API_KEY');

        $vector = (new OpenAIEmbedder())->embed('hello world');

        $this->assertCount(1536, $vector);
        $this->assertIsFloat($vector[0]);
    }
}
