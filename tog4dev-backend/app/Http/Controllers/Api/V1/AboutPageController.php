<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AboutPageResource;
use App\Models\AboutPage;
use App\Models\AboutSection;
use Illuminate\Http\Request;

class AboutPageController extends Controller
{
    public function show(Request $request)
    {
        $countryCode = $request->get('country', 'JO');

        $page = AboutPage::with(['sections' => function ($q) {
            $q->visible()->orderBy('sort_order');
            $q->with(['items' => function ($q2) {
                $q2->visible()->orderBy('sort_order');
            }]);
        }])->published()->forCountry($countryCode)->first();

        $globalPage = null;
        if ($countryCode !== 'global') {
            $globalPage = AboutPage::with(['sections' => function ($q) {
                $q->visible()->orderBy('sort_order');
                $q->with(['items' => function ($q2) {
                    $q2->visible()->orderBy('sort_order');
                }]);
            }])->published()->global()->first();
        }

        if (!$page && !$globalPage) {
            return response()->json([
                'data' => null,
                'sections' => [],
            ]);
        }

        $resolvedPage = $page ?: $globalPage;

        if ($page && $globalPage) {
            $allCountrySectionKeys = AboutSection::where('about_page_id', $page->id)->pluck('section_key')->toArray();
            $fallbackSections = $globalPage->sections->filter(function ($section) use ($allCountrySectionKeys) {
                return !in_array($section->section_key, $allCountrySectionKeys);
            });

            if ($fallbackSections->isNotEmpty()) {
                $merged = $page->sections->merge($fallbackSections)->sortBy('sort_order')->values();
                $page->setRelation('sections', $merged);
            }
        }

        return new AboutPageResource($resolvedPage);
    }

    public function trackEvent(Request $request)
    {
        $request->validate([
            'event' => 'required|string|in:section_view,button_click,founder_click,partner_click',
            'section_key' => 'required|string',
            'country' => 'nullable|string',
            'language' => 'nullable|string',
        ]);

        return response()->json(['status' => 'ok']);
    }
}
