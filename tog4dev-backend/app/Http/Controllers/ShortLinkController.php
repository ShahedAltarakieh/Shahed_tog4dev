<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortLinkController extends Controller
{
    public function index()
    {
        $shortlinks = ShortLink::all();
        return view('admin.shortlinks.index', compact('shortlinks'));
    }

    public function create()
    {
        return view('admin.shortlinks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
            'short_code' => 'nullable|string|unique:short_links,short_code|max:20',
        ]);

        $shortCode = $request->short_code ?? Str::random(8); // generate if not provided

        ShortLink::create([
            'original_url' => $request->original_url,
            'short_code' => $shortCode,
        ]);

        if ($request->save_and_return) {
            return redirect()->route('shortlinks.index')->with('success', __('app.add successfully'));
        }

        return redirect()->back()->with('success', __('app.add successfully'));
    }


    public function show(ShortLink $shortlink)
    {
        return redirect($shortlink->original_url);
    }

    public function edit(ShortLink $shortlink)
    {
        return view('admin.shortlinks.edit', compact('shortlink'));
    }

    public function update(Request $request, ShortLink $shortlink)
    {
        $request->validate([
            'original_url' => 'required|url',
            'short_code' => 'nullable|string|min:8|max:20|unique:short_links,short_code,' . $shortlink->id,
        ]);

        $shortlink->update([
            'original_url' => $request->original_url,
            'short_code' => $request->short_code ?? $shortlink->short_code,
        ]);

        if ($request->save_and_return) {
            return redirect()->route('shortlinks.index')->with('success', __('app.updated successfully'));
        }

        return redirect()->back()->with('success', __('app.updated successfully'));
    }

    public function destroy(ShortLink $shortlink)
    {
        $shortlink->short_code = $shortlink->short_code."_DELETED_".rand();
        $shortlink->save();
        $shortlink->delete();
        if ($shortlink) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
}
