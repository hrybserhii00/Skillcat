<?php
require_once (__DIR__ . '/../config.php');
    
    require_once 'class.phpmailer.php';
    
//$uname = (isset($_SERVER['PHP_AUTH_USER']));
//    $pwd = (isset($_SERVER['PHP_AUTH_PW']));
    
   // if ($uname == "pranali" && $pwd == "pranali") {
        
      global $DB;
        $now = time();
        $old_dt = strtotime('-7 day', $now);
        $output = '';
        $output.= "User Name" . ',';
        $output.= "Enrol Method" . ',';
        $output.= "First Name" . ',';
        $output.= "Last Name" . ',';
        $output.= "Register Date" . ',';
        $output.= "Email Address" . ',';
        $output.= "Enrolled Course" . ',';
        $output.= chr(10) . chr(13);
        $username = "";
        $enrol = "";
        $firstname = "";
        $lastname = "";
        $timecreated = "";
        $email = "";
        $coursename = "";
        $usr_count = "";
        $tablebody = "";
        $tablebody1 = "";
        
        // Below query will retrive data from sc_user table for last 7 days
        // User id 1 nd 2 for admins
        $register_user = $DB->get_recordset_sql('SELECT
                                                user2.firstname AS firstname,
                                                user2.lastname AS lastname,
                                                user2.email AS email,
                                                user2.username AS username,
                                                user2.timecreated AS timecreated,
                                                course.fullname AS coursename,
                                                en.enrol AS enrol
                                                FROM sc_course AS course
                                                JOIN sc_enrol AS en ON en.courseid = course.id
                                                JOIN sc_user_enrolments AS ue ON ue.enrolid = en.id
                                                JOIN sc_user AS user2 ON ue.userid = user2.id Where user2.timecreated > 0 AND user2.id NOT IN (1,2) AND ue.timecreated >= ? ', array(
                                                $old_dt
                                                ));
        
        
        if (is_null($register_user) || empty($register_user))
        {
            exit;
        }
        else
        {
            foreach($register_user as $id => $rec)
            {
                $record = new stdClass();
                $record->username = $rec->username;
                $record->enrol = $rec->enrol;
                $record->firstname = $rec->firstname;
                $record->lastname = $rec->lastname;
                $record->timecreated = $rec->timecreated;
                $record->timecreated = gmdate("Y-m-d", $rec->timecreated);
                $record->email = $rec->email;
                $record->coursename = $rec->coursename;
                $output.= $record->username . ',';
                $output.= $record->enrol . ',';
                $output.= $record->firstname . ',';
                $output.= $record->lastname . ',';
                $output.= $record->timecreated . ',';
                $output.= $record->email . ',';
                $output.= $record->coursename . ',';
                $output.= "\n";
                
            }
        }
        
        file_put_contents('New_Email_Report.csv', $output);
        
        $mail = new PHPMailer();
        
        $getCountPerCourse = $DB->get_recordset_sql('SELECT c.fullname As coursename, COUNT(ue.id) AS usr_count
                                                    FROM sc_course AS c
                                                    JOIN sc_enrol AS en ON en.courseid = c.id
                                                    JOIN sc_user_enrolments AS ue ON ue.enrolid = en.id Where ue.userid NOT IN (1,2) AND ue.timecreated >= ?
                                                    GROUP BY c.id
                                                    ORDER BY c.fullname', array(
                                                    $old_dt
                                                    ));
        
        $userNotEnrolInCourse = $DB->get_recordset_sql('Select mu.username As username,mu.firstname As firstname,mu.lastname As lastname,mu.timecreated as timecreated,mu.email As email
                                                       FROM sc_user mu
                                                       LEFT JOIN sc_user_enrolments ue  ON mu.id=ue.userid
                                                       LEFT JOIN sc_enrol e ON ue.enrolid=e.id
                                                       LEFT JOIN  sc_course c  ON c.id=e.courseid
                                                       WHERE c.fullName IS NULL AND mu.timecreated >= ? ', array(
                                                       $old_dt
                                                       ));
        
        if (is_null($userNotEnrolInCourse) || empty($userNotEnrolInCourse))
        {
            exit;
        }
        else
        {
            foreach($userNotEnrolInCourse as $id => $rec2)
            {
                $record2 = new stdClass();
                $record2->username = $rec2->username;
                $record2->firstname = $rec2->firstname;
                $record2->lastname = $rec2->lastname;
                $record2->timecreated = $rec2->timecreated;
                $tablebody1.= "<tr><td>" . $rec2->username . "</td><td>" . $rec2->firstname . "</td><td>" . $rec2->lastname . "</td></tr>";
            }
        }
        
        
        
        if (is_null($getCountPerCourse) || empty($getCountPerCourse))
        {
            exit;
        }
        else
        {
            foreach($getCountPerCourse as $id => $rec1)
            {
                $record1 = new stdClass();
                $record1->coursename = $rec1->coursename;
                $record1->usr_count = $rec1->usr_count;
                $tablebody.= "<tr><td>" . $rec1->coursename . "</td><td>" . $rec1->usr_count . "</td></tr>";
            }
        }
        
        $emailbody = "<div>";
        $emailbody.= "Dear Admin, <br /> Here are the details of students who registered on SkillCat moodle, for the last seven days.<br/><br />";
        $emailbody.= "<table border = '1'><tr><td><strong>Course Name</strong></td><td><strong>UserCount</strong></td></tr>";
        $emailbody.= "<tr><td>" . $tablebody . "</td></tr>";
        $emailbody.= "</table><br />";
        $emailbody.= "<br /> Here are the details of students who registered on SkillCat moodle,but has not enrolled in any course in last seven days.<br/><br />";
        $emailbody.= "<table border = '1'><tr><td><strong>Username</strong></td><td><strong>Firstname</strong></td><td><strong>Lastname</strong></td></tr>";
        $emailbody.= "<tr><td>" . $tablebody1 . "</td></tr>";
        $emailbody.= "</table>";
        $emailbody.= "</div>";
        
        $mail->From = 'pranali@learntodrill.com';
        $mail->FromName = 'pranali@learntodrill.com';
        
        $mail->AddAddress("pranali@learntodrill.com");
        
        //$mail->AddAddress("pranali@learntodrill.com");
        $mail->Subject = "SkillCat notification-Weekly user enrollment report";
        $mail->MsgHTML($emailbody);
        $mail->AddAttachment("New_Email_Report.csv");
        
        if (!$mail->Send())
        {
            exit;
        }
    //}
    //else {
      //  echo "You are not authorised User";
        //     $headers ="From: pranali@learntodrill.com";
        //     mail('pranali@learntodrill.com', "Certificate Link", "Some One runs certificate link", $headers);
    //}
    ?>

<!--SELECT u.username As username ,me.enrol As enrol,u.firstname As firstname,u.lastname As lastname,
DATE_FORMAT(FROM_UNIXTIME(mu.timecreated),'%Y-%m-%d') as TimeStart,u.email As email,
mc.fullName As coursename
FROM sc_user_enrolments as  mu
INNER Join sc_enrol as me ON me.id=mu.enrolid
INNER Join  sc_course as mc  ON mc.id=me.courseid
INNER Join  sc_user as u  ON u.id=mu.userid
WHERE mc.fullName IS NOT NULL AND mu.timecreated > 1563235200 -->
