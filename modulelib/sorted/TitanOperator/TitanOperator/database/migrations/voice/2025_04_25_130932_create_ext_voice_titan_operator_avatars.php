<?php

use App\Extensions\TitanOperatorVoice\System\Models\ExtVoiceoperatorAvatar;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public static $prefix = 'ext';

    public function up(): void
    {
        if (Schema::hasTable(self::$prefix . '_voiceoperator_avatars')) {
            return;
        }

        Schema::create(self::$prefix . '_voiceoperator_avatars', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('avatar');
            $table->timestamp('created_at')->useCurrent();
        });

        $avatars = config('titan_operator_voice.avatars');

        if (ExtVoiceoperatorAvatar::query()->count() > 0) {
            return;
        }

        try {
            foreach ($avatars as $avatar) {

                $image = Storage::disk('extension')->path('TitanOperatorVoice/resources/assets/avatars/' . $avatar);

                if (\Illuminate\Support\Facades\File::exists($image) === false) {
                    continue;
                }

                $file = Storage::disk('public')->putFile('avatars', $image);

                ExtVoiceoperatorAvatar::query()->create([
                    'avatar'     => 'uploads/' . $file,
                    'created_at' => now(),
                ]);
            }
        } catch (\Exception $e) {

        }

    }

    public function down(): void
    {
        Schema::dropIfExists(self::$prefix . '_voiceoperator_avatars');
    }
};
