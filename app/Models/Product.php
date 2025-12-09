<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasOne};

class Product extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal
     * 
     * @var array<string>
     */
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'category', 'image', 'stock', 'low_stock_threshold',
        'status', 'view_count', 'sale_count', 'is_featured', 'is_new'
    ];

    /**
     * Tipe casting untuk atribut
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'view_count' => 'integer',
        'sale_count' => 'integer',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
    ];

    /**
     * Dapatkan URL gambar produk
     * 
     * @return string
     */
    public function getImageUrl(): string
    {
        if (!$this->image) {
            return 'https://via.placeholder.com/400x300?text=No+Image';
        }

        $path = ltrim($this->image, '/');

        // URL lengkap sudah ada
        if (str_starts_with($path, 'http')) {
            return $this->image;
        }

        // Folder public img
        if (str_starts_with($path, 'img/')) {
            return asset($path);
        }

        // Sudah mengarah ke storage/
        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        // Default: asumsikan path di bawah storage/app/public
        return asset('storage/' . $path);
    }

    /**
     * Relasi: Produk memiliki banyak review
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relasi: Produk memiliki banyak favorit
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Relasi: Produk memiliki banyak varian
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Relasi: Produk memiliki satu promosi aktif
     */
    public function activePromotion(): HasOne
    {
        return $this->hasOne(Promotion::class)->where('is_active', true);
    }

    /**
     * Dapatkan rata-rata rating produk
     * 
     * @return float
     */
    public function getAverageRating(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Dapatkan jumlah review produk
     * 
     * @return int
     */
    public function getReviewCount(): int
    {
        return $this->reviews()->count();
    }

    /**
     * Cek apakah produk masih tersedia
     * 
     * @return bool
     */
    public function isInStock(): bool
    {
        return $this->stock > 0 && $this->status !== 'out_of_stock';
    }

    /**
     * Update status stok berdasarkan jumlah
     * 
     * @return void
     */
    public function updateStockStatus(): void
    {
        $this->status = match(true) {
            $this->stock == 0 => 'out_of_stock',
            $this->stock < 10 => 'low_stock',
            default => 'in_stock'
        };
        $this->save();
    }

    /**
     * Dapatkan harga final setelah promosi diterapkan
     * 
     * @return int
     */
    public function getFinalPrice(): int
    {
        if ($this->activePromotion) {
            return $this->activePromotion->getDiscountedPrice($this->price);
        }
        return $this->price;
    }

    /**
     * Scope: Dapatkan produk terlaris berdasarkan jumlah penjualan
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getBestSellers($limit = 10)
    {
        return self::where('sale_count', '>', 0)
            ->orderBy('sale_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Scope: Dapatkan produk unggulan
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFeatured($limit = 6)
    {
        return self::where('is_featured', true)
            ->limit($limit)
            ->get();
    }

    /**
     * Scope: Dapatkan produk terbaru
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getNew($limit = 10)
    {
        return self::where('is_new', true)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
