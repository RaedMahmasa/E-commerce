<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    public function CategoryPage($id)
    {
        $category = Category::findOrFail($id);
        $products = Product::where('product_category_id',$id)->latest()->get();
        return view('user_template.category',compact('category','products'));
    }

    public function SingleProduct($id){
        $product = Product::findOrFail($id);
        $subcat_id = Product::where('id',$id)->value('product_subcategory_id');
        $related_products = Product::where('product_subcategory_id',$subcat_id)->latest()->get();

        return view('user_template.product',compact('product','related_products'));
    }

    public function AddToCart(){
        $user_id=Auth::id();
        $cart_items = Cart::where('user_id',$user_id)->get();
        return view('user_template.addtocart',compact('cart_items'));
    }

    public function GetShippnigAddress() {
        return view('user_template.shippingaddress');
    }

    public function AddShippnigAddress(Request $request) {
        ShippingInfo::create([
            'user_id' => Auth::id(),
            'phone_number' => $request->phone_number,
            'city_name' => $request->city_name,
            'postal_code' => $request->postal_code,
        ]);
        return redirect()->route('checkout');

    }

    public function Checkout() {
        $userid = Auth::id();
        $cart_items = Cart::where('user_id',$userid)->get();
        $shipping_address = ShippingInfo::where('user_id',$userid)->first();
        return view('user_template.checkout',compact('cart_items','shipping_address'));
    }

    public function PlaceOrder(){
        $userid = Auth::id();
        $shipping_address = ShippingInfo::where('user_id',$userid)->first();
        $cart_items = Cart::where('user_id',$userid)->get();

        foreach($cart_items as $item) {
            Order::insert([
                'userid' => $userid,
                'shipping_phoneNumber' =>$shipping_address->phone_number,
                'shipping_city' =>$shipping_address->city_name,
                'shipping_postalcode' =>$shipping_address->postal_code,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'total_price' => $item->price,
            ]);

            $id= $item->id;
            Cart::findOrFail($id)->delete();
        }
        ShippingInfo::where('user_id',$userid)->first()->delete();

        return redirect()->route('pendingorders')->with('message','Your Order Has Been Placed Successfully!');

    }

    public function UserProfile() {
        return view('user_template.userprofile');
    }

    public function AddProductToCart(Request $request) {

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;
        $product_price = $request->price;
        $price = $product_price * $quantity;
        $cartItem = Cart::insert([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'quantity' => $quantity,
            'price' => $price,
        ]);

        return redirect()->route('addtocart')->with('message', 'Your item added to the cart successfully!');
    }
    public function RemoveCartItem($id) {
        Cart::findOrFail($id)->delete();

        return redirect()->route('addtocart')->with('message', 'Your item removed from cart successfully!');
    }

    public function PendingOrders() {
        $pending_orders = Order::where('status','pending')->latest()->get();
        return view('user_template.pendingoredrs',compact('pending_orders'));
    }

    public function History() {
        return view('user_template.history');
    }

    public function NewRelease() {
        return view('user_template.newrelease');
    }

    public function TodaysDeal() {
        return view('user_template.todaysdeal');
    }

    public function CustomerService() {
        return view('user_template.customerservice');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
