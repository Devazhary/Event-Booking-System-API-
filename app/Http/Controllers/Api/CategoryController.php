<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(5);
        return CategoryResource::collection($categories);
    }

    public function store(CategoryRequest $request)
    {
        //validation
        $data = $request->validated();
        //create
        $category = Category::create($data);
        //return response
        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request,Category $category)
    {
        //validate
        $data = $request->validated();
        //update
        $category->update($data);
        //return response
        return new CategoryResource($category);
    }

    public function toggle(Category $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();
        return new CategoryResource($category);
    }
}
