<?php

use App\Models\Language;
use App\Models\Pages;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private function loadContent(string $filename): string
    {
        $path = database_path('seeders/content/' . $filename);

        if (!file_exists($path)) {
            return '';
        }

        return file_get_contents($path);
    }

    public function up(): void
    {
        $privacyContent = $this->loadContent('privacy_policy_fr.html');
        $termsContent = $this->loadContent('terms_conditions_fr.html');

        if ($privacyContent === '' && $termsContent === '') {
            return;
        }

        $languageIds = Language::whereIn('code', ['fr', 'en'])
            ->pluck('id')
            ->all();

        if (empty($languageIds)) {
            $languageIds = [1];
        }

        foreach ($languageIds as $languageId) {
            if ($privacyContent !== '') {
                Pages::where('page_type', 'privacy-policy')
                    ->where('language_id', $languageId)
                    ->update([
                        'title' => 'Politique de confidentialité',
                        'meta_description' => 'Politique de confidentialité REVIVAL TALK',
                        'meta_keywords' => 'Politique, confidentialité, REVIVAL TALK',
                        'page_content' => $privacyContent,
                        'is_privacypolicy' => 1,
                        'is_termspolicy' => 0,
                        'status' => 1,
                    ]);
            }

            if ($termsContent !== '') {
                Pages::where('page_type', 'terms-condition')
                    ->where('language_id', $languageId)
                    ->update([
                        'title' => 'Conditions générales d\'utilisation',
                        'meta_description' => 'Conditions générales d\'utilisation REVIVAL TALK',
                        'meta_keywords' => 'Conditions, utilisation, REVIVAL TALK',
                        'page_content' => $termsContent,
                        'is_privacypolicy' => 0,
                        'is_termspolicy' => 1,
                        'status' => 1,
                    ]);
            }
        }

        // Create pages if they do not exist yet (e.g. French-only fresh install)
        $defaultLanguageId = Language::where('code', 'fr')->value('id')
            ?? Language::orderBy('id')->value('id');

        if ($defaultLanguageId) {
            if ($privacyContent !== '' && !Pages::where('page_type', 'privacy-policy')->where('language_id', $defaultLanguageId)->exists()) {
                Pages::create([
                    'title' => 'Politique de confidentialité',
                    'slug' => 'privacy-policy',
                    'meta_description' => 'Politique de confidentialité REVIVAL TALK',
                    'meta_keywords' => 'Politique, confidentialité, REVIVAL TALK',
                    'is_custom' => 0,
                    'page_content' => $privacyContent,
                    'page_type' => 'privacy-policy',
                    'language_id' => $defaultLanguageId,
                    'page_icon' => '',
                    'is_termspolicy' => 0,
                    'is_privacypolicy' => 1,
                    'status' => 1,
                    'schema_markup' => '',
                    'meta_title' => 'Politique de confidentialité | REVIVAL TALK',
                    'og_image' => '',
                ]);
            }

            if ($termsContent !== '' && !Pages::where('page_type', 'terms-condition')->where('language_id', $defaultLanguageId)->exists()) {
                Pages::create([
                    'title' => 'Conditions générales d\'utilisation',
                    'slug' => 'terms-condition',
                    'meta_description' => 'Conditions générales d\'utilisation REVIVAL TALK',
                    'meta_keywords' => 'Conditions, utilisation, REVIVAL TALK',
                    'is_custom' => 0,
                    'page_content' => $termsContent,
                    'page_type' => 'terms-condition',
                    'language_id' => $defaultLanguageId,
                    'page_icon' => '',
                    'is_termspolicy' => 1,
                    'is_privacypolicy' => 0,
                    'status' => 1,
                    'schema_markup' => '',
                    'meta_title' => 'Conditions générales | REVIVAL TALK',
                    'og_image' => '',
                ]);
            }
        }
    }

    public function down(): void
    {
        // No rollback: legal content should remain unless manually reverted.
    }
};
