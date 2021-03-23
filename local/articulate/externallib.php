<?php
/**
 * External Web Service
 *
 * @package    articulateservices
 * @copyright  2020 SkillCat LLC (http://skillcatapp.com)
 */
require_once($CFG->libdir . "/externallib.php");

class local_articulate_external extends external_api {

    /**
     * Describes the parameters for save_grade
     * @return external_function_parameters
     */
    public static function save_grade_parameters() {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'The student id for the user who has completed the quiz'),
                'courseid' => new external_value(PARAM_INT, 'The course id of the container course'),
                'activityname' => new external_value(PARAM_RAW, 'The matching name of both the URL activity and grade item'),
                'grade' => new external_value(PARAM_RAW, 'The users grade for this quiz')
            )
        );
    }
    
    /**
     * Saves a student's articulate quiz score
     *
     * @param int $userid - the id of the user
     * @param int $courseid - the id of the course
     * @param int $activityname - used to join url.name to grade_item.itemname
     * @param int $grade - the user's grade for this quiz
     * @return null
     */

    public static function save_grade($userid, $courseid, $activityname, $grade) {
        global $DB;
        global $CFG;
        //global $USER;

        //Parameter validation
        $params = self::validate_parameters(self::save_grade_parameters(),
            array('userid' => $userid,
                  'courseid' => $courseid,
                  'activityname' => urldecode($activityname),
                  'grade' => $grade));

        //Get [grade_items.id]
        $itemParams = array('courseid'=>$params['courseid'], 'itemname'=>$params['activityname']);
        $itemid = $DB->get_field('grade_items', 'id', $itemParams, $strictness=MUST_EXIST);
        
        //Get existing grade [grade_grades.id] if exists
        $gradeParams = array('itemid'=>$itemid, 'userid'=>$params['userid']);
        $gradeid = $DB->get_field('grade_grades', 'id', $gradeParams, $strictness=IGNORE_MISSING);
        
        //Create data object to be inserted/updated
        $dataObj = new stdClass();
        $dataObj->userid = $userid;
        $dataObj->itemid = $itemid;
        $dataObj->usermodified = 2;
        $dataObj->finalgrade = $grade;
        $dataObj->timemodified = time();
        
        //Update existing or insert new record accordingly
        if ($gradeid > 0) {
            //Update existing grade record
            //Note: history is not currently being created
            $dataObj->id = $gradeid;
            $return = $DB->update_record('grade_grades', $dataObj, $bulk=false);
        } else {
            //Insert new grade record
            $return = $DB->insert_record('grade_grades', $dataObj, $returnid=true, $bulk=false);
        };

        return null;
    }

    /**
     * Describes the return value for save grade
     * @return external_single_structure
     */
    public static function save_grade_returns() {
        return null;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function create_users_parameters() {
        global $CFG;
        $userfields = [
            'createpassword' => new external_value(PARAM_BOOL, 'True if password should be created and mailed to user.',
                VALUE_OPTIONAL),
            // General.
            'username' => new external_value(core_user::get_property_type('username'),
                'Username policy is defined in Moodle security config.'),
            'auth' => new external_value(core_user::get_property_type('auth'), 'Auth plugins include manual, ldap, etc',
                VALUE_DEFAULT, 'manual', core_user::get_property_null('auth')),
            'password' => new external_value(core_user::get_property_type('password'),
                'Plain text password consisting of any characters', VALUE_OPTIONAL),
            'firstname' => new external_value(core_user::get_property_type('firstname'), 'The first name(s) of the user'),
            'lastname' => new external_value(core_user::get_property_type('lastname'), 'The family name of the user'),
            'email' => new external_value(core_user::get_property_type('email'), 'A valid and unique email address'),
            'maildisplay' => new external_value(core_user::get_property_type('maildisplay'), 'Email display', VALUE_OPTIONAL),
            'city' => new external_value(core_user::get_property_type('city'), 'Home city of the user', VALUE_OPTIONAL),
            'country' => new external_value(core_user::get_property_type('country'),
                'Home country code of the user, such as AU or CZ', VALUE_OPTIONAL),
            'timezone' => new external_value(core_user::get_property_type('timezone'),
                'Timezone code such as Australia/Perth, or 99 for default', VALUE_OPTIONAL),
            'description' => new external_value(core_user::get_property_type('description'), 'User profile description, no HTML',
                VALUE_OPTIONAL),
            // Additional names.
            'firstnamephonetic' => new external_value(core_user::get_property_type('firstnamephonetic'),
                'The first name(s) phonetically of the user', VALUE_OPTIONAL),
            'lastnamephonetic' => new external_value(core_user::get_property_type('lastnamephonetic'),
                'The family name phonetically of the user', VALUE_OPTIONAL),
            'middlename' => new external_value(core_user::get_property_type('middlename'), 'The middle name of the user',
                VALUE_OPTIONAL),
            'alternatename' => new external_value(core_user::get_property_type('alternatename'), 'The alternate name of the user',
                VALUE_OPTIONAL),
            // Interests.
            'interests' => new external_value(PARAM_TEXT, 'User interests (separated by commas)', VALUE_OPTIONAL),
            // Optional.
            'url' => new external_value(core_user::get_property_type('url'), 'User web page', VALUE_OPTIONAL),
            'icq' => new external_value(core_user::get_property_type('icq'), 'ICQ number', VALUE_OPTIONAL),
            'skype' => new external_value(core_user::get_property_type('skype'), 'Skype ID', VALUE_OPTIONAL),
            'aim' => new external_value(core_user::get_property_type('aim'), 'AIM ID', VALUE_OPTIONAL),
            'yahoo' => new external_value(core_user::get_property_type('yahoo'), 'Yahoo ID', VALUE_OPTIONAL),
            'msn' => new external_value(core_user::get_property_type('msn'), 'MSN ID', VALUE_OPTIONAL),
            'idnumber' => new external_value(core_user::get_property_type('idnumber'),
                'An arbitrary ID code number perhaps from the institution', VALUE_DEFAULT, ''),
            'institution' => new external_value(core_user::get_property_type('institution'), 'institution', VALUE_OPTIONAL),
            'department' => new external_value(core_user::get_property_type('department'), 'department', VALUE_OPTIONAL),
            'phone1' => new external_value(core_user::get_property_type('phone1'), 'Phone 1', VALUE_OPTIONAL),
            'phone2' => new external_value(core_user::get_property_type('phone2'), 'Phone 2', VALUE_OPTIONAL),
            'address' => new external_value(core_user::get_property_type('address'), 'Postal address', VALUE_OPTIONAL),
            // Other user preferences stored in the user table.
            'lang' => new external_value(core_user::get_property_type('lang'), 'Language code such as "en", must exist on server',
                VALUE_DEFAULT, core_user::get_property_default('lang'), core_user::get_property_null('lang')),
            'calendartype' => new external_value(core_user::get_property_type('calendartype'),
                'Calendar type such as "gregorian", must exist on server', VALUE_DEFAULT, $CFG->calendartype, VALUE_OPTIONAL),
            'theme' => new external_value(core_user::get_property_type('theme'),
                'Theme name such as "standard", must exist on server', VALUE_OPTIONAL),
            'mailformat' => new external_value(core_user::get_property_type('mailformat'),
                'Mail format code is 0 for plain text, 1 for HTML etc', VALUE_OPTIONAL),
            // Custom user profile fields.
            'customfields' => new external_multiple_structure(
                new external_single_structure(
                    [
                        'type'  => new external_value(PARAM_ALPHANUMEXT, 'The name of the custom field'),
                        'value' => new external_value(PARAM_RAW, 'The value of the custom field')
                    ]
                ), 'User custom fields (also known as user profile fields)', VALUE_OPTIONAL),
            // User preferences.
            'preferences' => new external_multiple_structure(
            new external_single_structure(
                [
                    'type'  => new external_value(PARAM_RAW, 'The name of the preference'),
                    'value' => new external_value(PARAM_RAW, 'The value of the preference')
                ]
            ), 'User preferences', VALUE_OPTIONAL),
        ];
        return new external_function_parameters(
            [
                'users' => new external_multiple_structure(
                    new external_single_structure($userfields)
                )
            ]
        );
    }

    /**
     * Create one or more users.
     *
     * @throws invalid_parameter_exception
     * @param array $users An array of users to create.
     * @return array An array of arrays
     */
    public static function create_users($users) {
        global $CFG, $DB;
        require_once($CFG->dirroot."/lib/weblib.php");
        require_once($CFG->dirroot."/user/lib.php");
        require_once($CFG->dirroot."/user/editlib.php");
        require_once($CFG->dirroot."/user/profile/lib.php"); // Required for customfields related function.

        // Ensure the current user is allowed to run this function.
        //$context = context_system::instance();
        //self::validate_context($context);
        // require_capability('moodle/user:create', $context);

        // Do basic automatic PARAM checks on incoming data, using params description.
        // If any problems are found then exceptions are thrown with helpful error messages.
        $params = self::validate_parameters(self::create_users_parameters(), array('users' => $users));

        $availableauths  = core_component::get_plugin_list('auth');
        unset($availableauths['mnet']);       // These would need mnethostid too.
        unset($availableauths['webservice']); // We do not want new webservice users for now.

        $availablethemes = core_component::get_plugin_list('theme');
        $availablelangs  = get_string_manager()->get_list_of_translations();

        $transaction = $DB->start_delegated_transaction();

        $userids = array();
        foreach ($params['users'] as $user) {
            // Make sure that the username, firstname and lastname are not blank.
            foreach (array('username', 'firstname', 'lastname') as $fieldname) {
                if (trim($user[$fieldname]) === '') {
                    throw new invalid_parameter_exception('The field '.$fieldname.' cannot be blank');
                }
            }

            // Make sure that the username doesn't already exist.
            if ($DB->record_exists('user', array('username' => $user['username'], 'mnethostid' => $CFG->mnet_localhost_id))) {
                throw new invalid_parameter_exception('Username already exists: '.$user['username']);
            }

            // Make sure auth is valid.
            if (empty($availableauths[$user['auth']])) {
                throw new invalid_parameter_exception('Invalid authentication type: '.$user['auth']);
            }

            // Make sure lang is valid.
            if (empty($availablelangs[$user['lang']])) {
                throw new invalid_parameter_exception('Invalid language code: '.$user['lang']);
            }

            // Make sure lang is valid.
            if (!empty($user['theme']) && empty($availablethemes[$user['theme']])) { // Theme is VALUE_OPTIONAL,
                                                                                     // so no default value
                                                                                     // We need to test if the client sent it
                                                                                     // => !empty($user['theme']).
                throw new invalid_parameter_exception('Invalid theme: '.$user['theme']);
            }

            // Make sure we have a password or have to create one.
            $authplugin = get_auth_plugin($user['auth']);
            if ($authplugin->is_internal() && empty($user['password']) && empty($user['createpassword'])) {
                $user['password'] = $user['email'] . 'XYZ2021!';
                //throw new invalid_parameter_exception('Invalid password: you must provide a password, or set createpassword.');
            }

            $user['confirmed'] = true;
            $user['mnethostid'] = $CFG->mnet_localhost_id;

            // Start of user info validation.
            // Make sure we validate current user info as handled by current GUI. See user/editadvanced_form.php func validation().
            if (!validate_email($user['email'])) {
                throw new invalid_parameter_exception('Email address is invalid: '.$user['email']);
            } else if (empty($CFG->allowaccountssameemail)) {
                // Make a case-insensitive query for the given email address.
                $select = $DB->sql_equal('email', ':email', false) . ' AND mnethostid = :mnethostid';
                $params = array(
                    'email' => $user['email'],
                    'mnethostid' => $user['mnethostid']
                );
                // If there are other user(s) that already have the same email, throw an error.
                if ($DB->record_exists_select('user', $select, $params)) {
                    throw new invalid_parameter_exception('Email address already exists: '.$user['email']);
                }
            }
            // End of user info validation.

            $createpassword = !empty($user['createpassword']);
            unset($user['createpassword']);
            $updatepassword = false;
            if ($authplugin->is_internal()) {
                if ($createpassword) {
                    $user['password'] = '';
                } else {
                    $updatepassword = true;
                }
            } else {
                $user['password'] = AUTH_PASSWORD_NOT_CACHED;
            }

            // Create the user data now!
            $user['id'] = user_create_user($user, $updatepassword, false);

            $userobject = (object)$user;

            // Set user interests.
            if (!empty($user['interests'])) {
                $trimmedinterests = array_map('trim', explode(',', $user['interests']));
                $interests = array_filter($trimmedinterests, function($value) {
                    return !empty($value);
                });
                useredit_update_interests($userobject, $interests);
            }

            // Custom fields.
            if (!empty($user['customfields'])) {
                foreach ($user['customfields'] as $customfield) {
                    // Profile_save_data() saves profile file it's expecting a user with the correct id,
                    // and custom field to be named profile_field_"shortname".
                    $user["profile_field_".$customfield['type']] = $customfield['value'];
                }
                profile_save_data((object) $user);
            }

            if ($createpassword) {
                setnew_password_and_mail($userobject);
                unset_user_preference('create_password', $userobject);
                set_user_preference('auth_forcepasswordchange', 1, $userobject);
            }

            // Trigger event.
            \core\event\user_created::create_from_userid($user['id'])->trigger();

            // Preferences.
            if (!empty($user['preferences'])) {
                $userpref = (object)$user;
                foreach ($user['preferences'] as $preference) {
                    $userpref->{'preference_'.$preference['type']} = $preference['value'];
                }
                useredit_update_user_preference($userpref);
            }

            $userids[] = array('id' => $user['id']);
        }

        $transaction->allow_commit();

        return $userids;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function create_users_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(core_user::get_property_type('id'), 'user id')
                )
            )
        );
    }

    /**
     * Describes the parameters for enrol_user
     * @return external_function_parameters
     */
    public static function enrol_user_parameters() {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'The user that is going to be enrolled'),
                'courseid' => new external_value(PARAM_INT, 'The course to enrol the user role in'),
                'roleid' => new external_value(PARAM_INT, 'Role to assign to the user')
            )
        );
    }

    /**
     * Enrolment of users.
     *
     * Function throw an exception at the first error encountered.
     * @param int $userid - the id of the user
     * @param int $courseid - the id of the course
     * @param int $roleid - the id of the user's role in the course
     */
    public static function enrol_user($userid, $courseid, $roleid) {
        global $DB, $CFG;

        require_once($CFG->libdir . '/enrollib.php');

        //Parameter validation
        $enrolment = self::validate_parameters(self::enrol_user_parameters(),
            array('userid' => $userid,
                  'courseid' => $courseid,
                  'roleid' => $roleid));

        // Rollback all enrolment if an error occurs
        $transaction = $DB->start_delegated_transaction(); 

        // Retrieve the manual enrolment plugin.
        $enrol = enrol_get_plugin('manual');
        if (empty($enrol)) {
            throw new moodle_exception('manualpluginnotinstalled', 'enrol_manual');
        }

        // Check manual enrolment plugin instance is enabled/exist.
        $instance = null;
        $enrolinstances = enrol_get_instances($enrolment['courseid'], true);
        
        foreach ($enrolinstances as $courseenrolinstance) {
          if ($courseenrolinstance->enrol == "manual") {
              $instance = $courseenrolinstance;
              break;
          }
        }
        if (empty($instance)) {
          $errorparams = new stdClass();
          $errorparams->courseid = $enrolment['courseid'];
          throw new moodle_exception('wsnoinstance', 'enrol_manual', $errorparams);
        }

        // Check that the plugin accept enrolment (it should always the case, it's hard coded in the plugin).
        if (!$enrol->allow_enrol($instance)) {
            $errorparams = new stdClass();
            $errorparams->roleid = $enrolment['roleid'];
            $errorparams->courseid = $enrolment['courseid'];
            $errorparams->userid = $enrolment['userid'];
            throw new moodle_exception('wscannotenrol', 'enrol_manual', '', $errorparams);
        }

        // Finally proceed the enrolment.
        $enrolment['timestart'] = isset($enrolment['timestart']) ? $enrolment['timestart'] : 0;
        $enrolment['timeend'] = isset($enrolment['timeend']) ? $enrolment['timeend'] : 0;
        $enrolment['status'] = (isset($enrolment['suspend']) && !empty($enrolment['suspend'])) ?
                ENROL_USER_SUSPENDED : ENROL_USER_ACTIVE;

        $enrol->enrol_user($instance, $enrolment['userid'], $enrolment['roleid'],
                $enrolment['timestart'], $enrolment['timeend'], $enrolment['status']);

        $transaction->allow_commit();
        
        return null;
    }

    /**
     * Returns description of method result value.
     *
     * @return null
     */
    public static function enrol_user_returns() {
        //return new external_value(core_user::get_property_type('id'), 'user id');
        return null;
    }

    /**
     * Describes the parameters for save_grade
     * @return external_function_parameters
     */
    public static function save_analytic_parameters() {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'The student id for the user who has accessed the quiz'),
                'coursemoduleid' => new external_value(PARAM_INT, 'The course module id of the simulation activity'),
                'type' => new external_value(PARAM_INT, 'The type of data being tracked, defaults to: event'),
                'category' => new external_value(PARAM_RAW, 'The category of the tracking item'),
                'action' => new external_value(PARAM_RAW, 'The action of the tracking item'),
                'label' => new external_value(PARAM_RAW, 'The label of the tracking item'),
                'value' => new external_value(PARAM_RAW, 'The value of the tracking item (optional)')
            )
        );
    }
    
    /**
     * Saves a student's articulate quiz score
     *
     * @param int $userid - the id of the user
     * @param int $coursemoduleid - the id of the course
     * @param smallint $type - The type of data being tracked, defaults to: 1 = event
     * @param varchar(15) $category - The category of the tracking item
     * @param varchar(15) $action - The action of the tracking item
     * @param varchar(15) $label - The label of the tracking item
     * @param varchar(15) $value - The value of the tracking item (optional)
     * @return null
     */
    
    public static function save_analytic($userid, $coursemoduleid, $type = 1, $category, $action, $label, $value = '') {
        global $DB;
        global $CFG;

        //Parameter validation
        $params = self::validate_parameters(self::save_analytic_parameters(),
            array('userid' => $userid,
                  'coursemoduleid' => $coursemoduleid,
                  'type' => $type,
                  'category' => $category,
                  'action' => $action,
                  'label' => $label,
                  'value' => $value));
        
        //Create data object to be inserted/updated
        $dataObj = new stdClass();
        $dataObj->userid = $params["userid"];
        $dataObj->coursemoduleid = $params["coursemoduleid"];
        $dataObj->type = $params["type"];
        $dataObj->category = $params["category"];
        $dataObj->action = $params["action"];
        $dataObj->label = $params["label"];
        $dataObj->value = $params["value"];
        
        $return = $DB->insert_record('skillcat_analytics', $dataObj, $returnid=false, $bulk=false);

        return null;
    }

    /**
     * Describes the return value for save grade 
     * @return external_single_structure
     */
    public static function save_analytic_returns() {
        return null;
    }

}
