<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'Categories retrieved successfully',
            'data' => $categories
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories',
            'description'=> 'required',
            'status' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->status = $request->status;
        $category->save();

        return response()->json([
            'status' => true,
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if(!empty($category)) {
            return response()->json([
                'status' => true,
                'message' => 'Category found successfully',
                'data' => $category
            ], 200);
        }
        else {
            return response()->json([
                'status' => false,
                'message' => 'Category info not found!'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name,'.$id,
            'description' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        else {
            $category = Category::find($id);
            $category->name = $request->name;
            $category->description = $request->description;
            $category->status = $request->status;
            $category->update();

            return response()->json([
                'status' => true,
                'message' => 'Category info updated successfully.',
                'data' => $category
            ], 200);
        }
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        if(!empty($category)) {
            $category->delete();

            return response()->json([
                'status' => true,
                'message' => 'Category info deleted successfully',
            ], 202);
        }
        else {
            return response()->json([
                'status' => false,
                'message' => 'Category info not found!'
            ], 404);
        }
    }


}
