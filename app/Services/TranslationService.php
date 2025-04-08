<?php

namespace App\Services;

use App\Models\Translation;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;

class TranslationService
{
    /**
     * Returns a paginated list of translations based on search filters.
     */
    public function searchTranslations(Request $request): Paginator
    {
        $cacheKey = 'search_' . md5(json_encode($request->all()));
        return Cache::remember($cacheKey, 30, function () use ($request) {
            return $this->buildSearchQuery($request)->simplePaginate(50);
        });
    }

    /**
     * Returns a paginated list of translations.
     */
    public function listTranslations(Request $request): Paginator
    {
        return $this->buildSearchQuery($request)->simplePaginate(50);
    }

    /**
     * Creates a new translation record and syncs provided tags.
     */
    public function createTranslation(array $data): Translation
    {
        $translation = Translation::create($data);

        if (!empty($data['tags']) && is_array($data['tags'])) {
            $tagIds = Tag::whereIn('name', $data['tags'])->pluck('id')->toArray();
            $translation->tags()->sync($tagIds);
        }

        return $translation;
    }

    /**
     * Updates an existing translation record and syncs its tags.
     */
    public function updateTranslation(int $id, array $data)
    {
        $translation = Translation::findOrFail($id);
        $translation->update($data);

        if (isset($data['tags'])) {
            $tagIds = Tag::whereIn('name', $data['tags'])->pluck('id')->toArray();
            $translation->tags()->sync($tagIds);
        }

        return $translation;
    }

    /**
     * Deletes the given translation.
     */
    public function deleteTranslation(int $id): void
    {
        $translation = Translation::findOrFail($id);
        $translation->delete();
    }

    /**
     * Exports translations for the given locale using caching.
     */
    public function exportTranslations(string $locale)
    {
        $cacheKey = "translations_$locale";
        $translations = Cache::remember($cacheKey, 60, function () use ($locale) {
            return Translation::where('locale', $locale)->select('key', 'value')->get();
        });

        return $translations->pluck('value', 'key');
    }

    /**
     * Builds a query based on given search parameters.
     */
    protected function buildSearchQuery(Request $request)
    {
        return Translation::select('id', 'key', 'value', 'locale')
            ->with('tags:id,name')
            ->when($request->key, fn($q, $key) => $q->where('key', 'like', "%$key%"))
            ->when($request->value, fn($q, $value) => $q->where('value', 'like', "%$value%"))
            ->when($request->locale, fn($q, $locale) => $q->where('locale', $locale))
            ->when($request->tag, fn($q, $tag) => $q->whereHas('tags', fn($t) => $t->where('name', $tag)));
    }
}
