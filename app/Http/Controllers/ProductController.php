<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection|Product[]
     */
    public function index()
    {
        return Product::all();
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|unique:products|max:255',
            'description' => 'required',
            'price' => 'required'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->slug = $request->slug;
        $product->save();

        return  response()->json(
            [
             'data' => $product,
             'message' => 'Product Added!'
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Product::find($id);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:products|max:255',
            'description' => 'required',
            'price' => 'required'
        ]);
        $product = Product::find($id);
        $product->update($request->all());
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return int
     */
    public function destroy(int $id)
    {
        return Product::destroy($id);
    }

    public function search($name){
        return Product::where(
            'name',
            'like',
            '%'.$name.'%'
        )->get();
    }
}
