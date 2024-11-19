<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Knuckles\Scribe\Attributes\Endpoint;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Categories
 * Managing categories
 */
class CategoryController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * Getting the list of the categories
     */
    public function index()
    {
        abort_if(!auth()->user()->tokenCan('categories-list'),403);
        return CategoryResource::collection(Category::all());
    }
    #[Endpoint('Show category' ,description: 'Get a specific category')]
    public function show(Category $category)
    {
        abort_if(!auth()->user()->tokenCan('categories-show'),403);
        return new CategoryResource($category);
    }
    /**
     * Store a new category
     *
     * Creating a new category
     *
     * @bodyParam name string required The name of the category. Example: Electronics
     *
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->all();

        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $name = Str::uuid() . '.' . $file->extension();
            $file->storeAs('categories', $name , 'public');
            $data['photo'] = $name;
        }
    $category = Category::create($request->all());

    return new CategoryResource($category);
    }

    public function update(Category $category, StoreCategoryRequest $request)
    {
        $category->update($request->all());

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->noContent();
    }


    public function list()
    {
        return CategoryResource::collection(Category::all());
    }



}
