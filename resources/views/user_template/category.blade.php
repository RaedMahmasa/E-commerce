@extends('user_template.layouts.template')
@section('main-content')

<div class="fashion_section">
    <div id="main_slider" >
             <div class="container">
                <h1 class="fashion_taital">{{ $category->category_name }} </h1>
                <div class="fashion_section_2">
                   <div class="row">
                    @foreach ($products as $product )

                      <div class="col-lg-4 col-sm-4">
                         <div class="box_main">
                            <h4 class="shirt_text">{{ $product->product_name  }}</h4>
                            <p class="price_text">Price  <span style="color: #262626;">{{ $product->price }}</span></p>
                            <div class="tshirt_img"><img src="{{ asset('storage/'.$product->product_img) }}"></div>
                            <div class="btn_main">

                                <form action="{{ route('addproducttocart')}}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $product->id }}" name="product_id">
                                    <input type="hidden" value="{{ $product->price }}" name="price">
                                    <input type="hidden" value="1" name="quantity">



                                    <br>
                                    <input class="btn btn-warning" type="submit" value="Buy Now">
                            <div class="btn bg:gray mx-3 "><a href="{{ route('singleproduct',[$product->id,$product->slug]) }}">See More</a></div>

                                </form>

                            </div>
                         </div>
                      </div>
                    @endforeach


                </div>
             </div>
          </div>

@endsection
