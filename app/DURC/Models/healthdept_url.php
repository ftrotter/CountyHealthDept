<?php

namespace App\DURC\Models;

use CareSet\DURC\DURCModel;
/*
	Note this class was auto-generated from 

app_countyhealth.healthdept_url by DURC.

	This class will be overwritten during future auto-generation runs..
	Itjust reflects whatever is in the database..
	DO NOT EDIT THIS FILE BY HAND!!
	Your changes go in healthdept_url.php 

*/

class healthdept_url extends DURCModel{

    

    
        // the datbase for this model
        protected $table = 'app_countyhealth.healthdept_url';

	//DURC will dymanically copy these into the $with variable... which prevents recursion problem: https://laracasts.com/discuss/channels/eloquent/eager-load-deep-recursion-problem?page=1
		protected $DURC_selfish_with = [ 
			'healthdept', //from belongs to
		];


	//DURC did not detect any date fields

	public $timestamps = false;
	//DURC NOTE: did not find updated_at and created_at fields for this model

	
	
	

	//for many functions to work, we need to be able to do a lookup on the field_type and get back the MariaDB/MySQL column type.
	static $field_type_map = [
		'id' => 'int',
		'url' => 'varchar',
		'search_term' => 'varchar',
		'healthdept_id' => 'int',
			]; //end field_type_map
		
    // Indicate which fields are nullable for the UI to be able to validate required form elements
    protected $non_nullable_fields = [
		'id',
		'url',
		'search_term',
		'healthdept_id',
			]; // End of nullable fields

    // Use Eloquent attributes array to specify the default values for each field (if any) indicated by the DB schema, to be used as placeholder on form elements
    protected $attributes = [
		'id' => null,
		'url' => null,
		'search_term' => null,
		'healthdept_id' => null,
			]; // End of attributes
        
		//everything is fillable by default
		protected $guarded = [];


		
//DURC HAS_MANY SECTION

			//DURC did not detect any has_many relationships
		
		
//DURC HAS_ONE SECTION

			//DURC did not detect any has_one relationships

		
//DURC BELONGS_TO SECTION

/**
*	get the single healthdept for this healthdept_url
*/
	public function healthdept(){
		return $this->belongsTo('App\healthdept','healthdept_id','id');
	}



//Originating SQL Schema
/*
CREATE TABLE `app_countyhealth`.`healthdept_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `search_term` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `healthdept_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
*/


}//end of healthdept_url