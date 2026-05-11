<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use PHPUnit\Framework\TestCase;

class TitanChatbotStructureTest extends TestCase
{
    /** @dataProvider requiredClassProvider */
    public function test_required_class_exists(string $class): void
    {
        $this->assertTrue(class_exists($class), "Class {$class} does not exist");
    }

    public static function requiredClassProvider(): array
    {
        return [
            ['Modules\TitanEchoAssist\DTOs\MessagePayload'],
            ['Modules\TitanEchoAssist\Services\ConversationRouter'],
            ['Modules\TitanEchoAssist\Services\GeneratorBridge'],
            ['Modules\TitanEchoAssist\Services\ChannelRouter'],
            ['Modules\TitanEchoAssist\Services\WebchatChannel'],
            ['Modules\TitanEchoAssist\Services\TrainingPipeline'],
            ['Modules\TitanEchoAssist\Billing\Meters\VoiceSecondsMeter'],
            ['Modules\TitanEchoAssist\Billing\Meters\ConversationMeter'],
            ['Modules\TitanEchoAssist\Billing\Meters\EmbeddingMeter'],
            ['Modules\TitanEchoAssist\AI\Agents\ConversationAgent'],
            ['Modules\TitanEchoAssist\AI\Agents\VoiceAgent'],
            ['Modules\TitanEchoAssist\AI\Pipelines\RagPipeline'],
            ['Modules\TitanEchoAssist\Models\Chatbot'],
            ['Modules\TitanEchoAssist\Models\Conversation'],
            ['Modules\TitanEchoAssist\Models\ChatbotHistory'],
            ['Modules\TitanEchoAssist\Models\ChatbotEmbedding'],
            ['Modules\TitanEchoAssist\Providers\ModuleServiceProvider'],
        ];
    }

    /** @dataProvider requiredInterfaceProvider */
    public function test_channel_drivers_implement_interface(string $class): void
    {
        $this->assertTrue(class_exists($class), "Class {$class} does not exist");
        $this->assertTrue(
            in_array(
                \Modules\TitanEchoAssist\Contracts\ChannelDriver::class,
                class_implements($class) ?: []
            ),
            "{$class} must implement ChannelDriver"
        );
    }

    public static function requiredInterfaceProvider(): array
    {
        return [
            ['Modules\TitanEchoAssist\Services\WebchatChannel'],
            ['Modules\TitanEchoAssist\Services\WhatsappChannel'],
            ['Modules\TitanEchoAssist\Services\TelegramChannel'],
            ['Modules\TitanEchoAssist\Services\MessengerChannel'],
            ['Modules\TitanEchoAssist\Services\VoiceChannel'],
        ];
    }
}
