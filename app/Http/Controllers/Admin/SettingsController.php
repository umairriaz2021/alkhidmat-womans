<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function settingUpdate(Request $request)
{
    // Settings fetch karein (ID 1 default)
    $settings = Setting::with(['siteLogo', 'footerLogo'])->first();

    if ($request->isMethod('post')) {
        $validatedData = $request->validate([
            'stitle' => 'required|string|max:255',
            'stag'   => 'nullable|string|max:255',
            'profile_image_id' => 'nullable|exists:media,id',
            'profile_footer_image_id' => 'nullable|exists:media,id',
        ], [
            'stitle.required' => 'The website title is mandatory.',
        ]);

        // Mapping Blade names to Database Columns
        Setting::updateOrCreate(
            ['id' => 1], 
            [
                'site_title'  => $request->stitle,
                'site_tag'    => $request->stag,
                'site_logo'   => $request->profile_image_id,
                'footer_logo' => $request->profile_footer_image_id,
            ]
        );

        return back()->with('success', 'Settings updated successfully!');
    }

    return view('admin.dashboard.settings.index', compact('settings'));
}

}
