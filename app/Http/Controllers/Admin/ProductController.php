<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->input('q').'%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        $products = $query->orderByDesc('id')->paginate(20);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $product = new Product(['active' => true]);

        return view('admin.products.form', compact('categories', 'product'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request->file('image'));
        }
        if ($request->hasFile('hover_image')) {
            $data['hover_image'] = $this->storeImage($request->file('hover_image'));
        }

        $product = Product::create($data);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $product->images()->create([
                    'image' => $this->storeImage($file),
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit « '.$product->name.' » créé.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('image')) {
            $this->deleteStoredFile($product->image);
            $data['image'] = $this->storeImage($request->file('image'));
        }
        if ($request->hasFile('hover_image')) {
            $this->deleteStoredFile($product->hover_image);
            $data['hover_image'] = $this->storeImage($request->file('hover_image'));
        }

        $product->update($data);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $product->images()->create([
                    'image' => $this->storeImage($file),
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit « '.$product->name.' » modifié.');
    }

    public function destroy(Product $product)
    {
        $this->deleteStoredFile($product->image);
        $this->deleteStoredFile($product->hover_image);
        foreach ($product->images as $image) {
            $this->deleteStoredFile($image->image);
            $image->delete();
        }

        $product->delete();

        return back()->with('success', 'Produit supprimé.');
    }

    public function toggle(Product $product)
    {
        $product->update(['active' => ! $product->active]);

        return back()->with('success', $product->active ? 'Produit activé.' : 'Produit désactivé.');
    }

    public function destroyImage(Request $request, int $imageId)
    {
        $image = \App\Models\ProductImage::findOrFail($imageId);
        $this->deleteStoredFile($image->image);
        $image->delete();

        return back()->with('success', 'Image supprimée.');
    }

    public function setMainImage(Product $product, int $imageId)
    {
        $image = \App\Models\ProductImage::where('product_id', $product->id)->findOrFail($imageId);
        $product->update(['image' => $image->image]);

        return back()->with('success', 'Image définie comme image principale.');
    }

    public function setHoverImage(Product $product, int $imageId)
    {
        $image = \App\Models\ProductImage::where('product_id', $product->id)->findOrFail($imageId);
        $product->update(['hover_image' => $image->image]);

        return back()->with('success', 'Image définie comme image au survol.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => [
                'nullable',
                Rule::when(fn ($input) => ! in_array($input['category_id'] ?? null, [null, 'new'], true), Rule::exists('categories', 'id')),
            ],
            'new_category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'gender' => 'required|in:homme,femme',
            'image' => 'nullable|image|max:4096',
            'hover_image' => 'nullable|image|max:4096',
            'gallery.*' => 'nullable|image|max:4096',
            'active' => 'nullable|boolean',
        ], [
            'name.required' => 'Le nom du produit est obligatoire.',
            'price.required' => 'Le prix est obligatoire.',
        ]);

        if (! empty($data['new_category'])) {
            $data['category_id'] = $this->findOrCreateCategory($data['new_category']);
        } elseif (($data['category_id'] ?? null) === 'new') {
            $data['category_id'] = null;
        }

        $data['active'] = $request->boolean('active');
        $data['slug'] = Str::slug($data['name']);

        while (Product::where('slug', $data['slug'])->where('id', '!=', $request->route('product')?->id ?? 0)->exists()) {
            $data['slug'] .= '-'.Str::lower(Str::random(3));
        }

        return $data;
    }

    private function findOrCreateCategory(string $name): int
    {
        $name = trim($name);
        $slug = Str::slug($name);

        $category = Category::where('slug', $slug)->first();
        if (! $category) {
            $category = Category::create([
                'name' => $name,
                'slug' => $slug,
            ]);
        }

        return $category->id;
    }

    private function deleteStoredFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (config('filesystems.disks.supabase.bucket')) {
            try {
                \Illuminate\Support\Facades\Storage::disk('supabase')->delete($path);
            } catch (\Throwable) {
                // ignore
            }

            return;
        }

        if (str_starts_with($path, 'products/')) {
            $file = public_path($path);
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }

    private function storeImage(\Illuminate\Http\UploadedFile $file): string
    {
        $filename = Str::random(20).'.'.$file->getClientOriginalExtension();
        $path = 'products/'.$filename;

        if (config('filesystems.disks.supabase.bucket')) {
            \Illuminate\Support\Facades\Storage::disk('supabase')->put($path, file_get_contents($file->getRealPath()), 'public');

            return $path;
        }

        $file->move(public_path('products'), $filename);

        return $path;
    }
}
