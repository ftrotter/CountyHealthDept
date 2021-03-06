<?php
/*
Note: because this file was signed, everything originally placed before the name space line has been replaced... with this comment ;)
FILE_SIG=6df4c6d50f1f3560f06c9366367e872b
*/
namespace App;
/*
	state: controls app_countyhealth.state

This class started life as a DURC model, but itwill no longer be overwritten by the generator
this is safe to edit.


*/
class state extends \App\DURC\Models\state
{
	//this controls what is downloaded in the json for this object under card_body.. 
	//this function returns the html snippet that should be loaded for the summary of this object in a bootstrap card
	//read about the structure here: https://getbootstrap.com/docs/4.3/components/card/
	//this function should return an html snippet to go in the first 'card-body' div of an HTML interface...
	public function getCardBody() {
		return parent::getCardBody(); //just use the standard one unless a user over-rides this..
	}


	//You may need to change these for 'one to very very many' relationships.
/*
		protected $DURC_selfish_with = [ 
			'healthdept', //from from many
		];

*/
	//you can uncomment fields to prevent them from being serialized into the API!
	protected  $hidden = [
			//'id', //int
			//'ssa_state_code', //varchar
			//'short_state', //varchar
			//'state_name', //varchar
			//'state_ucase', //varchar
			//'state_or_territory', //varchar
			//'population', //int
			//'is_known_good_state', //tinyint
		]; //end hidden array


//DURC HAS_MANY SECTION

/**
*	DURC is handling the healthdept for this state in state
*       but you can extend or override the defaults by editing this function...
*/
	public function healthdept(){
		return parent::healthdept();
	}


//DURC BELONGS_TO SECTION
			//DURC did not detect any belongs_to relationships

	//look in the parent class for the SQL used to generate the underlying table

	//add fields here to entirely hide them in the default DURC web interface.
        public static $UX_hidden_col = [
        ];

        public static function isFieldHiddenInGenericDurcEditor($field){
                if(in_array($field,self::$UX_hidden_col)){
                        return(true);
                }
        }

	//add fields here to make them view-only in the default DURC web interface
        public static $UX_view_only_col = [
        ];

        public static function isFieldViewOnlyInGenericDurcEditor($field){
                if(in_array($field,self::$UX_view_only_col)){
                        return(true);
                }
        }

	//your stuff goes here..
	

}//end state