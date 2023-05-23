<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'Tags retrieved successfully',
            'data' => Tag::all(),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validateTag = Validator::make($request->all(), [
                'name' => 'required|unique:tags,name',
            ]);
            if ($validateTag->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validateTag->errors()->first(),
                ], 400);
            }
            $tag = Tag::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Tag created successfully',
                'data' => $tag,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'Tag retrieved successfully',
                'data' => $tag,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        try {
            $validateTag = Validator::make($request->all(), [
                'name' => 'required|unique:tags,name,' . $tag->id,
            ]);
            if ($validateTag->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validateTag->errors()->first(),
                ], 400);
            }
            $tag->update($request->all());
            $tag->updateSlug();

            return response()->json([
                'status' => true,
                'message' => 'Tag updated successfully',
                'data' => $tag,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        try {
            $tag->delete();
            return response()->json([
                'status' => true,
                'message' => 'Tag deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Tag deleted successfully',
            ], 500);
        }
    }
}
