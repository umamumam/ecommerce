<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->get();
        $categories = Category::where('is_active', true)->get();
        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'sold_count' => 'nullable|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_urls' => 'nullable|string',
            'description' => 'nullable|string',
            'variant_1_name' => 'nullable|string|max:50',
            'variant_1_options' => 'nullable|string',
            'variant_2_name' => 'nullable|string|max:50',
            'variant_2_options' => 'nullable|string',
        ]);

        $data = $request->except('images', 'image_urls', 'variant_1_options', 'variant_2_options');
        $data['slug'] = Str::slug($request->name) . '-' . time();
        $data['is_active'] = true;

        $imagesList = [];
        if ($request->image_urls) {
            $urls = array_map('trim', explode(',', $request->image_urls));
            $imagesList = array_merge($imagesList, $urls);
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagesList[] = $file->store('products', 'public');
            }
        }
        $data['images'] = $imagesList;

        if ($request->variant_1_options) {
            $data['variant_1_options'] = array_map('trim', explode(',', $request->variant_1_options));
        }
        if ($request->variant_2_options) {
            $data['variant_2_options'] = array_map('trim', explode(',', $request->variant_2_options));
        }

        Product::create($data);

        return back()->with('success', 'Produk baru berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        // For AJAX requests (from the edit button), return JSON
        if (request()->ajax()) {
            return response()->json($product);
        }
        
        // For normal web request (View Detail), return view
        return view('products.show', compact('product'));
    }

    public function userShow($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
                                    ->where('id', '!=', $product->id)
                                    ->take(4)
                                    ->get();

        return view('welcome-product-detail', compact('product', 'relatedProducts'));
    }

    public function catalog(Request $request)
    {
        $categories = Category::where('is_active', true)->get();

        $query = Product::with('category')->where('is_active', true);

        // Search query
        $searchTerm = trim($request->get('q', ''));
        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Category filter (slug or id)
        $categorySlug = $request->get('category');
        $currentCategory = null;
        if (!empty($categorySlug)) {
            $currentCategory = Category::where('slug', $categorySlug)->first();
            if (!$currentCategory && is_numeric($categorySlug)) {
                $currentCategory = Category::find($categorySlug);
            }
            if ($currentCategory) {
                $query->where('category_id', $currentCategory->id);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(15)->withQueryString();

        return view('catalog.index', compact('products', 'categories', 'currentCategory', 'searchTerm', 'sort'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'sold_count' => 'nullable|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_urls' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'variant_1_name' => 'nullable|string|max:50',
            'variant_1_options' => 'nullable|string',
            'variant_2_name' => 'nullable|string|max:50',
            'variant_2_options' => 'nullable|string',
        ]);

        $data = $request->except('images', 'image_urls', 'variant_1_options', 'variant_2_options');
        $data['slug'] = Str::slug($request->name) . '-' . $product->id;

        $imagesList = [];
        if ($request->image_urls || $request->hasFile('images')) {
            if ($product->images) {
                foreach ($product->images as $img) {
                    if (!Str::startsWith($img, 'http')) {
                        Storage::disk('public')->delete($img);
                    }
                }
            }
            if ($request->image_urls) {
                $imagesList = array_map('trim', explode(',', $request->image_urls));
            }
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $imagesList[] = $file->store('products', 'public');
                }
            }
            $data['images'] = $imagesList;
        }

        $data['variant_1_options'] = $request->variant_1_options ? array_map('trim', explode(',', $request->variant_1_options)) : null;
        $data['variant_2_options'] = $request->variant_2_options ? array_map('trim', explode(',', $request->variant_2_options)) : null;

        $product->update($data);

        return back()->with('success', 'Data produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        if ($product->images) {
            foreach ($product->images as $img) {
                if (!Str::startsWith($img, 'http')) {
                    Storage::disk('public')->delete($img);
                }
            }
        }
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus');
    }
}
