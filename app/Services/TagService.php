<?php

namespace App\Services;

use App\Models\Tag;

class TagService
{
    public function createNewTag($name, $languageId,$id = null)
    {
        if($id != null) {
            return $id;
        }

        $slug = generateUniqueSlug($name);
        $tag = Tag::where(['language_id' => $languageId, 'slug' => $slug])->first();
        if ($tag) {
            return $tag->id;
        }

        $tag = Tag::create([
            'language_id' => $languageId,
            'slug' => $slug,
            'tag_name' => $name,
            'meta_title' => null,
            'meta_description' => null,
            'meta_keyword' => null,
            'schema_markup' => null,
            'og_image' => null,
        ]);

        return $tag->id;
    }
}
