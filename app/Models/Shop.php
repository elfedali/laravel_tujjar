<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;


    protected $fillable = [
        'owner_id',

        'name',
        'description',
        'slug',
        'phone_number',

        'address',
        'city',
        'zip_code',
        'country',

        'logo_photo',
        'cover_photo',

        'is_enabled',
        'is_verified',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_shop', 'shop_id', 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'shop_tag', 'shop_id', 'tag_id');
    }

    public function images()
    {
        return $this->belongsToMany(Image::class, 'image_shop', 'shop_id', 'image_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }

    public function scopeSlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    public function scopeWithCategories($query)
    {
        return $query->with('categories');
    }

    public function scopeWithTags($query)
    {
        return $query->with('tags');
    }

    public function scopeWithImages($query)
    {
        return $query->with('images');
    }

    public function scopeWithReviews($query)
    {
        return $query->with('reviews');
    }

    public function scopeWithOwner($query)
    {
        return $query->with('owner');
    }

    // slug will be created automatically
    protected static function booted(): void
    {
        static::creating(function (Shop $shop) {
            // make it unique slug
            $slug = \Str::slug($shop->name);
            $count = Shop::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            $shop->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }
    // when update shop, slug will be updated
    public function updateSlug(): void
    {
        // make it unique slug
        $slug = \Str::slug($this->name);
        $count = Shop::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
        $this->slug = $count ? "{$slug}-{$count}" : $slug;
        $this->save();
    }
}
