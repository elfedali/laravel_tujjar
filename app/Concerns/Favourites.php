<?php

namespace App\Concerns;

use App\Models\Favourite;
use App\Models\User;

trait Favourites
{
    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouritable');
    }

    public function favourite(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        if ($this->isFavouritedBy(auth()->user())) {
            return false;
        }

        $this->favourites()->create([
            'user_id' => auth()->id(),
        ]);

        return true;
    }

    public function unfavourite(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        if (!$this->isFavouritedBy(auth()->user())) {
            return false;
        }

        $this->favourites()
            ->where('user_id', auth()->id())
            ->delete();

        return true;
    }

    public function isFavouritedBy(User $user)
    {
        return (bool) $this->favourites()
            ->where('user_id', $user->id)
            ->count();
    }
}
