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
