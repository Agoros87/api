<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    /**
     * @OA\Get (
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Get List of categories",
     *     @OA\Response(
     *          response=200,
     *          description="Succesfull operation",
     *          ),
     *    @OA\Response(
     *          response=401,
     *           description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *      ),
     * )
     */
    public function index()
    {
        abort_if(!auth()->user()->tokenCan('categories-list'),403);
        return CategoryResource::collection(Category::all());
    }

    public function show(Category $category)
    {
        abort_if(!auth()->user()->tokenCan('categories-show'),403);
        return new CategoryResource($category);
    }

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
