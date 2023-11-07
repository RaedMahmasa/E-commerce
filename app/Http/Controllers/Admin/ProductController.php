<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $products = Product::latest()->get();
        return view('admin.allproducts',compact('products'));
    }

    public function create(){
        $categories = Category::latest()->get();
        $subcategories = Subcategory::latest()->get();

        return view('admin.addproduct',compact('categories','subcategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $request->validate([
            'product_name' => 'required|unique:products',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'product_short_des' => 'required',
            'product_long_des' => 'required',
            'product_category_id' => 'required',
            'product_subcategory_id' => 'required',
        ]);

        $request->merge([
            'slug' => Str::slug($request->post('product_name'))
        ]);
        $data= $request->except('product_img');

        $data['product_img']=$this->uploadImage($request);


        $category_id = $request->product_category_id;
        $subcategory_id = $request->product_subcategory_id;

        $category_name = Category::where('id', $category_id)->value('category_name');
        $subcategory_name = Subcategory::where('id', $subcategory_id)->value('subcategory_name');

        $data['product_category_name'] =$category_name;
        $data['product_subcategory_name'] =$subcategory_name;


        Product::create($data);

        Category::where('id',$category_id)->increment('product_count',1);
        Category::where('id',$request->category_id)->increment('subcategory_count',1);
        Subcategory::where('id',$subcategory_id)->increment('product_count',1);

        return redirect()->route('allproducts')->with('message',
        'Product Added Successfully!');

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

    public function edit ($id) {
        $productinfo = Product::findOrFail($id);


        return view('admin.editproduct',compact('productinfo'));

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_img($id)
    {
        $productinfo = Product::findOrFail($id);
        return view('admin.editproductimg',compact('productinfo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request ) {
        $productid = $request->id;

        $request->validate([
            'product_name' => 'required|unique:products',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'product_short_des' => 'required',
            'product_long_des' => 'required',
        ]);
        Product::findOrFail($productid)->update($request->all());

        return redirect()->route('allproducts')->with('message','Product Image Updated Successfully !');

    }

    public function update_img (Request $request)
    {

        $request->merge([
            'slug' => Str::slug($request->post('product_name'))
        ]);

        $id = $request->id;

        $product = Product::findOrFail($id);

        $old_image = $product->product_img;

        $data = $request->except('product_img');
        $new_image = $this->uploadImage($request);
        if ($new_image) {
            $data['product_img'] = $new_image;
        }

        $product->update( $data );
        //$category->fill($request->all())->save();

        if ($old_image && $new_image) {
            Storage::disk('public')->delete($old_image);
        }

        return redirect()->route('allproducts')->with('message',
        'Product Information Updated Successfully !');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $cat_id = $product->product_category_id;
        $subcat_id = $product->product_subcategory_id;

        $product->delete();

        Category::where('id', $cat_id)->update([
            'product_count' => Category::where('id', $cat_id)->value('product_count') - 1,
        ]);

        Subcategory::where('id', $subcat_id)->update([
            'product_count' => Subcategory::where('id', $subcat_id)->value('product_count') - 1,
        ]);

        return redirect()->route('allproducts')->with('message', 'Product Deleted Successfully!');
    }


    protected function uploadImage(Request $request){

        if(!$request->hasFile('product_img')){
            return;
        }
            $file=$request->file('product_img');
            $path=$file->store('uploads',[
                'disk'=>'public'
            ]);
            return $path;

    }
}
