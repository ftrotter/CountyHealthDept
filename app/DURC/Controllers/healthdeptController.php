<?php

namespace App\DURC\Controllers;

use App\healthdept;
use Illuminate\Http\Request;
use CareSet\DURC\DURC;
use CareSet\DURC\DURCController;
use Illuminate\Support\Facades\View;

class healthdeptController extends DURCController
{


	public $view_data = [];

	protected static $hidden_fields_array = [
		'created_at',
		'updated_at',

	];


	public function getWithArgumentArray(){
		
		$with_summary_array = [];
		$with_summary_array[] = "state:id,".\App\state::getNameField();

		return($with_summary_array);
		
	}


	private function _get_index_list(Request $request){

		$return_me = [];

		$with_argument = $this->getWithArgumentArray();

		$these = healthdept::with($with_argument)->paginate(100);

        	foreach($these->toArray() as $key => $value){ //add the contents of the obj to the the view 
			$return_me[$key] = $value;
        	}

		//collapse and format joined data..
		$return_me_data = [];
        foreach($return_me['data'] as $data_i => $data_row){
                foreach($data_row as $key => $value){
                        if(is_array($value)){
                                foreach($value as $lowest_key => $lowest_data){
                                        //then this is a loaded attribute..
                                        //lets move it one level higher...

                                        if ( isset( healthdept::$field_type_map[$lowest_key] ) ) {
                                            $field_type = healthdept::$field_type_map[ $lowest_key ];
                                            $return_me_data[$data_i][$key .'_id_DURClabel'] = DURC::formatForDisplay( $field_type, $lowest_key, $lowest_data, true );
                                        } else {
                                            $return_me_data[$data_i][$key .'_id_DURClabel'] = $lowest_data;
                                        }
                                }
                        }

                        if ( isset( healthdept::$field_type_map[$key] ) ) {
                            $field_type = healthdept::$field_type_map[ $key ];
                            $return_me_data[$data_i][$key] = DURC::formatForDisplay( $field_type, $key, $value, true );
                        } else {
                            $return_me_data[$data_i][$key] = $value;
                        }
                }
        }
        $return_me['data'] = $return_me_data;
		
		
                foreach($return_me['data'] as $data_i => $data_row){
                        foreach($data_row as $key => $value){
                                if(is_array($value)){
                                        foreach($value as $lowest_key => $lowest_data){
                                                //then this is a loaded attribute..
                                                //lets move it one level higher...
                                                $return_me['data'][$data_i][$key .'_id_DURClabel'] = $lowest_data;
                                        }
                                        unset($return_me['data'][$data_i][$key]);
                                }
                        }
                }


		//helps with logic-less templating...
		if($return_me['first_page_url'] == $return_me['last_page_url']){
			$return_me['is_need_paging'] = false;
		}else{
			$return_me['is_need_paging'] = true;
		}

		if($return_me['current_page'] == 1){
			$return_me['first_page_class'] = 'disabled';
			$return_me['prev_page_class'] = 'disabled';
		}else{
			$return_me['first_page_class'] = '';
			$return_me['prev_page_class'] = '';
		}


		if($return_me['current_page'] == $return_me['last_page']){
			$return_me['next_page_class'] = 'disabled';
			$return_me['last_page_class'] = 'disabled';
		}else{
			$return_me['next_page_class'] = '';
			$return_me['last_page_class'] = '';
		}

		return($return_me);
	}

	/**
	*	A simple function that allows fo rthe searching of this object type in the db, 
	*	And returns the results in a select2-json compatible way.
	*	This powers the select2 widgets across the forms...
	*/
   	public function search(Request $request){

		$q = $request->input('q');
		$page = $request->input('page');

		//TODO we need to escape this query string to avoid SQL injection.

		//what is the field I should be searching
                $search_fields = healthdept::getSearchFields();

		//sometimes there is an image field that contains the url of an image
		//but this is typically null
		$img_field = healthdept::getImgField();

		$where_sql = '';
		$or = '';
		foreach($search_fields as $this_field){
			$where_sql .= " $or $this_field LIKE '%$q%'  ";
			$or = ' OR ';
		}

		$query = healthdept::whereRaw($where_sql);
		            
		$count = $query->count();			
		$these = $query
		            ->skip(20*($page-1))
					->take(20)
					->get();
					
        $more = $count > $page * 20;

		$return_me['pagination'] = ['more' => $more];
		$raw_array = $these->toArray();

		$real_array = [];
		foreach($raw_array as $this_row){
			$tmp = [ 'id' => $this_row['id']];
			$tmp_text = '';
			foreach($this_row as $field => $data){
				if(in_array($field,$search_fields)){
					//then we need to show this text!!
					$tmp_text .=  "$data ";
				}
			}
			$tmp['text'] = trim($tmp_text);

			//show the id of the data at the end of the select..
			$tmp['text'] .= ' ('.$this_row['id'].')';;

			if(!is_null($img_field)){ //then there is an image for this entry
				$tmp['img_field'] = $img_field;
				if(isset($this_row[$img_field])){
					$tmp['img_url'] = $this_row[$img_field];
				}else{	
					$tmp['img_url'] = null;
				}
			}

			$real_array[] = $tmp;
		}


		$return_me['results'] = $real_array;

		// you might this helpful for debugging..
		//$return_me['where'] = $where_sql;

		return response()->json($return_me);

	}

    /**
     * Get a json version of all the objects.. 
     * @param  \App\healthdept  $healthdept
     * @return JSON of the object
     */
    public function jsonall(Request $request){
		return response()->json($this->_get_index_list($request));
 	}

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
	$main_template_name = $this->_getMainTemplateName();


	$this->view_data = $this->_get_index_list($request);

	if($request->has('debug')){
		var_export($this->view_data);
		exit();
	}
	$durc_template_results = view('DURC.healthdept.index',$this->view_data);        
	return view($main_template_name,['content' => $durc_template_results]);
    }


    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */ 
    public function store(Request $request){

	$myNewhealthdept = new healthdept();

	//the games we play to easily auto-generate code..
	$tmp_healthdept = $myNewhealthdept;
			$tmp_healthdept->id = DURC::formatForStorage( 'id', 'int', $request->id, $tmp_healthdept ); 
		$tmp_healthdept->healthdept_name = DURC::formatForStorage( 'healthdept_name', 'varchar', $request->healthdept_name, $tmp_healthdept ); 
		$tmp_healthdept->county_name = DURC::formatForStorage( 'county_name', 'varchar', $request->county_name, $tmp_healthdept ); 
		$tmp_healthdept->population = DURC::formatForStorage( 'population', 'int', $request->population, $tmp_healthdept ); 
		$tmp_healthdept->state_id = DURC::formatForStorage( 'state_id', 'int', $request->state_id, $tmp_healthdept ); 
		$tmp_healthdept->fips_code = DURC::formatForStorage( 'fips_code', 'varchar', $request->fips_code, $tmp_healthdept ); 
		$tmp_healthdept->healthdept_url = DURC::formatForStorage( 'healthdept_url', 'varchar', $request->healthdept_url, $tmp_healthdept ); 
		$tmp_healthdept->wikipedia_url = DURC::formatForStorage( 'wikipedia_url', 'varchar', $request->wikipedia_url, $tmp_healthdept ); 
		$tmp_healthdept->county_url = DURC::formatForStorage( 'county_url', 'varchar', $request->county_url, $tmp_healthdept ); 

	
	try {
	    		$tmp_healthdept->save();

	} catch (\Exception $e) {
	          return redirect("/DURC/healthdept/create")->with('status', 'There was an error in your data: '.$e->getMessage());

	}

	$new_id = $myNewhealthdept->id;
	
	return redirect("/DURC/healthdept/$new_id")->with('status', 'Data Saved!');
    }//end store function

    /**
     * Display the specified resource.
     * @param  \App\$healthdept  $healthdept
     * @return \Illuminate\Http\Response
     */
    public function show(healthdept $healthdept){
	return($this->edit($healthdept));
    }

    /**
     * Get a json version of the given object 
     * @param  \App\healthdept  $healthdept
     * @return JSON of the object
     */
    public function jsonone(Request $request, $healthdept_id){
		$healthdept = \App\healthdept::find($healthdept_id);
		$healthdept = $healthdept->fresh_with_relations(); //this is a custom function from DURCModel. you can control what gets autoloaded by modifying the DURC_selfish_with contents on your customized models
		$return_me_array = $healthdept->toArray();
		
		//lets see if we can calculate a card-img-top for a front end bootstrap card interface
		$img_uri_field = \App\healthdept::getImgField();
		if(!is_null($img_uri_field)){ //then this object has an image link..
			if(!isset($return_me_array['card_img_top'])){ //allow the user to use this as a field without pestering..
				$return_me_array['card_img_top'] = $healthdept->$img_uri_field;
			}
		}

		//lets get a card_body from the DURC mode class!!
		if(!isset($return_me_array['card_body'])){ //allow the user to use this as a field without pestering..
			//this is simply the name unless someone has put work into this...
			$return_me_array['card_body'] = $healthdept->getCardBody();
		}
		
		return response()->json($return_me_array);
 	}


    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create(){
	// but really, we are just going to edit a new object..
	$new_instance = new healthdept();
	return $this->edit($new_instance);
    }


    /**
     * Show the form for editing the specified resource.
     * @param  \App\healthdept  $healthdept
     * @return \Illuminate\Http\Response
     */
    public function edit(healthdept $healthdept){

	$main_template_name = $this->_getMainTemplateName();

	//do we have a status message in the session? The view needs it...
	$this->view_data['session_status'] = session('status',false);
	if($this->view_data['session_status']){
		$this->view_data['has_session_status'] = true;
	}else{
		$this->view_data['has_session_status'] = false;
	}

	$this->view_data['csrf_token'] = csrf_token();
	
	
	foreach ( healthdept::$field_type_map as $column_name => $field_type ) {
        // If this field name is in the configured list of hidden fields, do not display the row.
        $this->view_data["{$column_name}_row_class"] = '';
        if ( in_array( $column_name, self::$hidden_fields_array ) ) {
            $this->view_data["{$column_name}_row_class"] = 'd-none';
        }
    }

	if($healthdept->exists){	//we will not have old data if this is a new object

		//well lets properly eager load this object with a refresh to load all of the related things
		$healthdept = $healthdept->fresh_with_relations(); //this is a custom function from DURCModel. you can control what gets autoloaded by modifying the DURC_selfish_with contents on your customized models

		//put the contents into the view...
		foreach($healthdept->toArray() as $key => $value){
			if ( isset( healthdept::$field_type_map[$key] ) ) {
                $field_type = healthdept::$field_type_map[ $key ];
                $this->view_data[$key] = DURC::formatForDisplay( $field_type, $key, $value );
            } else {
                $this->view_data[$key] = $value;
            }
            
            // If this is a nullable field, see whether null checkbox should be checked by default
			if ($healthdept->isFieldNullable($key) &&
                $value == null) {
			    $this->view_data["{$key}_checked"] = "checked";
            }
		}

		//what is this object called?
		$name_field = $healthdept->_getBestName();
		$this->view_data['is_new'] = false;
		$this->view_data['durc_instance_name'] = $healthdept->$name_field;
	}else{
		$this->view_data['is_new'] = true;
	}

	$debug = false;
	if($debug){
		echo '<pre>';
		var_export($this->view_data);
		exit();
	}
	

	$durc_template_results = view('DURC.healthdept.edit',$this->view_data);        
	return view($main_template_name,['content' => $durc_template_results]);
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\healthdept  $healthdept
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, healthdept $healthdept){

	$tmp_healthdept = $healthdept;
			$tmp_healthdept->id = DURC::formatForStorage( 'id', 'int', $request->id, $tmp_healthdept ); 
		$tmp_healthdept->healthdept_name = DURC::formatForStorage( 'healthdept_name', 'varchar', $request->healthdept_name, $tmp_healthdept ); 
		$tmp_healthdept->county_name = DURC::formatForStorage( 'county_name', 'varchar', $request->county_name, $tmp_healthdept ); 
		$tmp_healthdept->population = DURC::formatForStorage( 'population', 'int', $request->population, $tmp_healthdept ); 
		$tmp_healthdept->state_id = DURC::formatForStorage( 'state_id', 'int', $request->state_id, $tmp_healthdept ); 
		$tmp_healthdept->fips_code = DURC::formatForStorage( 'fips_code', 'varchar', $request->fips_code, $tmp_healthdept ); 
		$tmp_healthdept->healthdept_url = DURC::formatForStorage( 'healthdept_url', 'varchar', $request->healthdept_url, $tmp_healthdept ); 
		$tmp_healthdept->wikipedia_url = DURC::formatForStorage( 'wikipedia_url', 'varchar', $request->wikipedia_url, $tmp_healthdept ); 
		$tmp_healthdept->county_url = DURC::formatForStorage( 'county_url', 'varchar', $request->county_url, $tmp_healthdept ); 


	$id = $healthdept->id;
	
    try {
	    		$tmp_healthdept->save();

	} catch (\Exception $e) {
	          return redirect("/DURC/healthdept/{$id}")->with('status', 'There was an error in your data: '.$e->getMessage());

	}

	return redirect("/DURC/healthdept/$id")->with('status', 'Data Saved!');
        
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\healthdept  $healthdept
     * @return \Illuminate\Http\Response
     */
    public function destroy(healthdept $healthdept){
	    return healthdept::destroy( $healthdept->id );  
    }
    
    /**
     * Restore the specified resource from storage.
     * @param  $id ID of resource
     * @return \Illuminate\Http\Response
     */
    public function restore( $id )
    {
        $healthdept = healthdept::withTrashed()->find($id)->restore();
        return redirect("/DURC/test_soft_delete/$id")->with('status', 'Data Restored!');
    }
}
