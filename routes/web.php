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

//uncomment this to enable authentication.
//remember that you need to do this again in ending.web.php
//Route::middleware([\CareSet\CareSetJWTAuthClient\Middleware\JWTClientMiddleware::class])->group(function () {

Route::get('/', function () {
    return view('welcome');
});

//First we do the "triples" which give the links types.. https://en.wikipedia.org/wiki/Triplestore
Route::get('genericLinkerForm/{durc_type_left}/{durc_type_right}/{durc_type_link}','GenericTripleLinker@linkForm');
// this saves the triples form POSTs
Route::post('genericLinkerSave/{durc_type_left}/{durc_type_right}/{durc_type_link}','GenericTripleLinker@linkSaver');
// this will always show the SQL to build to triples table, as well as the SQL to generate a Zermelo Graph report from the triple... even if the database already exists..
Route::get('genericLinkerSQL/{durc_type_left}/{durc_type_right}/{durc_type_link}','GenericTripleLinker@showSQLView');


//First we do the "triples" which give the links types.. https://en.wikipedia.org/wiki/Triplestore
Route::get('genericLinkerForm/{durc_type_left}/{durc_type_right}','GenericLinker@linkForm');
// this saves the triples form POSTs
Route::post('genericLinkerSave/{durc_type_left}/{durc_type_right}','GenericLinker@linkSaver');
// this will always show the SQL to build to triples table, as well as the SQL to generate a Zermelo Graph report from the triple... even if the database already exists..
Route::get('genericLinkerSQL/{durc_type_left}/{durc_type_right}','GenericLinker@showSQLView');




/*
This is an auto generated route file from DURC
this will be automatically overwritten by future DURC runs.


*/

 
//DURC->	app_countyhealth.healthdept
Route::resource("/DURC/healthdept", 'healthdeptController');
Route::get("/DURC/json/healthdept/{healthdept_id}", 'healthdeptController@jsonone');
Route::get("/DURC/json/healthdept/", 'healthdeptController@jsonall');
Route::get("/DURC/searchjson/healthdept/", 'healthdeptController@search');

 
//DURC->	app_countyhealth.healthdept_url
Route::resource("/DURC/healthdept_url", 'healthdept_urlController');
Route::get("/DURC/json/healthdept_url/{healthdept_url_id}", 'healthdept_urlController@jsonone');
Route::get("/DURC/json/healthdept_url/", 'healthdept_urlController@jsonall');
Route::get("/DURC/searchjson/healthdept_url/", 'healthdept_urlController@search');

 
//DURC->	app_countyhealth.state
Route::resource("/DURC/state", 'stateController');
Route::get("/DURC/json/state/{state_id}", 'stateController@jsonone');
Route::get("/DURC/json/state/", 'stateController@jsonall');
Route::get("/DURC/searchjson/state/", 'stateController@search');


//uncomment to enable authentication
//}); //end section for auth middleware


