<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTranslationRequest;
use App\Http\Requests\UpdateTranslationRequest;
use App\Services\TranslationService;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    protected TranslationService $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function index(Request $request)
    {
        $translations = $this->translationService->listTranslations($request);
        return response()->json($translations);
    }

    public function store(StoreTranslationRequest $request)
    {
        $translation = $this->translationService->createTranslation($request->validated());
        return response()->json($translation, 201);
    }

    public function update(UpdateTranslationRequest $request, int $id)
    {
        return response()->json(
            $this->translationService->updateTranslation($id, $request->validated())
        );
    }

    public function destroy(int $id)
    {
        $this->translationService->deleteTranslation($id);
        return response()->noContent();
    }

    public function export(string $locale)
    {
        $translations = $this->translationService->exportTranslations($locale);
        return response()->json($translations);
    }

    public function search(Request $request)
    {
        $translations = $this->translationService->searchTranslations($request);
        return response()->json($translations);
    }
}
