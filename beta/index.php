<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * List content in content bank.
 *
 * @package    core_contentbank
 * @copyright  2020 Amaia Anabitarte <amaia@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../config.php');

require_login();
$context = get_context_instance (CONTEXT_SYSTEM);
$roles = get_user_roles($context, $USER->id, false);
$role = key($roles);
$roleid = $roles[$role]->roleid;
if(!$roleid){
    echo "error";
}
require_once("$CFG->libdir/formslib.php");

class fileBlock extends moodleform {
    //Add elements to form
    function definition() {
        global $CFG;
        $mform = $this->_form;
        
        $mform->addElement('filepicker', 'myfile', 'My File');
        
        $mform->addElement('submit', 'mysubmit', 'Upload'); 
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}
$PAGE->set_title('<h1>Upload file</h1>');
$PAGE->set_heading('<h1>Upload file</h1>');
// Get all contents managed by active plugins where the user has permission to render them.
echo $OUTPUT->header();
$mform = new fileBlock();
if ($mform->is_cancelled()) {
} else if ($fromform = $mform->get_data()) {
    $servername = "127.0.0.1";
    $username = "mrfbi";
    $password = "@810Chuongtran123";
    $dbname = "moodle";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $filename = $mform->get_new_filename('myfile');
    $mform->save_file('myfile', $CFG->dataroot . '/fileData/' . $filename);  
    require_once 'PHPLibrary/PHPExcel/Classes/PHPExcel.php';
    $file = $CFG->dataroot . '/fileData/'.$filename;
    $objFile = PHPExcel_IOFactory::identify($file);
    $objData = PHPExcel_IOFactory::createReader($objFile);
    $objData->setReadDataOnly(true);
    $objPHPExcel = $objData->load($file);    
    $sheet = $objPHPExcel->setActiveSheetIndex(0);   
    $Totalrow = $sheet->getHighestRow();
    $LastColumn = $sheet->getHighestColumn();$TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn); //Táº¡o máº£ng chá»©a dá»¯ liá»‡u
    $data = [];for ($i = 2; $i <= $Totalrow; $i++) {
        for ($j = 0; $j < $TotalCol; $j++) {
            $data[$i - 2][$j] = $sheet->getCellByColumnAndRow($j, $i)->getValue();;
        }
    }
    $Totalrow = $Totalrow - 2;
    global $DB;
    $user_auth = '';
    //update user table
    for ($i = 0; $i <= $Totalrow; $i++) {
        echo "<br>";
        $latest_user = 0;
        $case = 1;
        $username = $data[$i][0];
        $sql_user = "SELECT id,auth from mdl_user where username =
        '$username'";
        $result = $conn->query($sql_user);
        if($result->num_rows>0){
            while($row=$result->fetch_assoc()){
                $latest_user = $row['id'];  
                $user_auth = $row['auth'];
            }
            echo "(".$case.") User exists. <br>";
        }
        else{
            $case = 0;
            echo "(".$case.") User not exist. Creating a new user. <br>";
            $write_user->username = $data[$i][0];
        $username_petition_del = $data[$i][0];
        $pieces = explode(" ",$data[$i][0]);   
        $write_user->firstname = $pieces[0];
        $write_user->lastname = $pieces[1];
        $write_user->auth = "manual";
        $write_user->confirmed = 1;
        $write_user->mnethostid = 1;
        $write_user->country = "VN";
        $write_user->timezone = "Asia/Ho_Chi_Minh";
        $write_user->firstaccess = time();
        $write_user->lastaccess = time();
        $write_user->lastlogin = time();
        $write_user->currentlogin = time();
        $write_user->timemodified = time();
        $write_user->id = $DB->insert_record('user',$write_user);
        echo "User ".$username." has been added. <br>";
        $sql_user = "SELECT id,auth from mdl_user where username =
        '$username'";
        $result = $conn->query($sql_user);
        if($result->num_rows>0){
            while($row=$result->fetch_assoc()){
                $latest_user = $row['id'];
                $user_auth = $row['auth'];
            }
        }
        }
        echo 'auth now is '.$user_auth.'<br>';
        $latest_cohort = 0;
        $cohort = $data[$i][14];
        $sql_cohort = 
        "SELECT id,name FROM mdl_cohort where name='$cohort'";
        $result = $conn->query($sql_cohort);
        if($result->num_rows>0){
            echo "Cohort exists. <br>";
            while($row=$result->fetch_assoc()){
                $latest_cohort = $row['id'];
                $latest_cohort_name = $row['name'];
            }
        }
        else{
            echo "No cohort was found. Creating a new one. <br>";
            $write_cohort->name = $data[$i][14];
            $write_cohort->contextid = 37;
            $write_cohort->descriptionformat	 = 1;
            $write_cohort->visible = 1;
            $write_cohort->timecreated = 1616465363;
            $write_cohort->timemodified = 1616465363;
            $write_cohort->id = $DB->insert_record('cohort',$write_cohort);
            $result = $conn->query($sql_cohort);
            if($result->num_rows>0){
                while($row=$result->fetch_assoc()){
                    $latest_cohort = $row['id'];
                    $latest_cohort_name = $row['name'];                    
                }
            }
        }
        $queried_course = 0;
        $sql_check_user_cohort = "
        select id from mdl_cohort_members where 
        cohortid='$latest_cohort' and
        userid='$latest_user'";
        $result = $conn->query($sql_check_user_cohort);
        if($result->num_rows>0){
            echo "User ".$username." already exists in cohort.<br>";
        }
        else{
            echo "No user in cohort, start enrolling process.<br>";
            $write_cohort_members->cohortid = $latest_cohort;
            $write_cohort_members->userid = $latest_user;
            $write_cohort_members->timeadd = time();
            $write_cohort_members->id = $DB->insert_record('cohort_members',$write_cohort_members);
            
        }
        $course_name = $data[$i][15];
        // check if course exists
        $sql_course = "
        SELECT id from mdl_course where fullname = '$course_name'";
        $result= $conn->query($sql_course);
        if($result->num_rows>0){  // if course exists
            while($row=$result->fetch_assoc()){
                $queried_course = $row['id'];
            }
            $case = 1;
            echo "(".$case.") Found course. Starting enrolment process.<br>";
            $queried_enrol = 0;
            $sql_enrol = "
        SELECT id from mdl_enrol where courseid = '$queried_course'
        and enrol = '$user_auth'";
        echo $sql_enrol."<br>";
            $result = $conn->query($sql_enrol);
            if($result->num_rows>0){
                while($row=$result->fetch_assoc()){
                    $queried_enrol = $row['id'];
                }
            }            
            $sql_check_user_course = "
                select id from mdl_user_enrolments where
                enrolid = '$queried_enrol' and
                userid = '$latest_user'";   
            $result = $conn->query($sql_check_user_course);
            if($result->num_rows>0){
                echo "User ".$username." already enrolled in course ".$course_name.".<br>";
            }
            else{
                $write_enrolment->status = 0;
            $write_enrolment->enrolid = $queried_enrol;
            $write_enrolment->userid = $latest_user;
            $write_enrolment->timestart = time();
            $write_enrolment->timeend = 0;
            $write_enrolment->modifierid = 2;
            $write_enrolment->timecreated = time();
            $write_enrolment->timemodified = time();
            $write_enrolment->id = $DB->insert_record('user_enrolments',$write_enrolment);
            echo "User ".$username." has been enrolled with enrolment ID ".$queried_enrol." in course ".$course_name.".<br>";
            $context_course = CONTEXT_COURSE;
            $queried_context_id = 0;
            $sql_get_context_id = "
            select id from mdl_context
            where contextlevel = '$context_course'
            and instanceid = '$queried_course'";
            echo "query role is ".$sql_get_context_id."<br>";
            $result = $conn->query($sql_get_context_id);
            if($result->num_rows>0){
                while($row=$result->fetch_assoc()){
                    $queried_context_id = $row['id'];
                }
            }
            $default_role = 'student';
            $sql_select_role = "
            select id from mdl_role
            where shortname = '$default_role'";
            echo "query role is ".$sql_select_role."<br>";
            $selected_role = 0;
            $result = $conn->query($sql_select_role);
            if($result->num_rows>0){
                while($row=$result->fetch_assoc()){
                    $selected_role = $row['id'];
                }
            }
            $sql_role_assignments = "
            select * from mdl_role_assignments
            where roleid='$selected_role',
            and contextid='$queried_context_id',
            and userid='$latest_user'";
            $result = $conn->query($sql_role_assignments);
            if($result->num_rows>0){
                echo "User's role is in system already.<br>";
            }
            else{
               $write_role->roleid = $selected_role;
            $write_role->contextid = $queried_context_id;
            $write_role->userid = $latest_user;
            $write_role->timemodified = time();
            $write_role->modifiedid = 2;
            $write_role->itemid = 0 ;
            $write_role->sortorder = 0;
            $write_record->id = $DB->insert_record('role_assignments',$write_role);
            echo "User's role has been added to ".$user_auth.".<br>";
            }
            }
            
        }
        else{ //if course not exists
            $case = 0;
            echo "(".$case.") Course not found. Create one in moodle first!<br>";
            echo "User ".$username." will not be enrolled. <br>";         
        } 
    }
} else {
    $mform->set_data($toform);
    $mform->display();
}
echo $OUTPUT->footer();