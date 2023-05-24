<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',

    ];

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            $category->slug = \Str::slug($category->name);
        });
    }
    // when update category, slug will be updated
    public function updateSlug(): void
    {
        $this->slug = \Str::slug($this->name);
        $this->save();
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'category_shop', 'category_id', 'shop_id');
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }

    public function scopeSlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    public function scopeWithShops($query)
    {
        return $query->with('shops');
    }
}
