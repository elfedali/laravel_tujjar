<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\Favouritable;
use App\Concerns\Favourites;

class Shop extends Model implements Favouritable
{
    use HasFactory;
    use Favourites;
    use HasSlug;

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
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
    // scope where name like
    public function scopeNameLike($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }
}
