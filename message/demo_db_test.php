<?php 
require_once('../config.php');
function writeMsg() {
    $user = $DB->get_record('user', ['id' => '1']);
    return "Return a message two";
}

echo writeMsg();
?>