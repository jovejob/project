<?php

namespace App\Http\Controllers;


use App\Product;
use File;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
	    /**
     * @var Products
     */
    protected $product;
    /**
     * ProductsController constructor.
     */
    public function __construct()
    {
        $this->product = new Product();
    }
    public function welcomePage()
    {
        $getcontent = File::get(base_path().'/public/database/products.txt');
        $decode = json_decode($getcontent,true);
         //dd($decode);
        return view('welcome', [
            'products' => $decode
        ]);
    }
    public function addProduct(Request $request)
    {
        $input = $request->all();
        $inputs = array_except($input, ['_token']);
        // validate
        $this->validate($request, [
            'name' => 'required',
            'quantity'  => 'required|numeric',
            'price'  => 'required|numeric'
        ], [
            'name.required' => 'Product name is required',
            'quantity.required' => 'Quantity is required',
            'quantity.number' => 'Please insert number',
            'price.required' => 'Price is required',
            'price.number' => 'Please insert number',
        ]);
        
        $inputs['datetime'] = date('Y:m:d H:i:s');
        $added_data = json_encode($inputs);

		$getcontent = File::get(base_path().'/public/database/products.txt');
        $decode = json_decode($getcontent, true) ?: [];
        array_push($decode, $added_data);
        
        $encodeJson = json_encode($decode);
        
        File::put(base_path().'/public/database/products.txt', $encodeJson);

        if ($added_data) :
            // If the data was added, redirect with success message
            return response()->json(['success' => 'You successful add new post!', 'product' => $inputs]);
        else :
            // Else return error
            return response()->json(['error' => 'Somethins is wrong!']);
        endif;
    }
}
