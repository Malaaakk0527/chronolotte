<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin();
        $this->seedCategories();
        $this->importProductsFromHtml();
    }

    private function seedAdmin(): void
    {
        if (! Admin::where('username', 'admin')->exists()) {
            Admin::create([
                'name' => 'Chronolette Admin',
                'username' => 'admin',
                'password' => 'admin123', // changera via le dashboard plus tard si besoin
            ]);
            $this->command?->info('Admin créé : admin / admin123');
        }
    }

    private function seedCategories(): void
    {
        $categories = [
            'Tissot' => 'tissot',
            'Hugo Boss' => 'hugo-boss',
            'Guess' => 'guess',
            'Michael Kors' => 'michael-kors',
            'Tommy Hilfiger' => 'tommy-hilfiger',
            'Emporio Armani' => 'emporio-armani',
        ];

        foreach ($categories as $name => $slug) {
            Category::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }
    }

    private function importProductsFromHtml(): void
    {
        $file = __DIR__.'/source/index.html';
        if (! is_file($file)) {
            $this->command?->warn('index.html introuvable, aucun produit importé.');
            return;
        }

        $html = file_get_contents($file);
        $regex = '/(<div class="product-small col[^>]*post-(\d+)[^>]*>)(.*?)(?=<div class="product-small col|\z)/si';
        preg_match_all($regex, $html, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            $this->command?->warn('Aucun produit détecté dans index.html.');
            return;
        }

        $this->command?->info(count($matches).' produits trouvés dans index.html, import en cours…');

        foreach ($matches as $i => $m) {
            $block = $m[1].$m[3];

            if (preg_match('/<p class="name product-title"><a[^>]*>([^<]+)<\/a>/i', $block, $t)) {
                $name = trim($t[1]);
            } else {
                $name = "Produit {$m[1]}";
            }

            preg_match_all('/<img[^>]*src="([^"]+)"/i', $block, $imgs);
            $image = $this->fixImageExtension($imgs[1][0] ?? null);
            $hoverImage = $this->fixImageExtension($imgs[1][1] ?? null);

            $oldPrice = $this->extractPrice('/<del[^>]*>.*?<bdi>\s*([\d\s.,\x{00A0}]+)/si', $block);
            $price = $this->extractPrice('/<ins[^>]*>.*?<bdi>\s*([\d\s.,\x{00A0}]+)/si', $block);
            if ($price === null) {
                $price = $this->extractPrice('/<bdi>\s*([\d\s.,\x{00A0}]+)/i', $block);
            }
            if ($price === null) {
                $price = 0;
            }

            $slug = Product::slugify($name) ?: 'produit-'.$m[1];
            if (Product::where('slug', $slug)->exists()) {
                $slug .= '-'.$m[1];
            }

            $gender = str_contains($block, 'montres-femmes') ? 'femme' : 'homme';

            $product = Product::create([
                'category_id' => null, // assigné ci-dessous
                'name' => $name,
                'slug' => $slug,
                'description' => null,
                'price' => $price,
                'old_price' => $oldPrice,
                'stock' => 10,
                'gender' => $gender,
                'image' => $image,
                'hover_image' => $hoverImage,
                'active' => true,
            ]);

            // catégorie depuis le bloc produit
            $normalized = preg_replace('/[-_\s]/', '', mb_strtolower($block));
            foreach (Category::all() as $cat) {
                $needle = preg_replace('/[-_\s]/', '', mb_strtolower($cat->slug));
                if ($needle && str_contains($normalized, $needle)) {
                    $product->category_id = $cat->id;
                    $product->save();
                    break;
                }
            }

            // images supplémentaires de la galerie (variantes _1, _2 …)
            $base = $product->image;
            if ($base) {
                $pathinfo = pathinfo($base);
                for ($n = 1; $n <= 6; $n++) {
                    $candidate = $pathinfo['dirname'].'/'.$pathinfo['filename'].'_'.$n.'.'.$pathinfo['extension'];
                    if (is_file(public_path($candidate))) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image' => $candidate,
                        ]);
                    }
                }
            }
        }

        $this->command?->info('Import terminé : '.Product::count().' produits.');
    }

    private function extractPrice(string $pattern, string $block): ?float
    {
        if (preg_match($pattern, $block, $p)) {
            return $this->toFloat($p[1]);
        }
        return null;
    }

    private function fixImageExtension(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $file = public_path($path);
        if (! is_file($file)) {
            return $path;
        }

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $head = file_get_contents($file, false, null, 0, 16);
        $real = match (true) {
            str_starts_with($head, "\xFF\xD8\xFF") => 'jpg',
            str_starts_with($head, "\x89PNG\r\n\x1A\n") => 'png',
            str_starts_with($head, 'GIF8') => 'gif',
            str_starts_with($head, 'RIFF') => 'webp',
            default => null,
        };

        if (! $real || ($real === 'jpg' && in_array($ext, ['jpg', 'jpeg'], true)) || $real === $ext) {
            return $path;
        }

        $newPath = substr($path, 0, -strlen($ext)).$real;
        $newFile = public_path($newPath);
        if (! is_file($newFile)) {
            copy($file, $newFile);
            $this->command?->info("Image renommée : {$path} → {$newPath}");
        }

        return $newPath;
    }

    private function toFloat(string $value): float
    {
        $value = trim($value);
        $value = str_replace(["\x{00A0}", ' ', "\t"], '', $value);
        $value = str_replace([',', '.'], '', $value);

        return (float) $value;
    }
}
