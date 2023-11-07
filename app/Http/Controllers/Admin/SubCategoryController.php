<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $allsubcategories = Subcategory::latest()->get();
        return view('admin.allsubcategory',compact('allsubcategories'));
    }

    public function create(){
        $categories = Category::latest()->get();
        return view('admin.addsubcategory', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'subcategory_name' => 'required|unique:subcategories',
            'category_id' => 'required'
        ]);

        $request->merge([
            'slug' => Str::slug($request->post('subcategory_name'))
        ]);

        $category_name = Category::where('id', $request->category_id)->value('category_name');

        $request['category_name'] =$category_name;

        Subcategory::create($request->all());

        Category::where('id',$request->category_id)->increment('subcategory_count',1);

        return redirect()->route('allsubcategory')->with('message',
        'Category Added Successfully!');

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
        $subcatinfo = Subcategory::findOrFail($id);

        return view('admin.editsubcategory',compact('subcatinfo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $request->validate([
            'subcategory_name' => 'required|unique:subcategories',
        ]);
        $subcatid = $request->subcatid;

        Subcategory::findOrFail($subcatid)->update([

            'subcategory_name' => $request->subcategory_name,
            $request->merge([
                'slug' => Str::slug($request->post('subcategory_name'))
            ])
        ]);

        return redirect()->route('allsubcategory')->with('message',
        'Sub Category Updated Successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {

       $cat_id=Subcategory::where('id',$id)->value('category_id');
        Subcategory::findOrFail($id)->delete();
        Category::where('id',$cat_id)->decrement('subcategory_count',1);

        return redirect()->route('allsubcategory')->with('message', 'Sub Category Deleted Successfully !');
    }
}
