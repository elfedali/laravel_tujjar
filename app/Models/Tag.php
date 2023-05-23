<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected static function booted(): void
    {
        static::creating(function (Tag $tag) {
            $tag->slug = \Str::slug($tag->name);
        });
    }
    // when update tag, slug will be updated
    public function updateSlug(): void
    {
        $this->slug = \Str::slug($this->name);
        $this->save();
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_tag', 'tag_id', 'shop_id');
    }
}
