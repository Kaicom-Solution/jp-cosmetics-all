<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\FileStorageService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $fileStorageService;

    public function __construct(FileStorageService $fileStorageService)
    {
        $this->fileStorageService = $fileStorageService;
    }

    public function list(){
        $categories = Category::with('parent')->withCount('products')->orderBy('sequence')->paginate(10);

        return view('categories.list', compact('categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('categories.create', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $cloudImageUploadResponse = $this->fileStorageService->uploadImageToCloud($image, 'category');
            $data['image'] = $cloudImageUploadResponse['public_path'];
        }
        
        $data['slug'] = Str::of($data['name'])->slug('-');

        Category::create($data);

        Toastr::success('Category created successfully.');
        return redirect()->route('category.list');
    }

    public function edit(string $id)
    {
        $category   = Category::findOrFail($id);

        $categories = Category::where('id', '!=', $category->id)
                        ->orderBy('name')->get();

        return view('categories.edit', compact('category', 'categories'));
    }

    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($category->image) {
                $newFile = $request->file('image');
                $fileToDelete = $category->image;
                $imageUploadResponse = $this->fileStorageService->updateFileFromCloud($fileToDelete, $newFile);
                $data['image'] = $imageUploadResponse['public_path'];
            } else {
                $image = $request->file('image');
                $imageUploadResponse = $this->fileStorageService->uploadImageToCloud($image, 'category');
                $data['image'] = $imageUploadResponse['public_path'];
            }
        }
        

        $data['slug'] = Str::of($data['slug'] ?? $data['name'])->slug('-');

        $category->update($data);

        Toastr::success('Category updated successfully.');
        return redirect()->route('category.list');
    }

    public function toggleStatus($id)
    {
        try {
            $category = Category::findOrFail($id);
    
            if ($category->status) {
                $category->status = false;
                $category->save();
            } else {
                $category->status = true;
                $category->save();
            }
            Toastr::success('Status changed successfully.');
            return redirect()->route('category.list');
        } catch (\Exception $e) {
            Toastr::success('Status not changed');
            return redirect()->route('category.list');
        }
    }


}
