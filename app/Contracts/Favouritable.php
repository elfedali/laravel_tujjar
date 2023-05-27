<?php

namespace App\Contracts;

use App\Models\User;

/**
 * Interface Favouritable, implemented by models that can be favourited.
 */
interface Favouritable
{
    public function favourites();
    public function favourite(): bool;
    public function unfavourite(): bool;
    public function isFavouritedBy(User $user);
}
