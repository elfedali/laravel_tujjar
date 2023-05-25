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
            'city' => 'nullable|string',

            'tags' => 'nullable|array',
            'tags.*' => 'nullable|integer|exists:tags,id',

            'categories' => 'nullable|array',
            'categories.*' => 'nullable|integer|exists:categories,id',

        ]);

        $query = $request->input('query');

        $results = Shop::with('categories', 'tags')
            ->where(function ($queryBuilder) use ($query, $request) {
                if ($request->filled('city')) {
                    $queryBuilder->where('city', $request->input('city'));
                }
                if ($request->filled('tags')) {
                    $queryBuilder->whereHas('tags', function ($queryBuilder) use ($request) {
                        $queryBuilder->whereIn('id', $request->input('tags'));
                    });
                }
                if ($request->filled('categories')) {
                    $queryBuilder->whereHas('categories', function ($queryBuilder) use ($request) {
                        $queryBuilder->whereIn('id', $request->input('categories'));
                    });
                }
                $queryBuilder->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhereHas('categories', function ($queryBuilder) use ($query) {
                            $queryBuilder->where('name', 'like', "%{$query}%");
                        })
                        ->orWhereHas('tags', function ($queryBuilder) use ($query) {
                            $queryBuilder->where('name', 'like', "%{$query}%");
                        });
                });
            })
            ->where('is_enabled', true)
            ->orderBy('name')
            ->take(10)
            ->get();


        return response()->json([
            'message' => 'Search results',
            'data' => $results,
        ]);
    }
}
