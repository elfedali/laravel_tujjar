<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Contracts\Favouritable;
use App\Concerns\Favourites;

class Shop extends Model implements Favouritable
{
    use HasFactory, Favourites;

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

        'is_approved', // when the shop is approved by admin (boolean)
        'approved_at',
    ];
    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'is_enabled' => 'boolean',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime'
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

    /**
     * 
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shop) {
            $shop->slug = $shop->generateUniqueSlug($shop->name);
        });

        static::updating(function ($shop) {
            $shop->slug = $shop->generateUniqueSlug($shop->name, $shop->id);
        });
    }

    protected function generateUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $count = Shop::where('id', '!=', $id)
            ->whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
