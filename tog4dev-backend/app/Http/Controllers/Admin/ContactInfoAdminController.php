<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use Illuminate\Http\Request;

class ContactInfoAdminController extends Controller
{
    public function edit()
    {
        $info = ContactInfo::current();
        return view('admin.contact_info.edit', compact('info'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'city_country'      => 'nullable|string|max:255',
            'city_country_ar'   => 'nullable|string|max:255',
            'street_short'      => 'nullable|string|max:255',
            'street_short_ar'   => 'nullable|string|max:255',
            'company_name'      => 'nullable|string|max:255',
            'company_name_ar'   => 'nullable|string|max:255',
            'address_line1'     => 'nullable|string|max:255',
            'address_line1_ar'  => 'nullable|string|max:255',
            'address_line2'     => 'nullable|string|max:1000',
            'address_line2_ar'  => 'nullable|string|max:1000',
            'working_hours'     => 'nullable|string|max:255',
            'working_hours_ar'  => 'nullable|string|max:255',
            'phone_sub'         => 'nullable|string|max:255',
            'phone_sub_ar'      => 'nullable|string|max:255',
            'landline_sub'      => 'nullable|string|max:255',
            'landline_sub_ar'   => 'nullable|string|max:255',
            'email_sub'         => 'nullable|string|max:255',
            'email_sub_ar'      => 'nullable|string|max:255',
            'phone_primary'     => 'nullable|string|max:50',
            'whatsapp_number'   => 'nullable|string|max:50',
            'landline'          => 'nullable|string|max:50',
            'email_primary'     => 'nullable|email|max:255',
            'extra_phones'      => 'nullable|array',
            'extra_phones.*'    => 'nullable|string|max:50',
            'extra_emails'      => 'nullable|array',
            'extra_emails.*'    => 'nullable|email|max:255',
            'social_links'      => 'nullable|array',
            'social_links.*'    => 'nullable|string|max:500',
            'map_link'          => 'nullable|string|max:500',
            'map_embed_url'     => ['nullable', 'string', 'max:1000', 'regex:#^https://(www\.)?google\.[a-z\.]+/maps[^\s\'"<>]*$#i'],
            'map_lat'           => 'nullable|numeric',
            'map_lng'           => 'nullable|numeric',
        ]);

        $info = ContactInfo::current();

        $data = $request->only([
            'city_country', 'city_country_ar',
            'street_short', 'street_short_ar',
            'company_name', 'company_name_ar',
            'address_line1', 'address_line1_ar',
            'address_line2', 'address_line2_ar',
            'working_hours', 'working_hours_ar',
            'phone_sub', 'phone_sub_ar',
            'landline_sub', 'landline_sub_ar',
            'email_sub', 'email_sub_ar',
            'phone_primary', 'whatsapp_number', 'landline', 'email_primary',
            'map_link', 'map_embed_url', 'map_lat', 'map_lng',
        ]);

        // Normalize array fields (drop empty entries)
        $data['extra_phones'] = array_values(array_filter(
            $request->input('extra_phones', []),
            fn ($v) => filled($v)
        ));
        $data['extra_emails'] = array_values(array_filter(
            $request->input('extra_emails', []),
            fn ($v) => filled($v)
        ));
        $data['social_links'] = array_map(
            fn ($v) => is_string($v) ? trim($v) : $v,
            $request->input('social_links', [])
        );

        $info->update($data);

        return redirect()
            ->route('contact-info-admin.edit')
            ->with('success', __('app.updated successfully'));
    }
}
