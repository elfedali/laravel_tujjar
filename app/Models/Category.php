<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
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
}
