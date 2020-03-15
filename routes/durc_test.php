<?php
/*
	This test route is automatically added.
	IF you would like to quickly see and test all of the index routes that are generated by DURC
	to use: http://[baseUrl]/durctest
	
	To remove routes from production, remove the require for this file from the register() method
	of the DURCServiceProvider.

*/

//This closure lists all of the index routes that DURC knows about...
Route::get('durctest', function () {
    $route_list = [ 




 			'/DURC/healthdept', //from: app_countyhealth.healthdept 
 			'/DURC/healthdept/create', //from: app_countyhealth.healthdept 
 			'/DURC/healthdept/1', //from: app_countyhealth.healthdept 
 			'/DURC/healthdept/1/edit', //from: app_countyhealth.healthdept 
 			'/DURC/healthdept_url', //from: app_countyhealth.healthdept_url 
 			'/DURC/healthdept_url/create', //from: app_countyhealth.healthdept_url 
 			'/DURC/healthdept_url/1', //from: app_countyhealth.healthdept_url 
 			'/DURC/healthdept_url/1/edit', //from: app_countyhealth.healthdept_url 
 			'/DURC/state', //from: app_countyhealth.state 
 			'/DURC/state/create', //from: app_countyhealth.state 
 			'/DURC/state/1', //from: app_countyhealth.state 
 			'/DURC/state/1/edit', //from: app_countyhealth.state 


	]; //end route_list

	$html = '<html><head><title>DURC Test Page</title><body><h1>DURC Test Page</h1><h3>DO NOT USE IN PRODUCTION!!!</h3>';

	$html .= '<ul>';

	foreach($route_list as $this_relative_link){
		$html  .= "<li><a href='$this_relative_link'>$this_relative_link </a> </li> ";
	}

	$html .= '</ul></body></html>';
	return $html;
}); //end DURC test route closure

Route::get('/',function () {
	$test_data = ['content' => '<h1>This is test content</h1>'];
	return view('DURC.durc_html',$test_data);
});
