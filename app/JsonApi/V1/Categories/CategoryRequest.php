<?php

namespace App\JsonApi\V1\Categories;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class CategoryRequest extends ResourceRequest
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
            // 'slug' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($this->route()->parameter('category'))],
            'position' => ['integer'],
            'isEnabled' => ['boolean'],
        ];
    }
}
