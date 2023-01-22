<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variant;
use App\Models\ProductVariantPrice;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
    $product = Product::join('product_variant_prices', 'products.id', '=', 'product_variant_prices.product_id')
    ->leftJoin('product_variants as variant_one', 'product_variant_prices.product_variant_one', '=', 'variant_one.id')
    ->leftJoin('product_variants as variant_two', 'product_variant_prices.product_variant_two', '=', 'variant_two.id')
    ->leftJoin('product_variants as variant_three', 'product_variant_prices.product_variant_three', '=', 'variant_three.id')
    ->select('products.title','products.id', 'products.description', 'variant_one.variant as variant_one', 'variant_two.variant as variant_two', 'variant_three.variant as variant_three',  'product_variant_prices.price', 'product_variant_prices.stock','products.created_at')->paginate(10);;
    $variant = Variant::join('product_variants', 'product_variants.variant_id', '=', 'variants.id')
                ->select('product_variants.variant','variants.title')->distinct()->get();
    return view('products.index',["products"=>$product,"variants"=>$variant]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        try{
            $request->validate([
                'title' => 'required|string',
                'sku' => 'required|string',
                'description' => 'required|string',
            ]);
        
            $product = Product::create(
                [
                    "title" => $request->title,
                    "sku" => $request->sku,
                    "description" => $request->description,
                ]
            );
            return response()->json(['success' => 'Product added successfully!']);
        } catch(exception $e){
            echo "Problem";
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }
    


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product,Request $request)
    {
        $variants = Variant::all();
        return view('products.edit', compact('product','variants'));
    }

    public function filter(Request $request)
    {
        $title = $request->input('title');
        $variant = $request->input('variant');
        $minPrice = $request->input('price_from');
        $maxPrice = $request->input('price_to');
        $startDate = $request->input('date');
    
        $query = Product::query();
        $query->join('product_variant_prices', 'product_variant_prices.product_id', '=', 'products.id');
        $query->join('product_variants', 'product_variants.product_id', '=', 'products.id');
        $query->select('products.title', 'products.created_at', 'product_variant_prices.price', 'product_variants.variant')->distinct();

        // $query->select('products.id')->groupBy('products.id');

        if ($title) {
            $query->where('title', 'like', "%$title%");
        }
    
        if ($variant) {
            $query->where('variant', 'like', "%$variant%");
        }
    
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
    
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
    
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
    
        $products = $query->get();
        return response()->json(['products' => $products]);

    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
