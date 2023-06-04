<?php

namespace App\JsonApi\V1\Shops;

use App\Models\Shop;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsToMany;
use LaravelJsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;


class ShopSchema extends Schema
{

    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = Shop::class;


    protected ?array $defaultPagination = ['number' => 1, 'size' => 10];


    /**
     * The maximum include path depth.
     *
     * @var int
     */
    protected int $maxDepth = 3;


    /**
     * Get the resource fields.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            ID::make(),

            Str::make('name'),
            Str::make('slug'),
            Str::make('description'),
            Str::make('phoneNumber'),
            Str::make('address'),
            Str::make('city'),
            Number::make('zipCode'),
            Str::make('country'),
            Str::make('logoPhoto'),
            Str::make('coverPhoto'),
            DateTime::make('isEnabled')->sortable()->readOnly(),
            DateTime::make('isApproved')->sortable()->readOnly(),
            DateTime::make('approvedAt')->sortable()->readOnly(),
            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),

            // Relationships
            HasOne::make('owner')->type('users')->readOnly(),
            BelongsToMany::make('categories')->type('categories')->readOnly(),
            BelongsToMany::make('tags')->type('tags')->readOnly(),
            BelongsToMany::make('images')->type('images')->readOnly(),
            HasMany::make('reviews')->type('reviews')->readOnly(),
        ];
    }

    /**
     * Get the resource filters.
     *
     * @return array
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
        ];
    }

    /**
     * Get the resource paginator.
     *
     * @return Paginator|null
     */
    public function pagination(): ?Paginator
    {
        return PagePagination::make();
    }
}
