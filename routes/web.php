<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*admin profile routes goes here*/
Route::get('/dashboard','DashboardController@index')->name('dashboard');
Route::get('/logout','LoginController@logout')->name('logout');
Route::post('/login','LoginController@index')->name('login');
Route::get('/profile','DashboardController@getUserProfile')->name('user_profile');
/*admin profile routes goes here*/

/*category CRUD routes goes here*/
Route::get('/categories','CategoryController@index')->name('categories');
Route::get('/getDynamiccategoryRecord','CategoryController@getCategoryRecord')->name('getDynamiccategoryRecord');
Route::get('/add_category','CategoryController@loadViewAddCategory')->name('add_category');
Route::post('/insert_category_record','CategoryController@AddCategory')->name('insert_category_record');
Route::post('/deleteCategoryRecord','CategoryController@deleteCategory');
Route::get('/edit_category/{category_id?}','CategoryController@editViewLoadCategory');
Route::post('/update_category_record/','CategoryController@editCategory')->name('update_category_record');
/*category CRUD routes goes here*/

/*pictures CRUD routes goes here*/
Route::get('/pictures','PictureController@index')->name('pictures');
Route::get('/add_picture','PictureController@loadViewAddPicture')->name('add_picture');
Route::any('/insert_picture_record','PictureController@addPicture')->name('insert_picture_record');
Route::any('/deletePictureRecord','PictureController@deletePicture')->name('deletePictureRecord');
Route::get('/edit_picture/{picture_id?}','PictureController@editViewLoadPicture');
Route::any('/update_picture_record','PictureController@editPicture')->name('update_picture_record');
Route::get('/editinfo/{id}','DashboardController@editpersonalinfo');
Route::post('/updateinfo','DashboardController@updateadmin');
Route::get('/allcategories', 'BrandcategoriesController@getIndex');
Route::get('/alldata', 'BrandcategoriesController@anyData');
Route::get('/addcat', 'BrandcategoriesController@addbrandcategories');
Route::post('/subcat', 'BrandcategoriesController@savebrand');
Route::get('/edit_brand/{id}', 'BrandcategoriesController@loadeditbrand');
Route::post('/update_brand', 'BrandcategoriesController@updatebrand');
Route::get('/del_brand/{id}', 'BrandcategoriesController@deletebrand');

/////////////////product........................
Route::get('/showproduct', 'ProductController@index');
Route::get('/load_add','ProductController@loadViewAddProduct');
Route::post('/add_product','ProductController@addproduct');
Route::get('/edit_product/{id}','ProductController@editViewLoadProduct');
Route::post('/update_product','ProductController@updateproduct');
Route::get('/del_product/{id}','ProductController@deleteproduct');
Route::get('/loadPopularProduct','ProductController@LoadPopularProduct');
Route::get('/filter_popular_test','ProductController@filter_popular_test');


///////////////////////user.....................
Route::get('/users','UsersController@index');
Route::get('/del_user/{id}','UsersController@deleteuser');
Route::get('/view/{id}','ChartController@index');
Route::get('/test_color/{id}/{product_id}','ChartController@gettestcolor');
Route::post('/savecolor/','ChartController@savecolorandvalue');
Route::get('/add_more_test/{id}','ChartController@addtestnameandcolorandvalue');
Route::post('/addtest/','ChartController@saveaddedtest');
Route::get('/savechart/{id}','ChartController@savechartvalue');
Route::post('/updatechart/','ChartController@updatechartcolorandvalue');
Route::post('/updatechartbyajax/','ChartController@updatechartbyjquery');
Route::get('/del_chart/{product_id}/{id}','ChartController@deletechart');
Route::get('/Showassay/','AssayController@index');
Route::get('/showaddassay/','AssayController@loadaddassay');
Route::post('/addassay/','AssayController@addassay');
Route::get('/edit_assay/{id}','AssayController@loadeditassay');
Route::post('/updateassay/','AssayController@updateAssay');
Route::get('/del_assay/{id}','AssayController@deleteAssay');
Route::get('/showillness','IllenessController@index');
Route::get('/load_illness','IllenessController@loadaddillness');
Route::post('/add_illness_name','IllenessController@addIllness');
Route::get('/edit_illness/{id}','IllenessController@editillness');
Route::post('/update_illness/','IllenessController@updateillness');
Route::get('/del_illness/{id}','IllenessController@deleteillness');
Route::get('/active/{id}','IllenessController@activeillness');

Route::get('/loadaddimage/','UsersController@loadAddImage');
Route::get('/showaddimage/','UsersController@showimageadd');
Route::post('/add_images/','UsersController@add_image');
Route::get('/del_image/{id}','UsersController@delete_image');
Route::get('/loadshorttext/','UsersController@load_short_text');
Route::get('/showaddtext/','UsersController@loadAddText');
Route::post('/save_text/','UsersController@saveText');
Route::get('/del_text/{id}','UsersController@deleteText');
Route::get('/edit_text/{id}','UsersController@editText');
Route::post('/update_text/','UsersController@updateText');


////////////////result...............

Route::get('/loadresult/','ResultController@index');
Route::get('/view_result/','ResultController@ShowUserWiseTest');
Route::get('/filter_result/','ResultController@filterResult');
Route::get('/downloadExcel/{type}/{id}','ResultController@exportToExcel');
Route::get('/downloadAllResult/{type}/{user_id}/{date}','ResultController@filterAllUserResultExportToExcel');
Route::get('/downloadAllResult/{type}','ResultController@allUserResultExportToExcel');



//Terms and Condition

Route::get('/t&c/','API\TermsAndConditionsController@loadTermsAndConditionsPage');
Route::post('/save_t&c/','API\TermsAndConditionsController@saveTermsAndConditions');
//Route::post('/password_reset/','API\TermsAndConditionsController@password_reset_save');
// Route::post('/password_reset/','API\TermsAndConditionsController@password_reset_save');
// Route::post('/password_reset/','API\TermsAndConditionsController@password_reset_save');
Route::get('/reset_password/{email}','ResetpasswordController@reset_password');
Route::post('/password_reset/','ResetpasswordController@password_reset_save');








































// Route::get('datatables', 'BrandcategoriesController', [
//     'anyData'  => 'datatables.data',
//     'getIndex' => 'datatables',
// ]);





/*pictures CRUD routes goes here*/

Route::get('/', function () {
    return view('admin/login');
});
