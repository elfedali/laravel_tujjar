<?php

namespace App\Models;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    public const IMAGE_SIZES = [
        'small' => [300, 300],
        'medium' => [600, 600],
        'large' => [900, 900],
    ];

    protected $fillable = [
        'shop_id',
        'path',
        'is_main',
        'position',
        // 'small',
        // 'medium',
        // 'large',
    ];
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
    public function getSmallUrlAttribute()
    {
        return asset('storage/' . $this->small);
    }
    public function getMediumUrlAttribute()
    {
        return asset('storage/' . $this->medium);
    }
    public function getLargeUrlAttribute()
    {
        return asset('storage/' . $this->large);
    }
    public function delete()
    {
        \Storage::delete([
            $this->path,
            $this->small,
            $this->medium,
            $this->large,
        ]);
        return parent::delete();
    }
    public function resizeAndSaveImages(): void
    {
        $image = \Image::make($this->path);
        foreach (self::IMAGE_SIZES as $size => $dimensions) {
            $image->resize(...$dimensions)->save(storage_path('app/public/' . $this->{$size}));
        }
    }

    protected static function booted(): void
    {
        static::creating(function (Image $image) {
            $image->resizeAndSaveImages();
        });
    }
    public function update(array $attributes = [], array $options = [])
    {
        $result = parent::update($attributes, $options);
        $this->resizeAndSaveImages();
        return $result;
    }
}
