<?php

use App\Support\ThemeTokenRegistry;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('titan_theme_tokens')) {
            return;
        }

        Schema::create('titan_theme_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('panel', 64)->default('global')->index();
            $table->string('scope', 32)->index();
            $table->string('key', 120);
            $table->text('value');
            $table->timestamps();

            $table->unique(['panel', 'scope', 'key']);
        });

        $timestamp = now();
        DB::table('titan_theme_tokens')->insert(array_map(static fn (array $row): array => [
            ...$row,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ], ThemeTokenRegistry::defaultRows()));
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_theme_tokens');
    }
};
