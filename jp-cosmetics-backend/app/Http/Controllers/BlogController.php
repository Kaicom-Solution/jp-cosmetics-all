<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogCategoryRequest;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Services\FileStorageService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
        protected $fileStorageService;

    public function __construct(FileStorageService $fileStorageService)
    {
        $this->fileStorageService = $fileStorageService;
    }

    // ==================== Blog Category Methods ====================

    /**
     * Display a listing of blog categories
     */
    public function categoryList()
    {
        $categories = BlogCategory::withCount('blogs')->latest()->paginate(10);
        return view('blog.category.list', compact('categories'));
    }

    /**
     * Show the form for creating a new blog category
     */
    public function categoryCreate()
    {
        return view('blog.category.create');
    }

    /**
     * Store a newly created blog category
     */
    public function categoryStore(BlogCategoryRequest $request)
    {
        $data = $request->validated();
        
        // $data['slug'] = rand(1, 99999) . '-' . Str::of($data['name'])->slug('-');
        // $data['slug'] = Str::of($data['name'])->slug('-');
        // $data['status'] = 1;


        // $data['slug'] = rand(1, 99999) . '-' . Str::of($data['title'])->slug('-');
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Always set status = 1
        $data['status'] = 1;

        BlogCategory::create($data);

        Toastr::success('Blog category created successfully.');
        return redirect()->route('blog.category.list');
    }

    /**
     * Show the form for editing blog category
     */
    public function categoryEdit(string $id)
    {
        $category = BlogCategory::findOrFail($id);
        return view('blog.category.edit', compact('category'));
    }

    /**
     * Update the specified blog category
     */
    public function categoryUpdate(BlogCategoryRequest $request, string $id)
    {
        $category = BlogCategory::findOrFail($id);
        $data = $request->validated();

        $data['slug'] = Str::of($data['slug'] ?? $data['name'])->slug('-');

        $category->update($data);

        Toastr::success('Blog category updated successfully.');
        return redirect()->route('blog.category.list');
    }

    /**
     * Toggle blog category status
     */
    public function categoryToggleStatus($id)
    {
        try {
            $category = BlogCategory::findOrFail($id);

            if ($category->status) {
                $category->status = false;
                $category->save();
            } else {
                $category->status = true;
                $category->save();
            }
            
            Toastr::success('Status changed successfully.');
            return redirect()->route('blog.category.list');
        } catch (\Exception $e) {
            Toastr::error('Status not changed');
            return redirect()->route('blog.category.list');
        }
    }

    // ==================== Blog Methods ====================

    /**
     * Display a listing of blogs
     */
    public function list()
    {
        $blogs = Blog::with('category')->latest()->paginate(10);
        return view('blog.list', compact('blogs'));
    }

    /**
     * Show the form for creating a new blog
     */
    public function create()
    {
        $categories = BlogCategory::where('status', true)->orderBy('name')->get();
        return view('blog.create', compact('categories'));
    }

    /**
     * Store a newly created blog
     */
    public function store(BlogRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $cloudImageUploadResponse = $this->fileStorageService->uploadImageToCloud($image, 'blog');
            $data['image'] = $cloudImageUploadResponse['public_path'];
        }

        // $data['slug'] = rand(1, 99999) . '-' . Str::of($data['title'])->slug('-');
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Always set status = 1
        $data['status'] = 1;


        Blog::create($data);

        Toastr::success('Blog created successfully.');
        return redirect()->route('blog.list');
    }

    /**
     * Show the form for editing blog
     */
    public function edit(string $id)
    {
        $blog = Blog::findOrFail($id);
        $categories = BlogCategory::where('status', true)->orderBy('name')->get();
        return view('blog.edit', compact('blog', 'categories'));
    }

    /**
     * Update the specified blog
     */
    public function update(BlogRequest $request, string $id)
    {
        $blog = Blog::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($blog->image) {
                $newFile = $request->file('image');
                $fileToDelete = $blog->image;
                $imageUploadResponse = $this->fileStorageService->updateFileFromCloud($fileToDelete, $newFile);
                $data['image'] = $imageUploadResponse['public_path'];
            } else {
                $image = $request->file('image');
                $imageUploadResponse = $this->fileStorageService->uploadImageToCloud($image, 'blog');
                $data['image'] = $imageUploadResponse['public_path'];
            }
        }

        $data['slug'] = Str::of($data['slug'] ?? $data['title'])->slug('-');

        $blog->update($data);

        Toastr::success('Blog updated successfully.');
        return redirect()->route('blog.list');
    }

    /**
     * Toggle blog status
     */
    public function toggleStatus($id)
    {
        try {
            $blog = Blog::findOrFail($id);

            if ($blog->status) {
                $blog->status = false;
                $blog->save();
            } else {
                $blog->status = true;
                $blog->save();
            }
            
            Toastr::success('Status changed successfully.');
            return redirect()->route('blog.list');
        } catch (\Exception $e) {
            Toastr::error('Status not changed');
            return redirect()->route('blog.list');
        }
    }

    /**
     * Toggle blog featured status
     */
    public function toggleFeatured($id)
    {
        try {
            $blog = Blog::findOrFail($id);

            if ($blog->is_featured) {
                $blog->is_featured = false;
                $blog->save();
            } else {
                $blog->is_featured = true;
                $blog->save();
            }
            
            Toastr::success('Featured status changed successfully.');
            return redirect()->route('blog.list');
        } catch (\Exception $e) {
            Toastr::error('Featured status not changed');
            return redirect()->route('blog.list');
        }
    }
}
