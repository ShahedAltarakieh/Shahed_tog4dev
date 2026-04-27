<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactInfoController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $locale = $this->resolveLocale($request);
        app()->setLocale($locale);

        $info = ContactInfo::current();

        $pickAr = fn(string $en, string $ar) => $locale === 'ar'
            ? ($info->{$ar} ?: $info->{$en})
            : ($info->{$en} ?: $info->{$ar});

        $payload = [
            'lang'          => $locale,
            'city_country'  => $pickAr('city_country',  'city_country_ar'),
            'street_short'  => $pickAr('street_short',  'street_short_ar'),
            'company_name'  => $pickAr('company_name',  'company_name_ar'),
            'address_line1' => $pickAr('address_line1', 'address_line1_ar'),
            'address_line2' => $pickAr('address_line2', 'address_line2_ar'),
            'working_hours' => $pickAr('working_hours', 'working_hours_ar'),
            'phone_sub'     => $pickAr('phone_sub',     'phone_sub_ar'),
            'landline_sub'  => $pickAr('landline_sub',  'landline_sub_ar'),
            'email_sub'     => $pickAr('email_sub',     'email_sub_ar'),
            'phone_primary'   => $info->phone_primary,
            'whatsapp_number' => $info->whatsapp_number,
            'landline'        => $info->landline,
            'email_primary'   => $info->email_primary,
            'extra_phones'    => $info->extra_phones ?: [],
            'extra_emails'    => $info->extra_emails ?: [],
            'social_links'    => $info->social_links ?: (object) [],
            'map_link'        => $info->map_link,
            'map_embed_url'   => $info->map_embed_url,
            'map_lat'         => $info->map_lat,
            'map_lng'         => $info->map_lng,
        ];

        return response()->json(['data' => $payload]);
    }

    /**
     * Pick locale from explicit `lang` query first, then Accept-Language header.
     * Maps any `ar-*` to `ar`, any `en-*` to `en`. Falls back to `en`.
     */
    private function resolveLocale(Request $request): string
    {
        $candidates = [
            $request->query('lang'),
            $request->header('Accept-Language'),
        ];

        foreach ($candidates as $val) {
            if (!$val) continue;
            $first = strtolower(trim(explode(',', $val)[0]));      // "ar-jo;q=0.9" -> "ar-jo;q=0.9"
            $first = strtolower(trim(explode(';', $first)[0]));    // -> "ar-jo"
            $base  = explode('-', $first)[0];                      // -> "ar"
            if (in_array($base, ['ar', 'en'], true)) {
                return $base;
            }
        }
        return 'en';
    }
}
