<?php

use App\Models\Language;
use App\Models\Settings;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $french = Language::where('code', 'fr')->first();

        if (!$french) {
            $french = Language::create([
                'language' => 'Français',
                'display_name' => 'Français',
                'code' => 'fr',
                'status' => 1,
                'isRTL' => 0,
                'image' => 'flags/en.webp',
            ]);
        }

        $defaultSetting = Settings::where('type', 'default_language')->first();
        if ($defaultSetting) {
            $defaultSetting->message = (string) $french->id;
            $defaultSetting->save();
        } else {
            Settings::create([
                'type' => 'default_language',
                'message' => (string) $french->id,
            ]);
        }
    }

    public function down(): void
    {
        $english = Language::where('code', 'en')->first();
        if ($english) {
            $defaultSetting = Settings::where('type', 'default_language')->first();
            if ($defaultSetting) {
                $defaultSetting->message = (string) $english->id;
                $defaultSetting->save();
            }
        }
    }
};
