<?php
require('../../config.php');
global $DB, $USER, $CFG, $COURSE;
$title = 'Forget Password';
$PAGE->set_url($CFG->wwwroot.'/blocks/educational/adduser.php?id='.$id);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('base');
$PAGE->navbar->ignore_active();
$PAGE->navbar->add('Login', new moodle_url($CFG->wwwroot.'/blocks/educational/index.php'));
$PAGE->navbar->add($title);

defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir.'/formslib.php');
class forgetpasswordform extends moodleform {
    function definition() {
        global $CFG,$DB,$USER, $TEXTAREA_OPTIONS;
        $mform = $this->_form;

        $mform->addElement('text', 'email', 'Email:', 'maxlength="254" size="50" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"');
        $mform->addRule('email', 'Missing Email', 'required', 'client');
        $mform->setType('email', PARAM_MULTILANG); 
        $mform->setForceLtr('email');
        //$this->add_action_buttons(false, "SUBMIT");

        $buttonarray[] = $mform->createElement('submit', 'submitbutton', "Reset Password");
        //$buttonarray[] = &$mform->createElement('cancel', 'cancelbutton', "Cancel");
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);     
    }

    public function validation($usernew, $files) {
        global $CFG, $DB;
        $formdata  = (object) $usernew;
        $error = array(); 
        // if($DB->record_exists_sql("select * from {user} where email=? ", array($formdata->email))){
        //     $error['email'] = " Email already exists";
        // }elseif($DB->record_exists_sql("select * from {user} where username=? ", array($formdata->username))){
        //     $error['username'] = "username already exists";
        // }elseif($DB->record_exists_sql("select * from {user} where username=? ", array($formdata->email))){
        //     $error['username'] = "username already exists";
        // }elseif($DB->record_exists_sql("select * from {user} where email=? ", array($formdata->username))){
        //     $error['username'] = "username already exists";
        // }
        return $error;
    }
} 

echo $OUTPUT->header();
?>
<?php 
$addformaaa = new forgetpasswordform();
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
	
    redirect($CFG->wwwroot.'/blocks/educational/index.php', 'User  Created Successfully ', null, \core\output\notification::NOTIFY_SUCCESS);
}
$addformaaa->display();
echo $OUTPUT->footer(); ?>

