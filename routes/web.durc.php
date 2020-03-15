<?php
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

