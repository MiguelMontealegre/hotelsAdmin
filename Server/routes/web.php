<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::group([
    'prefix' => config('saml2.routesPrefix'),
    'middleware' => array_merge(['saml2.resolveTenant'], config('saml2.routesMiddleware')),
], function () {
    Route::get('/{uuid}/logout', array(
        'as' => 'saml.logout',
        'uses' => 'App\Http\Controllers\Saml2\Saml2Controller@logout',
    ));

    Route::get('/{uuid}/login', array(
        'as' => 'saml.login',
        'uses' => 'App\Http\Controllers\Saml2\Saml2Controller@login',
    ));

    Route::get('/{uuid}/metadata', array(
        'as' => 'saml.metadata',
        'uses' => 'App\Http\Controllers\Saml2\Saml2Controller@metadata',
    ));

    Route::post('/{uuid}/acs', array(
        'as' => 'saml.acs',
        'uses' => 'App\Http\Controllers\Saml2\Saml2Controller@acs',
    ));

    Route::get('/{uuid}/sls', array(
        'as' => 'saml.sls',
        'uses' => 'App\Http\Controllers\Saml2\Saml2Controller@sls',
    ));
});