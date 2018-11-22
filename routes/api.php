<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:api');

Route::group(['prefix' => 'admin', 'namespace' => 'App\\Http\\Controllers'], function () {
    Route::put('plugin/toggle', 'Api\AdminController@togglePluginStatus')->name('api.admin.plugin.toggle');

    Route::get('plugin/options', 'Api\AdminController@getPluginPages')->name('api.admin.plugin.option');

    Route::delete('plugin/uninstall', 'Api\AdminController@uninstallPlugin')->name('api.admin.plugin.uninstall');

    Route::put('page/toggle', 'Api\AdminController@togglePageStatus')->name('api.admin.page.toggle');

    Route::delete('page', 'Api\AdminController@removePage')->name('api.admin.page.remove');

    Route::get('navigation_links', 'Api\AdminController@getNavigationLinks')->name('api.admin.navigationLinks');
});
