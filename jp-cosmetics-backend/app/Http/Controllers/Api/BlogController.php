<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Blogcategory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Get all active blogs with pagination
     */
    public function index(): JsonResponse
    {
        try {
            $blogs = Blog::with('category:id,name,slug')
                ->select('id', 'category_id', 'title', 'slug', 'short_description', 'content', 'image', 'author', 'is_featured', 'created_at')
                ->where('status', 1)
                ->orderByDesc('created_at')
                ->paginate(12);

            // Transform image paths if needed
            $blogs->getCollection()->transform(function ($blog) {
                $blog->image = $blog->image;
                return $blog;
            });

            return $this->responseWithSuccess($blogs, 'Blogs fetched successfully', 200);

        } catch (Exception $e) {
            return $this->responseWithError('Something went wrong', [$e->getMessage()], 500);
        }
    }

    /**
     * Get blogs by category slug
     */
    public function blogsByCategory($slug): JsonResponse
    {
        try {
            // Find category by slug
            $category = Blogcategory::where('slug', $slug)
                ->where('status', 1)
                ->first();

            if (!$category) {
                return $this->responseWithError('Category not found', [], 404);
            }

            // Get blogs for this category
            $blogs = Blog::with('category:id,name,slug')
                ->select('id', 'category_id', 'title', 'slug', 'short_description', 'content', 'image', 'author', 'is_featured', 'created_at')
                ->where('category_id', $category->id)
                ->where('status', 1)
                ->orderByDesc('created_at')
                ->paginate(12);

            // Transform image paths if needed
            $blogs->getCollection()->transform(function ($blog) {
                $blog->image = $blog->image;
                return $blog;
            });

            return $this->responseWithSuccess([
                'category' => $category,
                'blogs' => $blogs
            ], 'Category blogs fetched successfully', 200);

        } catch (Exception $e) {
            return $this->responseWithError('Something went wrong', [$e->getMessage()], 500);
        }
    }

    /**
     * Get featured blogs
     */
    public function featuredBlogs(): JsonResponse
    {
        try {
            $blogs = Blog::with('category:id,name,slug')
                ->select('id', 'category_id', 'title', 'slug', 'short_description', 'content', 'image', 'author', 'is_featured', 'created_at')
                ->where('status', 1)
                ->where('is_featured', 1)
                ->orderByDesc('created_at')
                ->take(6)
                ->get();

            // Transform image paths if needed
            $blogs->transform(function ($blog) {
                $blog->image = $blog->image;
                return $blog;
            });

            return $this->responseWithSuccess($blogs, 'Featured blogs fetched successfully', 200);

        } catch (Exception $e) {
            return $this->responseWithError('Something went wrong', [$e->getMessage()], 500);
        }
    }

    /**
     * Show single blog by slug
     */
    public function show($slug): JsonResponse
    {
        try {
            $blog = Blog::with('category:id,name,slug')
                ->select('id', 'category_id', 'title', 'slug', 'short_description', 'content', 'image', 'author', 'is_featured', 'created_at')
                ->where('slug', $slug)
                ->where('status', 1)
                ->first();

            if (!$blog) {
                return $this->responseWithError('Blog not found', [], 404);
            }

            // Transform image path if needed
            $blog->image = $blog->image;

            return $this->responseWithSuccess($blog, 'Blog fetched successfully', 200);

        } catch (Exception $e) {
            return $this->responseWithError('Something went wrong', [$e->getMessage()], 500);
        }
    }

    /**
     * Get all active blog categories
     */
    public function categories(): JsonResponse
    {
        try {
            $categories = Blogcategory::withCount(['blogs' => function($query) {
                    $query->where('status', 1);
                }])
                ->select('id', 'name', 'slug', 'description')
                ->where('status', 1)
                ->orderBy('name')
                ->get();

            return $this->responseWithSuccess($categories, 'Blog categories fetched successfully', 200);

        } catch (Exception $e) {
            return $this->responseWithError('Something went wrong', [$e->getMessage()], 500);
        }
    }


}
