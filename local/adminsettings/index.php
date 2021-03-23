<?php

require('../../config.php');
require_login();
$PAGE->set_context(context_system::instance());
global $DB, $USER;
$pagetitle = 'Course List';
$PAGE->set_title($pagetitle);
echo $OUTPUT->header();
?>

<div class="bundle_table">
	<div class="table_heading"> Course  List </div>
	<table id="usetTable" class="table" border="1">
		<thead> 
			<tr> 
				<th>Sl. No.</th>
				<th>Course</th>                		
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$count=0;
			$query ="select * from  {course} where visible=1 AND category!=0";
			$courseinfo = $DB->get_records_sql($query);
			if($courseinfo){ 
				foreach ($courseinfo as $courseRow) {
					$count++;
					echo'<tr>
						<td>'.$count.'</td>
						<td>'.$courseRow->fullname.'</td>
						<td><a href="'.$CFG->wwwroot.'/local/course_discount/discount.php?id='.$courseRow->id.'" class="btn btn-success btn-sm" name="addcart">Add Discount</a></td>
					</tr>';
				}
			}
			?>
		</tbody>
	</table>
</div>


<style>

.bundle_table table {
    width: 100%;
}
.table_heading {
    color: #fff;
    font-size: 30px;
    text-align: center;
    margin: 10px 0;
    background: #3376d2;
}
.bundle_table{
	margin-top:50px;
}
.bundle_table table tr th,.bundle_table table tr td{
    padding:6px 8px; 
}
.bundle_table thead{
	background:#79aaf1;
}
.bundle_table table{
	border:1px solid #ddd;
}


</style> 


<?php  echo $OUTPUT->footer(); ?>