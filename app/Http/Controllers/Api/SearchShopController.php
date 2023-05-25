<?php

namespace App\Http\Controllers\Api;

use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchShopController extends Controller
{
    /**
     * Search shops by category, tag, name, description, and status.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $query = $request->input('query');

        $results = Shop::with('categories', 'tags')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->where('is_enabled', true) // only enabled shops

            ->orWhereHas('categories', function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', "%{$query}%");
            })
            ->orWhereHas('tags', function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->take(10)
            ->get();

        return response()->json([
            'message' => 'Search results',
            'data' => $results,
        ]);
    }
}
