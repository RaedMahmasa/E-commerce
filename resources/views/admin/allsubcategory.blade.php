@extends('admin.layouts.template')
@section('page_title')
All Sub Category - E-Commerce
@endsection
@section('content')
<div class="container">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Page/</span>All Sub Category</h4>

        @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
                @endif

    <div class="card">
        <h5 class="card-header">Available Sub Category Information</h5>
        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead class="table-light">
              <tr>
                <th>id</th>
                <th>Sub Category Name</th>
                <th> Category</th>
                <th>Product</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($allsubcategories as $subcategory )

              <tr>
               <td>{{ $subcategory->id }}</td>
               <td>{{ $subcategory->subcategory_name }}</td>
               <td>{{ $subcategory->category_name }}</td>
               <td>{{ $subcategory->product_count }}</td>
               <td>
                <a href="{{ route('editsubcategory', $subcategory->id)}}" class="btn btn-primary">Edit</a>
                <a href="{{ route('deletesubcategory', $subcategory->id, $subcategory->category_id)}}" class="btn btn-warning">Delete</a>
               </td>
              </tr>

              @endforeach

            </tbody>
          </table>
        </div>
      </div>
</div>
@endsection
