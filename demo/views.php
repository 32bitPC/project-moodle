<?php

require('../config.php');
global $CFG, $PAGE;
require_once("$CFG->libdir/formslib.php");

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Upload file');
$PAGE->set_heading('Upload file');
$PAGE->set_url($CFG->wwwroot.'');
echo $OUTPUT->header();
echo "Data is ".$_POST["data"]."\n";
if (($_POST['fileToUpload']['name']!="")){
    // Where the file is going to be stored
    $target_dir = "D:/xampp/htdocs/upload/";
    $file = $_POST['fileToUpload']['name'];
    $path = pathinfo($file);
    $filename = $path['filename'];
    $ext = $path['extension'];
    $temp_name = $_POST['fileToUpload']['tmp_name'];
    $path_filename_ext = $target_dir.$filename.".".$ext;
    echo "temp_name : ".$temp_name."\n";
    echo "path_filename_ext : ".$path_filename_ext."\n";
    // Check if file already exists
    if (file_exists($path_filename_ext)) {
        echo "Sorry, file already exists.";
    }else{
        move_uploaded_file($temp_name,$path_filename_ext);
        echo "Congratulations! File Uploaded Successfully.";
    }
}
echo "TBA\n";
echo $OUTPUT->footer();
?>
