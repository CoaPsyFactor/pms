<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['middleware' => ['web'], 'prefix' => '{lang}', 'namespace' => null], function () {

    if (strcasecmp(php_sapi_name(), 'php-cli')) {
        $pluginLoader = new \App\Helpers\Plugin\PluginLoader();

        $pluginLoader->loadPlugins();
    }

    Route::group(['namespace' => 'App\Http\Controllers'], function () {
        Route::get('page/{pageId}', 'PageController@renderPage')

            // define slug as string that can contain only lower letters, numbers and dash
            ->where(['pageId' => '[0-9]+'])

            // name this route, for easier check in middlewares
            ->name('page')

            // set required middleware for this route
            ->middleware(\App\Http\Middleware\FetchPage::class);

        Route::group(['middleware' => [/*'admin'*/], 'prefix' => 'admin'], function () {
            Route::get('plugins', 'AdminController@plugins')->name('admin.plugins');

            Route::get('pages', 'AdminController@pages')->name('admin.pages');

            Route::get('page/edit/{pageId}', 'AdminController@editPage')->name('admin.page.edit');

            Route::get('page/new', 'AdminController@newPage')->name('admin.page.new');

            Route::get('navigation', 'AdminController@navigationLinks')->name('admin.navigation');

            Route::post('page/new/{pageId?}', 'AdminController@createPage')->name('admin.page.create');

            Route::post('plugins/install', 'AdminController@installPlugin')->name('admin.plugins.install');
        });

        Route::get('{slug}', 'PageController@navigationLinkRender')

            // define slug as string that can contain only lower letters, numbers and dash
            ->where(['slug' => '[a-z0-9\-\/]+'])

            // name this route, for easier check in middlewares
            ->name('navigation_page')

            // set required middleware for this route
            ->middleware(\App\Http\Middleware\LoadNavigationLinkPage::class);

        Route::get('/', function () {
            return view('partial.welcome');
        })->name('homepage');
    });
});

Route::get('/', function() {
    return redirect(route('homepage', ['lang' => 'en']));
});