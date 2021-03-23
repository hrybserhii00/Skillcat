<?php
defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir.'/formslib.php');
class registrationform extends moodleform {
    function definition() {
        global $CFG,$DB,$USER, $TEXTAREA_OPTIONS;
        $mform = $this->_form;

        $role = array(''=>'Select Role');

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'firstname', 'First Name:', 'maxlength="254" size="50" ');
        $mform->addRule('firstname', 'Missing First Name', 'required', 'client');
        $mform->setType('firstname', PARAM_MULTILANG); 

        $mform->addElement('text', 'lastname', 'Last Name:', 'maxlength="254" size="50" ');
        $mform->addRule('lastname', 'Missing Last Name', 'required', 'client');
        $mform->setType('lastname', PARAM_MULTILANG); 

        $mform->setType('username', PARAM_MULTILANG); 

        $mform->addElement('text', 'email', 'Email:', 'maxlength="254" size="50" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"');
        $mform->addRule('email', 'Missing Email', 'required', 'client');
        $mform->setType('email', PARAM_MULTILANG); 

        $mform->setForceLtr('email');

        $mform->addElement('text', 'password', get_string('password'), 'maxlength="32" size="15" autocomplete="off"');
        $mform->setType('password', core_user::get_property_type('password'));
        $mform->addRule('password', get_string('missingpassword'), 'required', 'client');
        
        
        $mform->addElement('text', 'contactnumber', 'Phone number:', 'maxlength="10" size="50" onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57"');
        $mform->addRule('contactnumber', 'Missing Contact Number', 'required', 'client');
        $mform->setType('contactnumber', PARAM_MULTILANG);  

        // $mform->addElement('text', 'companyname', 'Company Name:', 'maxlength="254" size="50"');
        // $mform->addRule('companyname', 'Missing Company Name', 'required', 'client');

        $state = array(''=>'Select State');
        $getcustomfielddurationtype = $DB->get_record_sql("SELECT *  FROM {user_info_field} WHERE shortname='worker_state' ");
        echo $getcustomfielddurationtype->param1;
        echo "<br>";
        //print_r(explode("", $getcustomfielddurationtype->param1));
        // // $durationtypeval = array('0'=>'');
        // // $i=0;
        // // $val=explode(',', $getdurationtype->param1);
        // // $val1=explode('"', $val[2]);
        // // $val2=explode('\r\n', $val1[3]);
        // // foreach ($val2 as $values) {
        // //     $i++;
        // //     $durationtypeval[$i] = $values;  
            
        // // }  
        //$state=array(''=>)
        $mform->addElement('select', 'state', 'State:', $state);
        $mform->addRule('state', 'Missing State', 'required', null, 'client');

        $city = array(''=>'Select City');
        $mform->addElement('select', 'city', 'City:', $city);
        $mform->addRule('city', 'Missing City', 'required', null, 'client');

      	$hvacexp = array(''=>'Select One');
        $mform->addElement('select', 'hvacexp', 'Years of HVAC experience', $hvacexp);
        //$mform->addRule('hvacexp', 'Missing City', 'required', null, 'client');

        $trade = array(''=>'Select One');
        $mform->addElement('select', 'trade', 'Trades worked in', $trade);

        $hvactrade = array(''=>'Select One');
        $mform->addElement('select', 'hvactrade', 'Years of non HVAC trade experience', $hvactrade);

        $hvacindustry = array(''=>'Select One');
        $mform->addElement('select', 'hvacindustry', 'HVAC Industry', $hvacindustry);

        $drug = array(''=>'Select One');
        $mform->addElement('select', 'drug', 'Are you able to pass a drug test?', $drug);

        $background = array(''=>'Select One');
        $mform->addElement('select', 'background', 'Are you able to pass a background check?', $background);

        $dl = array(''=>'Select One');
        $mform->addElement('select', 'dl', "Do you have a valid driver's license?", $dl);

        

        //$this->add_action_buttons(false, "SUBMIT");

        $buttonarray[] = $mform->createElement('submit', 'submitbutton', "Create worker account");
        $buttonarray[] = &$mform->createElement('cancel', 'cancelbutton', "Cancel");
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);     
    }

    public function validation($usernew, $files) {
        global $CFG, $DB;
        $formdata  = (object) $usernew;
        $error = array(); 
        if($DB->record_exists_sql("select * from {user} where email=? ", array($formdata->email))){
        	$error['email'] = " Email already exists";
	    }elseif($DB->record_exists_sql("select * from {user} where username=? ", array($formdata->username))){
	        $error['username'] = "username already exists";
	    }elseif($DB->record_exists_sql("select * from {user} where username=? ", array($formdata->email))){
	        $error['username'] = "username already exists";
	    }elseif($DB->record_exists_sql("select * from {user} where email=? ", array($formdata->username))){
	        $error['username'] = "username already exists";
	    }
        return $error;
    }
} 
?>