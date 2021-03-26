<?php

require('../config.php');
global $CFG, $PAGE;
require_once("$CFG->libdir/formslib.php");

class second_block extends moodleform {
    public function definition(){
        global $CFG;
        $mform = $this->_form; 
        $mform->addElement('text', 'name', get_string('name')); // Add elements to your form
        $mform->setType('name', PARAM_NOTAGS);
    }
}
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Upload file');
$PAGE->set_heading('Upload file');
$PAGE->set_url($CFG->wwwroot.'');
echo $OUTPUT->header();
$mform = new second_block();

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
    $params['name'] = optional_param('name',null, PARAM_NOTAGS);
    echo "Value is ".hash_internal_user_password($params['name']);
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
