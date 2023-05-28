<?php

namespace App\JsonApi\V1\Shops;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;


class ShopRequest extends ResourceRequest
{

    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            //'slug' => ['required', 'string', 'max:255', Rule::unique('shops')->ignore($this->route()->parameter('shop'))],
            'description' => ['string'],
            'phoneNumber' => ['string', 'max:255'],

            'address' => ['string', 'max:255'],
            'city' => ['string', 'max:255'],
            'zipCode' => ['integer', 'digits:5'],
            'country' => ['string', 'max:255'],
            //'logoPhoto' => ['file', 'image:jpeg,png,jpg,gif,svg', 'max:2048'],
            'isEnabled' => ['boolean'],
            'tags' => JsonApiRule::toMany(),
            'categories' => JsonApiRule::toMany(),
            //'images' => ['array', 'max:5'],
        ];
    }
}
