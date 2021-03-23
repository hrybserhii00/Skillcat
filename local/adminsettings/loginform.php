<?php
defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir.'/formslib.php');
class loginform extends moodleform {
    function definition() {
        global $CFG,$DB,$USER, $TEXTAREA_OPTIONS;
        $mform = $this->_form;

        $mform->addElement('text', 'email', 'Email:', 'maxlength="254" size="50"');
        $mform->addRule('email', 'Missing Email', 'required', 'client');
        $mform->setType('email', PARAM_MULTILANG); 
        $mform->setForceLtr('email');

        $mform->addElement('text', 'password', get_string('password'), 'maxlength="32" size="15" autocomplete="off"');
        $mform->setType('password', core_user::get_property_type('password'));
        $mform->addRule('password', get_string('missingpassword'), 'required', 'client');
        
        //$this->add_action_buttons(false, "SUBMIT");

        $buttonarray[] = $mform->createElement('submit', 'submitbutton', "Login");
        //$buttonarray[] = &$mform->createElement('cancel', 'cancelbutton', "Cancel");
        //$buttonarray[] = $mform->createElement('submit', 'forgetpassword', "Forget Password");

        //$buttonarray[] = $mform->createElement('submit', 'signup', "SignUp");
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);

        $forgetpassword = '<a href="/local/adminsettings/forgetpassword.php">Forget Password        </a>';
		$mform->addElement('html', $forgetpassword); 
		$signup = '<a href="/local/adminsettings/registration.php">  SignUp</a>';
		$mform->addElement('html', $signup);    
    }

    public function validation($usernew, $files) {
        global $CFG, $DB;
        $formdata  = (object) $usernew;
        $error = array(); 
        $password=md5($usernew->password);
        $userRecords = $DB->get_record_sql("SELECT * FROM {user} WHERE (username='$usernew->email' OR email='$usernew->email') AND password='$password'"); 
    	if(empty($userRecords)){
    		$error['password'] = "Invalid username or password";
    	}
     //    if($DB->record_exists_sql("select * from {user} where email=? ", array($formdata->email))){
     //    	$error['email'] = " Email already exists";
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
?>