<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'description',
        'image_path',
        'external_api_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the full URL for the product image.
     *
     * @return string|null
     */
    public function getImageUrlAttribute()
    {
        if (Storage::disk('public')->exists($this->image_path)) {
            return asset('storage/' . $this->image_path);
        }
        return null;
    }

    /**
     * Get formatted price with currency symbol.
     *
     * @return string
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }
}