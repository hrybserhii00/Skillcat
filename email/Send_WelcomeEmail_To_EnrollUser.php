
application/x-httpd-php Send_WelcomeEmail_To_EnrollUser.php ( ASCII English text )
	<?php
/*******************************************************************************************************************************************************
Created By- Baiju  10-June-2016.
Agilewrap Req ID-784 - Customized enrollment message

Purpose- This File will send Welcome email and request for ipad delivery address,to all newly enrolled user on Moodle Oilschool.com
Working- This script takes all student email address who registered in moodle during last 12 hours from current date, and send Email to enrolled user.
require 'emailTemplate.php';
*******************************************************************************************************************************************************/
//$uname = $_SERVER['PHP_AUTH_USER'];
//$pwd = $_SERVER['PHP_AUTH_PW'];

//if ($uname == "authorised" && $pwd == "@utH0rseDscrIpt")
	//if(1==1)
//	{

		require 'emailTemplate.php';

		$date = new DateTime();
		echo $to_date = $date->format('Y m d H:i:s');
		 $date->modify('-24 hour');
		echo $from_date = $date->format('Y m d H:i:s');
//------------------Database details------------------

		$dbname = 'gurooa7_onlinedrilling';   /*Live DB connection*/
		$dbhost = 'localhost';
		$dbuser = 'gurooa7_drillinguser';
		$dbpass = 'Le0p0ldBl00m'; 

		
	/*$dbname = 'learn212_moodtst';
	$dbhost = 'localhost';
	$dbuser = 'learn212_root';
	$dbpass = '49PU2).8SK';*/
//----------------------------------------------------
		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	
		$eol = PHP_EOL;	
		$coursename=array();
		$username = array();
		$usermail =array();
		$cntid=array();
		if(! $conn )
			{
			  die('Could not connect: ' . mysql_error());
			}
				mysql_select_db($dbname);
				//Below query will get all list of users who are newly enrolled in Moodle in last 12 hrs

					$mysql = "SELECT mu.username as userName,mu.FirstName as firstName,mu.email as Emailid,mc.fullname as CourseName,me.courseid as CourseId
								FROM mdl_user_enrolments as mue 
									INNER JOIN  mdl_enrol as me ON me.id=mue.enrolid 
									INNER JOIN mdl_user as mu ON mu.id=mue.userid
									INNER JOIN  mdl_course as mc ON mc.id=me.courseid
									WHERE mc.id = 25 AND DATE_FORMAT(FROM_UNIXTIME(mue.timecreated),'%Y %m %d %H:%i:%s') > '$from_date'";

				$retval = mysql_query( $mysql, $conn );
				if(! $retval )
				{
				  die('Could not get data: ' . mysql_error());
				}
				if (mysql_num_rows($retval)==0) 
					{
						echo "no data availabel";
						exit;
					}
					else 
					{
						while($row = mysql_fetch_array($retval, MYSQL_ASSOC))  //Fetch data for each row from  Moodle DB
						{	
							echo $usrname = $row['userName'];
							echo $ufname = $row['firstName'];
							echo $cname = $row['CourseName'];
							echo $u_email = $row['Emailid'];
							echo $course_id = $row['CourseId'];
							$from='info@learntodrill.com';

							$emailSubject=getEmailSubject($course_id);
							$body= getMessage($course_id);
							$emailbody= str_replace('[UserFirstname]', $ufname, $body);
							$emailbodymsg= str_replace('[UserCoursename]', $cname, $emailbody);
							$emailbodymsg1= str_replace('[email]', $u_email, $emailbodymsg);
							$emailbodymsg2= str_replace('[username]', $usrname, $emailbodymsg1);
							$emailbodymsg3= str_replace('[CourseID]', $course_id, $emailbodymsg2);
							echo $message = $emailbodymsg3;

							sendmailtouser($u_email, $from, $emailSubject, $message,$course_id);

							
						}
							mysql_close($conn);
					}

					
//	}   
	        
			/*		else
					{
						echo("You are not authorise User");
					} 
	*/
	 
					function getMessage($courseid)
					{	
					   if($courseid=='25')  //CourseName:-IADC Workover/Completions/Wireline Supervisory Level
					   {
						 $emailBody= getString('iPadMessage');	   
					   }else if($courseid=='152') //CourseName:-IADC Workover/Completions/Wireline/Coiled Tubing/Snubbing - Supervisory Level
					   {
						 $emailBody= getString('iPadMessage');	 
					   }
					   else if($courseid=='149') //CourseName:-IADC Workover/Completions Supervisory
					   {
						 $emailBody= getString('iPadMessage');	 
					   }
					   else if($courseid=='154')
					   {
						 $emailBody= getString('iPadMessage');	
					   }
					    else if($courseid=='157') //IADC Workover/Completions Fundamental
					   {
						 $emailBody= getString('iPadMessage');	
					   }
						else if($courseid=='155')
					   {
						 $emailBody= getString('iPadMessage');	
					   }	
						else if($courseid=='51') //CourseName:-IWCF Drilling Well Control Level 2 course
					   {
						 $emailBody= getString('FreeCourse_email');	 
					   }
						else if($courseid=='60') //CourseName:-Introduction to Well Servicing (Free)
					   {
						 $emailBody= getString('FreeCourse_email');	 
					   }
						else if($courseid=='245') //CourseName:-Awareness Course Certificate Fee
					   {
						 $emailBody= getString('aware_email_body');	 
					   }
					   else if($courseid=='157') //IADC Workover/Completions Fundamental
					   {
						 $emailBody= getString('aware_email_body');	 
					   }
						else if($courseid=='63') //CourseName:-Introduction To Drilling (Free)
					   {
						 $emailBody= getString('FreeCourse_email');	 
					   }	
					    else if($courseid=='53') //"Get Certificate” for: IADC Awareness
					   {
						 $emailBody= getString('aware_cert');	 
					   }
					   else if($courseid=='165') //Get Certificate” for: IADC intro & well servicing
					   {
						 $emailBody= getString('intro_cert');	 
					   }
					   else if($courseid=='52') //Get Certificate” for: IADC intro & well servicing
					   {
						 $emailBody= getString('intro_cert');	 
					   }
					   else{
						 $emailBody= getString('DefaultMessage'); //Default email to be send
					   }
					    return  $emailBody;
					}

					function getEmailSubject($course_id)  //This function will get Email subject for the courses from emailTemplate.php file
					{
					  if($course_id=='153')
					   {
						 $emailSubject= getString('ipadDeliveryAddress');	   
					   }else if($course_id=='152')
					   {
						 $emailSubject= getString('ipadDeliveryAddress');	 
					   }
					   else if($course_id=='149')
					   {
						 $emailSubject= getString('ipadDeliveryAddress');	
					   }
					  else if($course_id=='154')
					   {
						 $emailSubject= getString('ipadDeliveryAddress');	
					   }
					  else if($course_id=='155')
					   {
						 $emailSubject= getString('ipadDeliveryAddress');	
					   }
						else if($course_id=='245')
					   {
						 $emailSubject= getString('aware_subject');	
					   }
						else if($courseid=='157') //IADC Workover/Completions Fundamental
					   {
						 $emailBody= getString('aware_subject');	 
					   }	
					    else if($courseid=='53') //IADC Awareness
					   {
						 $emailBody= getString('aware_cert');	 
					   }
					   else if($courseid=='52') //IADC Intro
					   {
						 $emailBody= getString('intro_cert');	 
					   }
					   else if($courseid=='165') //IADC Well servicing
					   {
						 $emailBody= getString('intro_cert');	 
					   }
					   else{
						 $emailSubject= getString('WelcomeSubject');
					   }  
					   return  $emailSubject;
					}
					
					
				function sendmailtouser($mailaddress, $from, $subject, $message,$course_id) {
				$eol = PHP_EOL;	
				//$separator = md5(time());

				$header = "From:".$from. $eol;
				$header .= "MIME-Version: 1.0". $eol;
				//$header .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
				//$header .= "Content-Transfer-Encoding: 7bit" . $eol;
				//$header .= "This is a MIME encoded message." . $eol . $eol;
				$header .= "Return-Path:".$from. $eol;
				$header .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
				
							if($course_id =='51') 
							{
								$header .= "Bcc:q1oz7pa0@robot.zapier.com"; //Email to student in IWCF course enroll course 
								
							}
							if($course_id =='156'||$course_id =='155'||$course_id =='154'||$course_id =='153'||$course_id =='152'||$course_id =='149'||$course_id =='147'||$course_id =='63'||$course_id =='52'||$course_id =='53'||$course_id =='164'||$course_id =='145'||$course_id =='55'||$course_id =='56'||$course_id =='54'||$course_id =='62'||$course_id =='59'||$course_id =='58'||$course_id =='57'||$course_id =='171'||$course_id =='170'||$course_id =='172'||$course_id =='176'||$course_id =='174'||$course_id =='165')
							{
								$header .= "Bcc:q1oz7pa0@robot.zapier.com"; //Default to all other courses
							}
				//$header .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
				//$header .= $msg1. $eol . $eol;
				 if (mail($mailaddress, $subject, $message,  $header, '-f'.$from))
				 {}
				 else 
				 { 
				   echo "";
				   exit; 
				 }
}
?>
