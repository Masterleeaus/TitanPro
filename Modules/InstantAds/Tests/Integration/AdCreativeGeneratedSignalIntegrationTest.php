<?php

namespace Modules\InstantAds\Tests\Integration;

use Modules\TitanStudioHub\Listeners\AddCreativeToBrandAssetLibraryListener;
use Modules\TitanStudioHub\Support\BrandAssetLibraryStore;
use PHPUnit\Framework\TestCase;

class AdCreativeGeneratedSignalIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        BrandAssetLibraryStore::flush();
    }

    public function test_titan_studio_hub_listener_adds_generated_creative_to_brand_library(): void
    {
        $listener = new AddCreativeToBrandAssetLibraryListener;
        $listener->handle([
            'creative_id' => 111,
            'company_id' => 22,
            'url' => '/uploads/demo.png',
            'provider' => 'flux',
            'prompt' => 'Demo prompt',
        ]);

        $assets = BrandAssetLibraryStore::all(22);

        $this->assertCount(1, $assets);
        $this->assertSame('/uploads/demo.png', $assets[0]['url']);
    }
}
