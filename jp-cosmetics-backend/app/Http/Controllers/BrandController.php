<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Services\FileStorageService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    protected $fileStorageService;

    public function __construct(FileStorageService $fileStorageService)
    {
        $this->fileStorageService = $fileStorageService;
    }

    public function list(Request $request){

        $brands = Brand::orderBy('name')
            ->paginate(15)
            ->withQueryString();
    
        return view('brands.list', compact('brands'));
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(BrandRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoUploadResponse = $this->fileStorageService->uploadImageToCloud($logo, 'brand');
            $data['logo'] = $logoUploadResponse['public_path'];
        }

        $data['slug'] = rand(1, 99999) . '-' . Str::of($data['name'])->slug('-');

        Brand::create($data);

        Toastr::success('Brand created successfully.');
        return redirect()->route('brand.list');
    }

    public function edit(string $id)
    {
        $brand = Brand::findOrFail($id);
        return view('brands.edit', compact('brand'));
    }

    public function update(BrandRequest $request, string $id)
    {
        $brand = Brand::findOrFail($id);
        $data  = $request->validated();

        if ($request->hasFile('logo')) {

            if ($brand->logo) {
                $newFile = $request->file('logo');
                $fileToDelete = $brand->logo;
                $logoUploadResponse = $this->fileStorageService->updateFileFromCloud($fileToDelete, $newFile);
                $data['logo'] = $logoUploadResponse['public_path'];
            } else {
                $logo = $request->file('logo');
                $logoUploadResponse = $this->fileStorageService->uploadImageToCloud($logo, 'brand');
                $data['logo'] = $logoUploadResponse['public_path'];
            }
        }

        $data['slug'] = Str::of($data['name'])->slug('-');

        $brand->update($data);

        Toastr::success('Brand updated successfully.');
        return redirect()->route('brand.list');
    }

    public function toggleStatus($id)
    {
        try {
            $brand = Brand::findOrFail($id);
    
            if ($brand->status) {
                $brand->status = false;
                $brand->save();
            } else {
                $brand->status = true;
                $brand->save();
            }
            Toastr::success('Status changed successfully.');
            return redirect()->route('brand.list');
        } catch (\Exception $e) {
            Toastr::error('Status not changed');
            return redirect()->route('brand.list');
        }
    }

    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->logo && file_exists(public_path($brand->logo))) {
            @unlink(public_path($brand->logo));
        }

        $brand->delete();

        Toastr::success('Brand deleted successfully.');
        return redirect()->route('brand.list');
    }
}
