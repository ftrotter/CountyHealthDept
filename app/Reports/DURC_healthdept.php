<?php
/*
Note: because this file was signed, everything originally placed before the name space line has been replaced... with this comment ;)
FILE_SIG=c29392ac5f3c0a6a62d29c052fd2e433
*/
namespace App\Reports;
use CareSet\Zermelo\Reports\Tabular\AbstractTabularReport;

class DURC_healthdept extends AbstractTabularReport
{

    //returns the name of the report
    public function GetReportName(): string {
        $report_name = "healthdept Report";
        return($report_name);
    }

    //returns the description of the report. HTML is allowed here.
    public function GetReportDescription(): ?string {
        $desc = "View the healthdept data
			<br>
			<a href='/DURC/healthdept/create'>Add new healthdept</a>
";
        return($desc);
    }

    //  returns the SQL for the report.  This is the workhorse of the report.
    public function GetSQL()
    {

        $is_debug = false; //lots of debugging echos will be show instead of the report

        $index = $this->getCode();


	//get the local image field for this report... null if not found..
	$img_field_name = \App\healthdept::getImgField();
	if(isset($$img_field_name)){
		$img_field = $$img_field_name;
	}else{
		$img_field = null;
	}

	$joined_select_field_sql  = '';



	$state_field = \App\state::getNameField();	
	$joined_select_field_sql .= "
, A_state.$state_field  AS $state_field
"; 
	$state_img_field = \App\state::getImgField();
	if(!is_null($state_img_field)){
		if($is_debug){echo "state has an image field of: |$state_img_field|
";}
		$joined_select_field_sql .= "
, A_state.$state_img_field  AS $state_img_field
"; 
	}


        if(is_null($index)){

                $sql = "
SELECT
healthdept.id
$joined_select_field_sql 
, healthdept.healthdept_name AS healthdept_name
, healthdept.county_name AS county_name
, healthdept.population AS population
, healthdept.state_id AS state_id
, healthdept.fips_code AS fips_code
, healthdept.healthdept_url AS healthdept_url
, healthdept.wikipedia_url AS wikipedia_url
, healthdept.county_url AS county_url
, healthdept.created_at AS created_at
, healthdept.updated_at AS updated_at

FROM app_countyhealth.healthdept

LEFT JOIN app_countyhealth.state AS A_state ON 
	A_state.id =
	healthdept.state_id

";

        }else{

                $sql = "
SELECT
healthdept.id 
$joined_select_field_sql
, healthdept.healthdept_name AS healthdept_name
, healthdept.county_name AS county_name
, healthdept.population AS population
, healthdept.state_id AS state_id
, healthdept.fips_code AS fips_code
, healthdept.healthdept_url AS healthdept_url
, healthdept.wikipedia_url AS wikipedia_url
, healthdept.county_url AS county_url
, healthdept.created_at AS created_at
, healthdept.updated_at AS updated_at
 
FROM app_countyhealth.healthdept 

LEFT JOIN app_countyhealth.state AS A_state ON 
	A_state.id =
	healthdept.state_id

WHERE healthdept.id = $index
";

        }

        if($is_debug){
                echo "<pre>$sql";
                exit();
        }

        return $sql;
    }

    //decorate the results of the query with useful results
    public function MapRow(array $row, int $row_number) :array
    {

	$is_debug = false;
	
	//we think it is safe to extract here because we are getting this from the DB and not a user directly..
        extract($row);


	//get the local image field for this report... null if not found..
	$img_field_name = \App\healthdept::getImgField();
	if(isset($$img_field_name)){
		$img_field = $$img_field_name;
	}else{
		$img_field = null;
	}

	$joined_select_field_sql  = '';



	$state_field = \App\state::getNameField();	
	$joined_select_field_sql .= "
, A_state.$state_field  AS $state_field
"; 
	$state_img_field = \App\state::getImgField();
	if(!is_null($state_img_field)){
		if($is_debug){echo "state has an image field of: |$state_img_field|
";}
		$joined_select_field_sql .= "
, A_state.$state_img_field  AS $state_img_field
"; 
	}



        //link this row to its DURC editor
        $row['id'] = "<a href='/DURC/healthdept/$id'>$id</a>";



	if(isset($$img_field_name)){  //is it set
		if(strlen($img_field) > 0){ //and it is it really a url..
			$row[$img_field_name] = "<img width='300' src='$img_field'>";
		}
	}



$state_tmp = ''.$state_field;
if(isset($row[$state_tmp])){
	$state_data = $row[$state_tmp];
	$row[$state_tmp] = "<a target='_blank' href='/Zermelo/DURC_state/$state_id'>$state_data</a>";
}

$state_img_tmp = ''.$state_img_field;
if(isset($row[$state_img_tmp]) && strlen($state_img_tmp) > 0){
	$state_img_data = $row[$state_img_tmp];
	$row[$state_img_tmp] = "<img width='200px' src='$state_img_data'>";
}



        return $row;
    }

    //see Zermelo documentation to understand following functions:
    //https://github.com/CareSet/Zermelo

    public $NUMBER     = ['ROWS','AVG','LENGTH','DATA_FREE'];
    public $CURRENCY = [];
    public $SUGGEST_NO_SUMMARY = ['ID'];
    public $REPORT_VIEW = null;

    public function OverrideHeader(array &$format, array &$tags): void
    {
    }

    public function GetIndexSQL(): ?array {
                return(null);
    }

        //turns on the cache, should be off for development and small databases or simple queries
   public function isCacheEnabled(){
        return(false);
   }

        //only matters if the cache is on
   public function howLongToCacheInSeconds(){
        return(1200); //twenty minutes by default
   }

}

/*

//fields:
array (
  0 => 
  array (
    'column_name' => 'id',
    'data_type' => 'int',
    'is_primary_key' => true,
    'is_foreign_key' => false,
    'is_linked_key' => false,
    'foreign_db' => NULL,
    'foreign_table' => NULL,
    'is_nullable' => false,
    'default_value' => NULL,
    'is_auto_increment' => true,
  ),
  1 => 
  array (
    'column_name' => 'healthdept_name',
    'data_type' => 'varchar',
    'is_primary_key' => false,
    'is_foreign_key' => false,
    'is_linked_key' => false,
    'foreign_db' => NULL,
    'foreign_table' => NULL,
    'is_nullable' => false,
    'default_value' => NULL,
    'is_auto_increment' => false,
  ),
  2 => 
  array (
    'column_name' => 'county_name',
    'data_type' => 'varchar',
    'is_primary_key' => false,
    'is_foreign_key' => false,
    'is_linked_key' => false,
    'foreign_db' => NULL,
    'foreign_table' => NULL,
    'is_nullable' => false,
    'default_value' => NULL,
    'is_auto_increment' => false,
  ),
  3 => 
  array (
    'column_name' => 'population',
    'data_type' => 'int',
    'is_primary_key' => false,
    'is_foreign_key' => false,
    'is_linked_key' => false,
    'foreign_db' => NULL,
    'foreign_table' => NULL,
    'is_nullable' => false,
    'default_value' => NULL,
    'is_auto_increment' => false,
  ),
  4 => 
  array (
    'column_name' => 'state_id',
    'data_type' => 'int',
    'is_primary_key' => false,
    'is_foreign_key' => false,
    'is_linked_key' => true,
    'foreign_db' => 'app_countyhealth',
    'foreign_table' => 'state',
    'is_nullable' => false,
    'default_value' => '7',
    'is_auto_increment' => false,
  ),
  5 => 
  array (
    'column_name' => 'fips_code',
    'data_type' => 'varchar',
    'is_primary_key' => false,
    'is_foreign_key' => false,
    'is_linked_key' => false,
    'foreign_db' => NULL,
    'foreign_table' => NULL,
    'is_nullable' => false,
    'default_value' => NULL,
    'is_auto_increment' => false,
  ),
  6 => 
  array (
    'column_name' => 'healthdept_url',
    'data_type' => 'varchar',
    'is_primary_key' => false,
    'is_foreign_key' => true,
    'is_linked_key' => true,
    'foreign_db' => NULL,
    'foreign_table' => NULL,
    'is_nullable' => false,
    'default_value' => NULL,
    'is_auto_increment' => false,
  ),
  7 => 
  array (
    'column_name' => 'wikipedia_url',
    'data_type' => 'varchar',
    'is_primary_key' => false,
    'is_foreign_key' => false,
    'is_linked_key' => false,
    'foreign_db' => NULL,
    'foreign_table' => NULL,
    'is_nullable' => false,
    'default_value' => NULL,
    'is_auto_increment' => false,
  ),
  8 => 
  array (
    'column_name' => 'county_url',
    'data_type' => 'varchar',
    'is_primary_key' => false,
    'is_foreign_key' => false,
    'is_linked_key' => false,
    'foreign_db' => NULL,
    'foreign_table' => NULL,
    'is_nullable' => false,
    'default_value' => NULL,
    'is_auto_increment' => false,
  ),
  9 => 
  array (
    'column_name' => 'created_at',
    'data_type' => 'datetime',
    'is_primary_key' => false,
    'is_foreign_key' => false,
    'is_linked_key' => false,
    'foreign_db' => NULL,
    'foreign_table' => NULL,
    'is_nullable' => false,
    'default_value' => 'current_timestamp()',
    'is_auto_increment' => false,
  ),
  10 => 
  array (
    'column_name' => 'updated_at',
    'data_type' => 'datetime',
    'is_primary_key' => false,
    'is_foreign_key' => false,
    'is_linked_key' => false,
    'foreign_db' => NULL,
    'foreign_table' => NULL,
    'is_nullable' => false,
    'default_value' => 'current_timestamp()',
    'is_auto_increment' => false,
  ),
)
//has_many
array (
  'healthdept_url' => 
  array (
    'prefix' => NULL,
    'type' => 'healthdept_url',
    'from_table' => 'healthdept_url',
    'from_db' => 'app_countyhealth',
    'from_column' => 'healthdept_id',
    'other_columns' => 
    array (
      0 => 
      array (
        'column_name' => 'id',
        'data_type' => 'int',
        'is_primary_key' => true,
        'is_foreign_key' => false,
        'is_linked_key' => false,
        'foreign_db' => NULL,
        'foreign_table' => NULL,
        'is_nullable' => false,
        'default_value' => NULL,
        'is_auto_increment' => true,
      ),
      1 => 
      array (
        'column_name' => 'url',
        'data_type' => 'varchar',
        'is_primary_key' => false,
        'is_foreign_key' => false,
        'is_linked_key' => false,
        'foreign_db' => NULL,
        'foreign_table' => NULL,
        'is_nullable' => false,
        'default_value' => NULL,
        'is_auto_increment' => false,
      ),
      2 => 
      array (
        'column_name' => 'search_term',
        'data_type' => 'varchar',
        'is_primary_key' => false,
        'is_foreign_key' => false,
        'is_linked_key' => false,
        'foreign_db' => NULL,
        'foreign_table' => NULL,
        'is_nullable' => false,
        'default_value' => NULL,
        'is_auto_increment' => false,
      ),
      3 => 
      array (
        'column_name' => 'healthdept_id',
        'data_type' => 'int',
        'is_primary_key' => false,
        'is_foreign_key' => false,
        'is_linked_key' => true,
        'foreign_db' => 'app_countyhealth',
        'foreign_table' => 'healthdept',
        'is_nullable' => false,
        'default_value' => NULL,
        'is_auto_increment' => false,
      ),
      4 => 
      array (
        'column_name' => 'created_at',
        'data_type' => 'datetime',
        'is_primary_key' => false,
        'is_foreign_key' => false,
        'is_linked_key' => false,
        'foreign_db' => NULL,
        'foreign_table' => NULL,
        'is_nullable' => false,
        'default_value' => 'current_timestamp()',
        'is_auto_increment' => false,
      ),
      5 => 
      array (
        'column_name' => 'updated_at',
        'data_type' => 'datetime',
        'is_primary_key' => false,
        'is_foreign_key' => false,
        'is_linked_key' => false,
        'foreign_db' => NULL,
        'foreign_table' => NULL,
        'is_nullable' => false,
        'default_value' => 'current_timestamp()',
        'is_auto_increment' => false,
      ),
    ),
  ),
)
//has_one
NULL
//belongs_to
array (
  'state' => 
  array (
    'prefix' => NULL,
    'type' => 'state',
    'to_table' => 'state',
    'to_db' => 'app_countyhealth',
    'local_key' => 'state_id',
    'other_columns' => 
    array (
      0 => 
      array (
        'column_name' => 'id',
        'data_type' => 'int',
        'is_primary_key' => true,
        'is_foreign_key' => false,
        'is_linked_key' => false,
        'foreign_db' => NULL,
        'foreign_table' => NULL,
        'is_nullable' => false,
        'default_value' => NULL,
        'is_auto_increment' => true,
      ),
      1 => 
      array (
        'column_name' => 'ssa_state_code',
        'data_type' => 'varchar',
        'is_primary_key' => false,
        'is_foreign_key' => false,
        'is_linked_key' => false,
        'foreign_db' => NULL,
        'foreign_table' => NULL,
        'is_nullable' => false,
        'default_value' => NULL,
        'is_auto_increment' => false,
      ),
      2 => 
      array (
        'column_name' => 'short_state',
        'data_type' => 'varchar',
        'is_primary_key' => false,
        'is_foreign_key' => true,
        'is_linked_key' => true,
        'foreign_db' => NULL,
        'foreign_table' => NULL,
        'is_nullable' => true,
        'default_value' => 'NULL',
        'is_auto_increment' => false,
      ),
      3 => 
      array (
        'column_name' => 'state_name',
        'data_type' => 'varchar',
        'is_primary_key' => false,
        'is_foreign_key' => false,
        'is_linked_key' => false,
        'foreign_db' => NULL,
        'foreign_table' => NULL,
        'is_nullable' => true,
        'default_value' => 'NULL',
        'is_auto_increment' => false,
      ),
      4 => 
      array (
        'column_name' => 'state_ucase',
        'data_type' => 'varchar',
        'is_primary_key' => false,
        'is_foreign_key' => false,
        'is_linked_key' => false,
        'foreign_db' => NULL,
        'foreign_table' => NULL,
        'is_nullable' => false,
        'default_value' => NULL,
        'is_auto_increment' => false,
      ),
      5 => 
      array (
        'column_name' => 'state_or_territory',
        'data_type' => 'varchar',
        'is_primary_key' => false,
        'is_foreign_key' => false,
        'is_linked_key' => false,
        'foreign_db' => NULL,
        'foreign_table' => NULL,
        'is_nullable' => true,
        'default_value' => 'NULL',
        'is_auto_increment' => false,
      ),
      6 => 
      array (
        'column_name' => 'population',
        'data_type' => 'int',
        'is_primary_key' => false,
        'is_foreign_key' => false,
        'is_linked_key' => false,
        'foreign_db' => NULL,
        'foreign_table' => NULL,
        'is_nullable' => false,
        'default_value' => NULL,
        'is_auto_increment' => false,
      ),
      7 => 
      array (
        'column_name' => 'is_known_good_state',
        'data_type' => 'tinyint',
        'is_primary_key' => false,
        'is_foreign_key' => false,
        'is_linked_key' => false,
        'foreign_db' => NULL,
        'foreign_table' => NULL,
        'is_nullable' => false,
        'default_value' => '1',
        'is_auto_increment' => false,
      ),
    ),
  ),
)
//many_many
NULL
//many_through
NULL*/


?>