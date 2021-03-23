<?php
require('../../config.php');
require_once('registrationform.php');
// require_login();
global $DB, $USER, $CFG, $COURSE;
$title = 'Registration Form';
$PAGE->set_url($CFG->wwwroot.'/blocks/educational/adduser.php?id='.$id);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('base');
$PAGE->navbar->ignore_active();
$PAGE->navbar->add($title);
echo $OUTPUT->header();
?>
<div class="row">
    <div class="col-md-12 text-center">
        <h1>Create worker's Account </h1>
        <br><br>
        <h6><span></span> Please enter the following information</h6><br>
    </div>
</div>
<?php 
$addformaaa = new registrationform();
if ($addformaaa->is_cancelled()) {
   redirect($CFG->wwwroot.'/my/index.php'); 
}
elseif($addform = $addformaaa->get_data())
{
	print_r($addform);
    echo"<br>";
    print_r($_POST);
    die;
	$userinsert  = new stdClass();
    $userinsert->username = $addform->email;
    $userinsert->password= md5($addform->password);
    $userinsert->firstname = $addform->firstname;
    $userinsert->lastname = $addform->lastname;
    $userinsert->email = $addform->email;
    $userinsert->timecreated = time();
    $userinsert->timemodified = time();
    //$userinsert->confirmed = 1;
    $userinsert->mnethostid = 1;
    $userinsert->phone1 = $addform->contactnumber;
    //print_r($userinsert);die;
    $userid=$DB->insert_record('user', $userinsert);
	$user=$DB->get_record("user", array("id"=>$userid));
	if($user){
		//Start User Company Details
            $usercompanyinsert  = new stdClass();
            $usercompanyinsert->userid = $user->id;
            $usercompanyinsert->companyname= $addform->companyname;
            $usercompanyinsert->state= implode(",", $addform->state);
            $usercompanyinsert->city = implode(",", $_POST['city']);
            $usercompanyinsert->industry= $addform->industry;
            $usercompanyinsert->hvac= $addform->hvac;
            $usercompanyinsert->hvachire = $addform->hvachire;
            $usercompanyinsert->role= implode(",", $addform->role);
            $usercompanyinsert->description= $addform->description['text'];
            $usercompanyinsert->logo = '';
            $usercompanyinsert->fblink= $addform->fblink;
            $usercompanyinsert->linkedinlink= $addform->linkedinlink;
            $usercompanyinsert->calendly = $addform->calendly;
            $usercompanyinsert->createdtime= time();
            $usercompanyrecord=$DB->insert_record('user_company_details', $usercompanyinsert);

        //End User Company Details
		// $context = context_system::instance();
  //   	role_assign($addform->roleid, $user->id, $context->id);

    	redirect($CFG->wwwroot.'/login/index.php', 'User with company profile Created Successfully ', null, \core\output\notification::NOTIFY_SUCCESS);
	}
}
$addformaaa->display();
echo $OUTPUT->footer(); ?>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script>

$("#id_state").change(function(){
    var stateid=$(this).val();
    alert(stateid)
    $.ajax({
        type:'POST',
        url:'ajax.php',
        data :{action: 'getcity',stateid:stateid},
        success:function(data){
            var mydata = data.split("#");
            console.log(mydata);
            var value=mydata[0].trim(); 
            $('#id_city').html(value);
            //debugger;
        }
    }); 
});

</script>
