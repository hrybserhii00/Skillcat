<?php 
require('../../config.php');
global $DB, $USER;
if($_REQUEST['action']=='getcity')
{ 
    $stateid = implode(",", $_REQUEST['stateid']);

    echo'<select name="city">';
    echo '<option value="">Select City</option>';
    $citiesinfo= $DB->get_records_sql("SELECT * FROM {cities_list} WHERE state_id IN ($stateid) ORDER BY name ASC");
	if($citiesinfo){  
        foreach ($citiesinfo as $citiesRow) {
           echo '<option value="'.$citiesRow->id.'">'.$citiesRow->name.'</option>';
            echo '</select>';
        }   
    } 
}

?>
