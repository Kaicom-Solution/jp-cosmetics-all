<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
public function index()
    {
        $settings = Setting::all();
        
        return $this->responseWithSuccess($settings, 'Settings retrieved successfully');
    }

    public function show($key)
    {
        $setting = Setting::where('key', $key)->first();

        if (!$setting) {
            return $this->responseWithError('Setting not found', [], 404);
        }

        return $this->responseWithSuccess($setting, 'Setting retrieved successfully');
    }

    // public function update(Request $request, $key)
    // {
    //     $request->validate([
    //         'description' => 'required|string',
    //     ]);

    //     $setting = Setting::where('key', $key)->first();

    //     if (!$setting) {
    //         $setting = Setting::create([
    //             'key' => $key,
    //             'description' => $request->description
    //         ]);
            
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Setting created successfully',
    //             'data' => $setting
    //         ], 201);
    //     }

    //     $setting->update([
    //         'description' => $request->description
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Setting updated successfully',
    //         'data' => $setting
    //     ]);
    // }
}
