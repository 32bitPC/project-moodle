<?php

require('../config.php');
global $CFG, $PAGE,$DB;
echo "TBA\n";
echo "TBA\n";
echo "TBA\n";
echo "TBA\n";
echo "TBA\n";
$DB->set_debug(true);
require_once("$CFG->libdir/formslib.php");
class sql_block extends moodleform {
    public function definition(){
        global $CFG;
    }
}
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('SQL Debug mode');
$PAGE->set_heading('SQL Debug mode');
$PAGE->set_url($CFG->wwwroot.'');
echo $OUTPUT->header();
$mform = new sql_block();
//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
    echo "TBA";
  //In this case you process validated data. $mform->get_data() returns data posted in form.
} else {
  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
  // or on the first display of the form.

  //Set default data (if any)
  $mform->set_data($toform);
  //displays the form
  $mform->display();
}
echo $OUTPUT->footer();
?>
