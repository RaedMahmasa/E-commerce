<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function() {
    return view('user_template.home');
});



Route::controller(HomeController::class)->group(function(){
    Route::get('/','index')->name('home');
});

Route::controller(ClientController::class)->group(function(){
    Route::get('/category/{id}/{slug}','CategoryPage')->name('category');
    Route::get('/product-details/{id}/{slug}','SingleProduct')->name('singleproduct');
    Route::get('/new-release','NewRelease')->name('newrelease');

});

Route::middleware(['auth','role:user'])->group(function()
{
    Route::controller(ClientController::class)->group(function(){
        Route::get('/add-to-cart','AddToCart')->name('addtocart');
        Route::post('/add-product-to-cart','AddProductToCart')->name('addproducttocart');
        Route::post('/add-shipping-address','AddShippnigAddress')->name('addshippingaddress');
        Route::post('/place-order','PlaceOrder')->name('placeorder');
        Route::get('/shipping-address','GetShippnigAddress')->name('shippingaddress');
        Route::get('/checkout','Checkout')->name('checkout');
        Route::get('/user-profile','UserProfile')->name('userprofile');
        Route::get('/user-profile/pending-orders','PendingOrders')->name('pendingorders');
        Route::get('/user-profile/history','History')->name('History');
        Route::get('/todays-deal','TodaysDeal')->name('todaydeal');
        Route::get('/customer-service','CustomerService')->name('customerservice');
        Route::get('/remove-cart-item/{id}','RemoveCartItem')->name('removeitem');
    });
});




Route::middleware(['auth','role:admin'])->group(function(){
Route::controller(DashboardController::class)->group(function(){
    Route::get('/admin/dashboard','index')->name('admindashboard');

    });

Route::controller(CategoryController::class)->group(function(){
        Route::get('/admin/all-category','index')->name('allcategory');
        Route::get('/admin/add-category','create')->name('addcategory');
        Route::post('/admin/store-category','store')->name('storecategory');
        Route::get('/admin/edit-category/{id}','edit')->name('editcategory');
        Route::post('/admin/update-category','update')->name('updatecategory');
        Route::get('/admin/delete-category/{id}','delete')->name('deletecategory');
            });

        Route::controller(SubCategoryController::class)->group(function(){
                Route::get('/admin/all-subcategory','index')->name('allsubcategory');
                Route::get('/admin/add-subcategory','create')->name('addsubcategory');
                Route::post('/admin/store-subcategory','store')->name('storesubcategory');
                Route::get('/admin/edit-subcategory/{id}','edit')->name('editsubcategory');
                Route::get('/admin/delete-subcategory/{id}','delete')->name('deletesubcategory');
                Route::post('/admin/update-subcategory','update')->name('updatesubcategory');
                    });

        Route::controller(ProductController::class)->group(function(){
                        Route::get('/admin/all-product','index')->name('allproducts');
                        Route::get('/admin/add-product','create')->name('addproduct');
                        Route::post('/admin/store-porduct','store')->name('storeproduct');
                        Route::get('/admin/edit-product-img/{id}','edit_img')->name('editproductimg');
                        Route::post('/admin/update-product-img','update_img')->name('updateproductimg');
                        Route::get('/admin/edit-product/{id}','edit')->name('editproduct');
                        Route::post('/admin/update-product','update')->name('updateproduct');
                        Route::get('/admin/delete-product/{id}','delete')->name('deleteproduct');
                            });

        Route::controller(OrderController::class)->group(function(){
                                Route::get('/admin/pending-order','index')->name('pendingorder');
                                    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
