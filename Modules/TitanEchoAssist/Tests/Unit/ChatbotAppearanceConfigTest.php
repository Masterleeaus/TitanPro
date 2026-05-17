<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use Modules\TitanEchoAssist\Enums\BubbleDesign;
use Modules\TitanEchoAssist\Enums\ColorModeEnum;
use Modules\TitanEchoAssist\Enums\HeaderBgEnum;
use Modules\TitanEchoAssist\Enums\PositionEnum;
use Modules\TitanEchoAssist\Models\Chatbot;
use PHPUnit\Framework\TestCase;

class ChatbotAppearanceConfigTest extends TestCase
{
    public function test_bubble_enums_define_expected_cases(): void
    {
        $this->assertSame(['blank', 'plain', 'links', 'modern', 'suggestions', 'promo_banner'], array_map(static fn (BubbleDesign $case): string => $case->value, BubbleDesign::cases()));
        $this->assertSame(['solid', 'gradient', 'none'], array_map(static fn (ColorModeEnum $case): string => $case->value, ColorModeEnum::cases()));
        $this->assertSame(['color', 'gradient', 'image'], array_map(static fn (HeaderBgEnum $case): string => $case->value, HeaderBgEnum::cases()));
        $this->assertSame(['left', 'right'], array_map(static fn (PositionEnum $case): string => $case->value, PositionEnum::cases()));
    }

    public function test_chatbot_fillable_and_casts_include_new_appearance_fields(): void
    {
        $model = new Chatbot();

        $fillable = $this->readProtectedProperty($model, 'fillable');
        $casts = $this->readProtectedProperty($model, 'casts');

        $requiredFillableFields = [
            'bubble_design',
            'header_bg',
            'show_date_time',
            'show_avg_response_time',
            'header_bg_gradient_start',
            'header_bg_gradient_end',
            'welcome_bg_image',
            'promo_banner_image',
            'promo_banner_title',
            'promo_banner_description',
            'promo_banner_cta_label',
            'promo_banner_cta_url',
            'social_whatsapp',
            'social_telegram',
            'social_facebook',
            'social_instagram',
            'footer_links',
            'privacy_policy_url',
            'terms_url',
        ];

        foreach ($requiredFillableFields as $field) {
            $this->assertContains($field, $fillable);
        }

        $this->assertSame(BubbleDesign::class, $casts['bubble_design']);
        $this->assertSame(ColorModeEnum::class, $casts['color_mode']);
        $this->assertSame(HeaderBgEnum::class, $casts['header_bg']);
        $this->assertSame(PositionEnum::class, $casts['position']);
        $this->assertSame('array', $casts['footer_links']);
    }

    private function readProtectedProperty(object $object, string $property): mixed
    {
        $reflection = new \ReflectionClass($object);
        $prop = $reflection->getProperty($property);
        $prop->setAccessible(true);

        return $prop->getValue($object);
    }
}
