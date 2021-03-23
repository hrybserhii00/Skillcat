<?php
require('../../config.php');
require_once('loginform.php');
global $DB, $USER, $CFG, $COURSE;
$title = 'Login';
$PAGE->set_url($CFG->wwwroot.'/blocks/educational/adduser.php?id='.$id);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('base');
$PAGE->navbar->ignore_active();
$PAGE->navbar->add($title);

$addformaaa = new loginform();
if ($addformaaa->is_cancelled()) {
   redirect($CFG->wwwroot.'/my/index.php'); 
}
elseif($addform = $addformaaa->get_data())
{
	print_r($addform);die;
	$userinsert  = new stdClass();
    $userinsert->username = $addform->username;
    $userinsert->password= md5($addform->password);
    $userinsert->firstname = $addform->firstname;
    $userinsert->lastname = $addform->lastname;
    $userinsert->email = $addform->email;
    $userinsert->timecreated = time();
    $userinsert->timemodified = time();
    $userinsert->confirmed = 1;
    $userinsert->mnethostid = 1;
    //$userinsert->phone1 = $addform->contactnumber;
    //print_r($userinsert);die;
    $userid=$DB->insert_record('user', $userinsert);
	$user=$DB->get_record("user", array("id"=>$userid));
	if($user){

		//Start User organization
            $userpayment  = new stdClass();
            $userpayment->userid = $user->id;
            $userpayment->orgid= $addform->id;
            $userpayment->usermodified= $USER->id;
            $userpayment->timecreated= time();
            $userpaymentrecord=$DB->insert_record('user_org', $userpayment);

        //End User organization
		$context = context_system::instance();
    	role_assign($addform->roleid, $user->id, $context->id);

    	redirect($CFG->wwwroot.'/blocks/educational/index.php', 'User  Created Successfully ', null, \core\output\notification::NOTIFY_SUCCESS);
	}
}
echo $OUTPUT->header();
$addformaaa->display();

// $frm_user = $DB->get_records_sql("SELECT * FROM {states} WHERE country_id = 231 ");
// $stateid=[];
// foreach ($frm_user as $value) {
//     $stateid[]=$value->id;
//     // echo $value->name;
//     // echo "<br>";
//     // # code...
// }
// $allstate=implode(",", $stateid);
// $cityinfo = $DB->get_records_sql("SELECT * FROM {cities_list} WHERE state_id IN ($allstate) ORDER BY name ASC");
// foreach ($cityinfo as $cityRow) {
//     echo $cityRow->name;
//     echo "<br>";
// }
echo $OUTPUT->footer(); ?>

