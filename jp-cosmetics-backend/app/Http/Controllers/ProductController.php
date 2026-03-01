<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RequestProduct;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\FileStorageService;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\ProductRequest;
use App\Models\ProductAttributeImage;

class ProductController extends Controller
{
    protected $fileStorageService;

    public function __construct(FileStorageService $fileStorageService)
    {
        $this->fileStorageService = $fileStorageService;
    }

    public function list(Request $request)
    {
        $query = Product::with(['attributes', 'category', 'brand']);

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhereHas('brand', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $products = $query->latest()->paginate(10);

        return view('products.list', compact('products'));
    }

    public function view($id)
    {
        $product = Product::with(['category', 'brand', 'attributes.attribute_images'])->findOrFail($id);
        return view('products.view', compact('product'));
    }


    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get();

        return view('products.create', compact('categories', 'brands'));
    }

    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            if ($request->hasFile('primary_image')) {
                $image = $request->file('primary_image');
                $imageUploadResponse = $this->fileStorageService->uploadImageToCloud($image, 'product');
                $data['primary_image'] = $imageUploadResponse['public_path'];
            } else {
                $data['primary_image'] = null;
            }

            $data['slug'] = Str::of($data['name'])->slug('-');


            $product = Product::create([
                'name'              => $data['name'],
                'slug'              => $data['slug'],
                'category_id'       => $data['category_id'],
                'brand_id'          => $data['brand_id'] ?? null,
                'primary_image'     => $data['primary_image'],
                'product_type'      => $data['product_type'] ?? 'single',
                'status'            => $data['status'] ?? 'active',
                'short_description' => $data['short_description'],
                'long_description'  => $data['long_description'],
                'ingredients'       => $data['ingredients'],
                'how_to_use'        => $data['how_to_use'],
            ]);

            if (isset($data['attributes']) && count($data['attributes']) > 0) {
                $defaultIndex = null;

                foreach ($data['attributes'] as $i => $attr) {
                    if (!empty($attr['is_default'])) {
                        $defaultIndex = $i;
                    }

                    $isEmpty = (empty($attr['attribute_name']) && empty($attr['attribute_value']) && empty($attr['unit_price']));
                    if ($isEmpty) continue;

                    $isDefault = ($defaultIndex === $i) ? 1 : 0;

                    $product_attribute = ProductAttribute::create([
                        'product_id'      => $product->id,
                        'attribute_name'  => $attr['attribute_name'] ?? '',
                        'attribute_value' => $attr['attribute_value'] ?? '',
                        'unit_price'      => $attr['unit_price'] ?? null,
                        'stock'           => $attr['stock'] ?? 0,
                        'min_order'       => $attr['min_order'] ?? 1,
                        'max_order'       => $attr['max_order'] ?? null,
                        'discount_type'   => $attr['discount_type'] ?? null,
                        'discount_amount' => $attr['discount_amount'] ?? null,
                        'status'          => isset($attr['status']) ? (int)$attr['status'] : 1,
                        'is_default'      => $isDefault
                    ]);

                    $filesForThisAttr = $attr['attribute_images'] ?? [];

                    if (is_array($filesForThisAttr) && count($filesForThisAttr)) {

                        foreach ($filesForThisAttr as $imgFile) {
                            if (!$imgFile) continue;

                            $uploadResp = $this->fileStorageService->uploadImageToCloud($imgFile, 'product-attribute');
                            $publicPath = $uploadResp['public_path'] ?? null;

                            ProductAttributeImage::create([
                                'attribute_id' => $product_attribute->id,
                                'image_path'   => $publicPath
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            Toastr::success('Product created successfully.');
            return redirect()->route('product.list');
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Product creation failed. Please try again.');
            return redirect()->route('product.list');
        }
    }

    public function edit($id)
    {
        $product = Product::with('attributes', 'attributes.attribute_images')->findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(ProductRequest $request, $id)
    {

        DB::beginTransaction();

        try {
            $product = Product::with('attributes')->findOrFail($id);
            $data = $request->validated();

            if ($request->hasFile('primary_image')) {
                if ($product->primary_image) {
                    $newFile = $request->file('primary_image');
                    $fileToDelete = $product->primary_image;
                    $imageUploadResponse = $this->fileStorageService->updateFileFromCloud($fileToDelete, $newFile);
                    $data['primary_image'] = $imageUploadResponse['public_path'];
                } else {
                    $image = $request->file('primary_image');
                    $imageUploadResponse = $this->fileStorageService->uploadImageToCloud($image, 'product');
                    $data['primary_image'] = $imageUploadResponse['public_path'];
                }
            } else {
                $data['primary_image'] = $product->primary_image;
            }

            $data['slug'] = Str::of($data['name'])->slug('-');


            $product->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'category_id' => $data['category_id'],
                'brand_id' => $data['brand_id'] ?? null,
                'primary_image'     => $data['primary_image'],
                'product_type' => $data['product_type'] ?? 'single',
                'status' => $data['status'] ?? 'active',
                'short_description' => $data['short_description'],
                'long_description'  => $data['long_description'],
                'ingredients'       => $data['ingredients'],
                'how_to_use'        => $data['how_to_use'],
            ]);

            $existingAttributeIds = $product->attributes->pluck('id')->toArray();
            $submittedAttributeIds = collect($data['attributes'] ?? [])->pluck('id')->filter()->toArray();

            $removedAttributes = array_diff($existingAttributeIds, $submittedAttributeIds);
            if (count($removedAttributes) > 0) {
                ProductAttribute::whereIn('id', $removedAttributes)->delete();
            }

            if (isset($data['attributes']) && count($data['attributes']) > 0) {
                $defaultIndex = null;
                foreach ($data['attributes'] as $i => $attr) {
                    if (!empty($attr['is_default'])) {
                        $defaultIndex = $i;
                    }
                    if (empty($attr['attribute_name']) && empty($attr['attribute_value'])) continue;

                    $attributeId = $attr['id'] ?? null;
                    $isDefault = ($defaultIndex === $i) ? 1 : 0;
                    if ($attributeId) {
                        $product_attribute = ProductAttribute::find($attributeId);
                        if ($product_attribute) {
                            $product_attribute->update([
                                'attribute_name' => $attr['attribute_name'] ?? '',
                                'attribute_value' => $attr['attribute_value'] ?? '',
                                'unit_price' => $attr['unit_price'] ?? null,
                                'stock' => $attr['stock'] ?? 0,
                                'min_order' => $attr['min_order'] ?? 1,
                                'max_order' => $attr['max_order'] ?? null,
                                'discount_type' => $attr['discount_type'] ?? null,
                                'discount_amount' => $attr['discount_amount'] ?? null,
                                'status' => isset($attr['status']) ? (int)$attr['status'] : 1,
                                'is_default' => $isDefault
                            ]);
                        }
                    } else {
                        $product_attribute = ProductAttribute::create([
                            'product_id' => $id,
                            'attribute_name' => $attr['attribute_name'] ?? '',
                            'attribute_value' => $attr['attribute_value'] ?? '',
                            'unit_price' => $attr['unit_price'] ?? null,
                            'stock' => $attr['stock'] ?? 0,
                            'min_order' => $attr['min_order'] ?? 1,
                            'max_order' => $attr['max_order'] ?? null,
                            'discount_type' => $attr['discount_type'] ?? null,
                            'discount_amount' => $attr['discount_amount'] ?? null,
                            'status' => isset($attr['status']) ? (int)$attr['status'] : 1,
                            'is_default' => !empty($attr['is_default']) ? 1 : 0,
                        ]);
                    }

                   
                    if (isset($attr['attribute_images'])) {
                        $filesForThisAttr = $attr['attribute_images'];
                        if (count($filesForThisAttr) > 0) {

                            ProductAttributeImage::where('attribute_id', $product_attribute->id)->delete();

                            foreach ($filesForThisAttr as $imgFile) {
                                if (!$imgFile) continue;
                                $uploadResp = $this->fileStorageService->uploadImageToCloud($imgFile, 'product-attribute');
                                $publicPath = $uploadResp['public_path'] ?? null;

                                ProductAttributeImage::create([
                                    'attribute_id' => $product_attribute->id,
                                    'image_path' => $publicPath,
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            Toastr::success('Product updated successfully.');
            return redirect()->route('product.list');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product update failed: ' . $e->getMessage());
            Toastr::error('Product update failed. Please try again.');
            return redirect()->route('product.list');
        }
    }


    public function toggleStatus($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->status) {
                $product->status = false;
                $product->save();
            } else {
                $product->status = true;
                $product->save();
            }
            Toastr::success('Status changed successfully.');
            return redirect()->route('product.list');
        } catch (\Exception $e) {
            Toastr::success('Status not changed');
            return redirect()->route('product.list');
        }
    }

    public function productRequestindex()
    {
        $requests = RequestProduct::with('customer')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.product_requests_index', compact('requests'));
    }

    public function deleteAttributeImage($id)
    {
        $image = ProductAttributeImage::findOrFail($id);
        // optional: delete from cloud storage 
        // $this->fileStorageService->delete($image->image_path);
        $image->delete();
        return response()->json(['success' => true]);
    }

    public function duplicate($id)
    {
        DB::beginTransaction();
        try {
            $original = Product::with('attributes.attribute_images')->findOrFail($id);

            // New unique slug
            $newSlug = Str::slug($original->name) . '-copy';

            // Duplicate product
            $newProduct = $original->replicate();
            $newProduct->slug = $newSlug;
            $newProduct->name = $original->name . ' (Copy)';
            $newProduct->save();

            // Duplicate attributes + images
            foreach ($original->attributes as $attr) {
                $newAttr = $attr->replicate();
                $newAttr->product_id = $newProduct->id;
                $newAttr->save();

                foreach ($attr->attribute_images as $img) {
                    $newImg = $img->replicate();
                    $newImg->attribute_id = $newAttr->id;
                    $newImg->save();
                }
            }

            DB::commit();
            Toastr::success('Product duplicated successfully.');
            return redirect()->route('product.list');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product duplicate failed: ' . $e->getMessage());
            Toastr::error('Duplicate failed. Please try again.');
            return redirect()->route('product.list');
        }
    }
}
