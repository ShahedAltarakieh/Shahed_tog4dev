<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LanguageAdminController extends Controller
{
    public function index()
    {
        $data = Language::orderBy('position', 'ASC')->orderBy('id', 'ASC')->get();
        return view('admin.languages.index', compact('data'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateInput($request);
        $validated['is_active']  = $request->has('is_active');
        $validated['is_default'] = $request->has('is_default');

        // Ensure at least one default exists
        if (!$validated['is_default'] && Language::where('is_default', true)->count() === 0) {
            $validated['is_default'] = true;
        }

        Language::create($validated);
        return redirect()->route('languages-admin.index')->with('success', __('app.created successfully'));
    }

    public function show($id)
    {
        $data = Language::findOrFail($id);
        return view('admin.languages.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $lang = Language::findOrFail($id);
        $validated = $this->validateInput($request, $lang->id);

        $validated['is_active']  = $request->has('is_active');
        $validated['is_default'] = $request->has('is_default');

        // Default protection: cannot un-default the only default
        if ($lang->is_default && !$validated['is_default']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['is_default' => __('app.cannot remove default language. set another language as default first.')]);
        }

        // Active protection: cannot deactivate the default
        if ($validated['is_default'] && !$validated['is_active']) {
            $validated['is_active'] = true;
        }

        $lang->update($validated);
        return redirect()->route('languages-admin.index')->with('success', __('app.updated successfully'));
    }

    public function destroy($id)
    {
        $lang = Language::findOrFail($id);

        if ($lang->is_default) {
            echo json_encode(['status' => 'error', 'message' => 'Cannot delete the default language.']);
            return;
        }

        $lang->delete();
        echo json_encode(['status' => 'success']);
    }

    public function change_status($id)
    {
        $lang = Language::findOrFail($id);

        if ($lang->is_default && $lang->is_active) {
            echo json_encode(['status' => 'error', 'message' => 'Cannot deactivate the default language.']);
            return;
        }

        $lang->is_active = !$lang->is_active;
        $lang->save();
        echo json_encode(['status' => 'success']);
    }

    public function set_default($id)
    {
        $lang = Language::findOrFail($id);
        if (!$lang->is_active) {
            $lang->is_active = true;
        }
        $lang->is_default = true;
        $lang->save(); // model boot enforces single default + cache bust
        return redirect()->route('languages-admin.index')->with('success', __('app.updated successfully'));
    }

    private function validateInput(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'code' => [
                'required',
                'string',
                'min:2',
                'max:10',
                'regex:/^[a-z]{2,10}(-[a-z0-9]{2,10})?$/',
                Rule::unique('languages', 'code')->ignore($ignoreId),
            ],
            'name'        => 'required|string|max:100',
            'native_name' => 'required|string|max:100',
            'direction'   => 'required|in:ltr,rtl',
            'position'    => 'nullable|integer',
        ]);
    }
}
