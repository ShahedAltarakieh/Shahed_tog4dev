<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class LanguagesController extends Controller
{
    public function index(): JsonResponse
    {
        $payload = Cache::remember(Language::CACHE_KEY, 300, function () {
            $langs = Language::active()
                ->orderBy('position', 'ASC')
                ->orderBy('id', 'ASC')
                ->get(['code', 'name', 'native_name', 'direction', 'is_default', 'position']);

            $defaultCode = optional($langs->firstWhere('is_default', true))->code
                ?? ($langs->first()->code ?? 'en');

            $version = (string) (Language::max('updated_at') ?: now()->timestamp);

            return [
                'data' => $langs->map(fn ($l) => [
                    'code'        => $l->code,
                    'name'        => $l->name,
                    'native_name' => $l->native_name,
                    'direction'   => $l->direction,
                    'is_default'  => (bool) $l->is_default,
                    'position'    => (int) $l->position,
                ])->values(),
                'default' => $defaultCode,
                'version' => md5($version),
            ];
        });

        return response()->json($payload)
            ->header('Cache-Control', 'public, max-age=300');
    }
}
