<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favouritable_id',
        'favouritable_type',
    ];

    /**
     * Get the owning favoritable model. (shop, product, etc.)
     */
    public function favouritable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
