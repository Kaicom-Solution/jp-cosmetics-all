<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'privacy_policy' => Setting::where('key', 'privacy_policy')->first(),
            'cookie_policy' => Setting::where('key', 'cookie_policy')->first(),
            'terms_conditions' => Setting::where('key', 'terms_conditions')->first(),
            'returns_exchanges' => Setting::where('key', 'returns_exchanges')->first(),
            'shipping_delivery' => Setting::where('key', 'shipping_delivery')->first(),
        ];

        // Create default entries if they don't exist
        foreach (['privacy_policy', 'cookie_policy', 'terms_conditions', 'returns_exchanges', 'shipping_delivery'] as $key) {
            if (!$settings[$key]) {
                $settings[$key] = Setting::create([
                    'key' => $key,
                    'description' => ''
                ]);
            }
        }

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request, $key)
    {
        $request->validate([
            'description' => 'required|string',
        ]);

        $setting = Setting::where('key', $key)->first();

        if (!$setting) {
            $setting = Setting::create([
                'key' => $key,
                'description' => $request->description
            ]);
        } else {
            $setting->update([
                'description' => $request->description
            ]);
        }

        return redirect()->back()->with('success', 'Updated successfully!');
    }
}
