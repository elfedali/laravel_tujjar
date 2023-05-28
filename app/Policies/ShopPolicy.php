<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ShopPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
    }

    /**
     * Determine whether the user can view the model.
     * @param User|null $user
     * @param Shop $shop
     */
    public function view(?User $user, Shop $shop): bool
    {
        if ($shop->is_enabled) {
            return true;
        }

        if ($user && $user->id === $shop->owner_id) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     * @param User|null $user
     * @param Shop $shop
     * @return bool
     */
    public function viewOwner(?User $user, Shop $shop): bool
    {
        return $this->view($user, $shop);
    }

    /**
     * Determine whether the user can view the model.
     * @param User|null $user
     * @param Shop $shop
     * @return bool
     */
    public function viewCategories(?User $user, Shop $shop): bool
    {
        return $this->view($user, $shop);
    }

    /**
     * Determine whether the user can view the model.
     * @param User|null $user
     * @param Shop $shop
     * @return bool
     */
    public function viewTags(?User $user, Shop $shop): bool
    {
        return $this->view($user, $shop);
    }

    /**
     * Determine whether the user can view the model.
     * @param User|null $user
     * @param Shop $shop
     * @return bool
     */
    public function viewReviews(?User $user, Shop $shop): bool
    {
        return $this->view($user, $shop);
    }

    /**
     * Determine whether the user can view the model.
     * @param User|null $user
     * @param Shop $shop
     * @return bool
     */
    public function viewImages(?User $user, Shop $shop): bool
    {
        return $this->view($user, $shop);
    }


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Shop $shop): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Shop $shop): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Shop $shop): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Shop $shop): bool
    {
        //
    }
}
