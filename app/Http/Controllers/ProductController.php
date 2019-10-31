<?php

namespace App\Http\Controllers;

use App\Product;
use App\Cart;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth")->only(["checkout"]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(9);

        return view("products.index", compact("products"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        
        if($request->id && $request->quantity) {

             $request->validate([
                'quantity' => 'required|numeric|min:1'
            ]);

            $cart = session()->get('cart');

            $cart->totalQty -= $cart->items[$request->id]["qty"];

            $cart->totalPrice -= $cart->items[$request->id]['price'] * $cart->items[$request->id]['qty'];

            $cart->items[$request->id]['qty'] = $request->quantity;
         
            $cart->totalQty += $request->quantity;

            $cart->totalPrice += $cart->items[$request->id]['price'] * $request->quantity;

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request)
    {

       if($request->id) {
 
            $cart = session()->get('cart');
 
            if(isset($cart->items[$request->id])) {
                
                $cart->totalQty -= $cart->items[$request->id]["qty"];

                $cart->totalPrice -= $cart->items[$request->id]["qty"] * $cart->items[$request->id]["price"];

                unset($cart->items[$request->id]);
            }

            if($cart->totalQty <= 0) {                    
                session()->forget("cart");
            } else {
                session()->put('cart', $cart);
            }
        }

    }

    public function addToCart(Product $product)
    {
        if (session()->has("cart")) {
            $cart = new Cart(session()->get('cart'));
        } else {
            $cart = new Cart();
        }

        $cart->add($product);

        session()->put("cart", $cart);

        return redirect()->route("products.index")->with("success", "Product was added");

    }

    public function shopCart()
    {

        // $cart = session()->get('cart');
        // dd($cart);
        // session()->forget("cart");
   
      
        if(session()->has("cart")) {
            $cart = new Cart(session()->get('cart'));
        } else {
            $cart = null;
        }

        return view("cart.show", compact("cart"));
    }

    public function checkout($amount)
    {
        return view("cart.checkout", compact("amount"));
    }

    public function charge(Request $request)
    {

        $charge = Stripe::charges()->create([
            'currency' => 'USD',
            'source' => $request->stripeToken,
            'amount'   => $request->amount,
            'description' => ' Test from laravel new app'
        ]);

        $chargeId = $charge['id'];

        if ($chargeId) {
            // save order in orders table ...
            // clearn cart 

            auth()->user()->orders()->create([
                "cart" => serialize(session()->get("cart"))
            ]);

            session()->forget('cart');  
            return redirect()->route('store')->with('success', " Payment was done. Thanks");
        } else {
            return redirect()->back();
        }
    }
}
