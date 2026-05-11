<?php

namespace Tests\Modules\TitanDocs;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\TitanCore\Contracts\AI\ClientInterface;
use Modules\TitanDocs\Entities\AiPromptHistory;
use Modules\TitanDocs\Entities\AiPromptResponse;
use Modules\TitanDocs\Entities\AiTemplate;
use Modules\TitanDocs\Entities\AiTemplateCategory;
use Modules\TitanDocs\Entities\AiTemplatePrompt;
use Modules\TitanDocs\Filament\Pages\TitanDocsControlPanel;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class TitanDocsFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_module_manifest_is_enabled_and_docs_stub_manifest_is_removed(): void
    {
        $manifest = json_decode((string) file_get_contents(base_path('Modules/TitanDocs/module.json')), true);

        $this->assertIsArray($manifest);
        $this->assertSame(1, $manifest['active'] ?? null);
        $this->assertSame('titanpro', $manifest['filament_panel'] ?? null);
        $this->assertSame(
            'Modules\\TitanDocs\\Filament\\Plugin\\TitanDocsPlugin',
            data_get($manifest, 'filament.plugin'),
        );
        $this->assertFileDoesNotExist(base_path('Modules/Docs/module.json'));
    }

    public function test_titandocs_filament_page_exposes_documents_navigation_entry(): void
    {
        $reflection = new \ReflectionClass(TitanDocsControlPanel::class);

        $this->assertTrue($this->app['router']->getRoutes()->hasNamedRoute('titan.docs.generator.start'));
        $this->assertSame('Documents', $reflection->getStaticPropertyValue('navigationGroup'));
        $this->assertSame('TitanDocs', $reflection->getStaticPropertyValue('navigationLabel'));
        $this->assertSame('titan-docs', $reflection->getStaticPropertyValue('slug'));
        $this->assertFileExists(base_path('Modules/TitanDocs/Resources/views/filament/pages/control-panel.blade.php'));
    }

    public function test_wizard_start_creates_session_and_ai_generation_persists_history(): void
    {
        $this->grantPermissions([
            'ai document create',
            'ai document generate',
        ]);

        $user = User::factory()->create([
            'organization_id' => 42,
        ]);
        $user->givePermissionTo(['ai document create', 'ai document generate']);

        $this->app->instance(ClientInterface::class, new class implements ClientInterface
        {
            public function chat(array $messages, array $options = []): array
            {
                return [
                    'ok' => true,
                    'content' => 'Generated TitanDocs output',
                    'usage' => ['completion_tokens' => 4],
                ];
            }

            public function embed(string $input, array $options = []): array
            {
                return ['ok' => true, 'vector' => []];
            }
        });

        $this->actingAs($user)
            ->get(route('titan.docs.generator.start'))
            ->assertRedirect();

        $this->assertDatabaseHas('titandocs_wizard_sessions', [
            'user_id' => $user->id,
            'company_id' => 42,
            'status' => 'draft',
        ]);

        $category = AiTemplateCategory::create([
            'name' => 'Content',
            'status' => true,
            'created_by' => 0,
        ]);

        $template = AiTemplate::create([
            'name' => 'Safe Work Method Statement',
            'description' => 'Generates a safety document.',
            'template_code' => 'swms-template',
            'status' => true,
            'professional' => false,
            'slug' => 'safe-work-method-statement',
            'category_id' => (string) $category->id,
            'type' => '1',
            'form_fields' => json_encode([
                'field' => [
                    [
                        'label' => 'Scope',
                        'field_type' => 'textarea',
                        'field_name' => 'scope',
                        'placeholder' => 'Describe the scope',
                    ],
                ],
            ], JSON_THROW_ON_ERROR),
            'is_tone' => false,
        ]);

        AiTemplatePrompt::create([
            'template_id' => $template->id,
            'key' => 'en',
            'value' => 'Prepare a compliant document for ##scope##.',
            'created_by' => 0,
        ]);

        $this->actingAs($user)
            ->post('/aidocument/store', ['template_id' => $template->id])
            ->assertOk()
            ->assertJson(['status' => 1]);

        $history = AiPromptHistory::query()->latest('id')->first();

        $this->assertNotNull($history);
        $this->assertSame($user->id, (int) $history->created_by);
        $this->assertSame(42, (int) $history->workspace);

        $this->actingAs($user)
            ->post(route('titan.docs.process'), [
                'template' => $template->template_code,
                'document_id' => $history->id,
                'language' => 'en',
                'scope' => 'Roof access and harness use',
                'words' => 300,
                'max_results' => 1,
                'creativity' => 0.3,
            ], [
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->assertOk()
            ->assertJson([
                'status' => 'success',
                'text' => 'Generated TitanDocs output',
            ]);

        $history->refresh();

        $this->assertSame('Prepare a compliant document for Roof access and harness use.', $history->prompt);
        $this->assertDatabaseHas('ai_prompt_responses', [
            'history_prompt_id' => (string) $history->id,
            'content' => 'Generated TitanDocs output',
            'created_by' => (string) $user->id,
        ]);
        $this->assertSame(1, AiPromptResponse::query()->count());
    }

    /**
     * @param  array<int, string>  $permissions
     */
    protected function grantPermissions(array $permissions): void
    {
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }
}
