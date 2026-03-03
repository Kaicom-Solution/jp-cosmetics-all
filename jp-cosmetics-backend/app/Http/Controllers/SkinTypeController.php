<?php

namespace App\Http\Controllers;

use App\Http\Requests\SkinTypeRequest;
use App\Models\SkinType;
use App\Services\FileStorageService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SkinTypeController extends Controller
{
    protected $fileStorageService;

    public function __construct(FileStorageService $fileStorageService)
    {
        $this->fileStorageService = $fileStorageService;
    }

    public function list(Request $request)
    {
        $skinTypes = SkinType::orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('skin-types.list', compact('skinTypes'));
    }

    public function create()
    {
        return view('skin-types.create');
    }

    public function store(SkinTypeRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoUploadResponse = $this->fileStorageService->uploadImageToCloud($logo, 'skin-type');
            $data['logo'] = $logoUploadResponse['public_path'];
        }

        $data['slug'] = rand(1, 99999) . '-' . Str::of($data['name'])->slug('-');

        SkinType::create($data);

        Toastr::success('Skin type created successfully.');
        return redirect()->route('skin-type.list');
    }

    public function edit(string $id)
    {
        $skinType = SkinType::findOrFail($id);
        return view('skin-types.edit', compact('skinType'));
    }

    public function update(SkinTypeRequest $request, string $id)
    {
        $skinType = SkinType::findOrFail($id);
        $data     = $request->validated();

        if ($request->hasFile('logo')) {

            if ($skinType->logo) {
                $newFile    = $request->file('logo');
                $fileToDelete = $skinType->logo;
                $logoUploadResponse = $this->fileStorageService->updateFileFromCloud($fileToDelete, $newFile);
                $data['logo'] = $logoUploadResponse['public_path'];
            } else {
                $logo = $request->file('logo');
                $logoUploadResponse = $this->fileStorageService->uploadImageToCloud($logo, 'skin-type');
                $data['logo'] = $logoUploadResponse['public_path'];
            }
        }

        $data['slug'] = Str::of($data['name'])->slug('-');

        $skinType->update($data);

        Toastr::success('Skin type updated successfully.');
        return redirect()->route('skin-type.list');
    }

    public function toggleStatus($id)
    {
        try {
            $skinType = SkinType::findOrFail($id);

            if ($skinType->status) {
                $skinType->status = false;
                $skinType->save();
            } else {
                $skinType->status = true;
                $skinType->save();
            }

            Toastr::success('Status changed successfully.');
            return redirect()->route('skin-type.list');
        } catch (\Exception $e) {
            Toastr::error('Status not changed.');
            return redirect()->route('skin-type.list');
        }
    }

    public function destroy(string $id)
    {
        $skinType = SkinType::findOrFail($id);

        if ($skinType->logo && file_exists(public_path($skinType->logo))) {
            @unlink(public_path($skinType->logo));
        }

        $skinType->delete();

        Toastr::success('Skin type deleted successfully.');
        return redirect()->route('skin-type.list');
    }
}
