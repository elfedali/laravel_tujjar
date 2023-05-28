<?php

namespace App\JsonApi\V1;

use App\Models\Shop;
use LaravelJsonApi\Core\Server\Server as BaseServer;
use Illuminate\Support\Facades\Auth;

class Server extends BaseServer
{

    /**
     * The base URI namespace for this server.
     *
     * @var string
     */
    protected string $baseUri = '/api/v1';

    /**
     * Bootstrap the server when it is handling an HTTP request.
     *
     * @return void
     */
    public function serving(): void
    {
        Auth::shouldUse('sanctum');

        Shop::creating(function ($shop) {
            $shop->owner_id = auth()->user()->id;
        });
    }

    /**
     * Get the server's list of schemas.
     *
     * @return array
     */
    protected function allSchemas(): array
    {
        return [
            \App\JsonApi\V1\Categories\CategorySchema::class,
            \App\JsonApi\V1\Images\ImageSchema::class,
            \App\JsonApi\V1\Reviews\ReviewSchema::class,
            \App\JsonApi\V1\Shops\ShopSchema::class,
            \App\JsonApi\V1\Tags\TagSchema::class,
            \App\JsonApi\V1\Users\UserSchema::class,
        ];
    }
}
