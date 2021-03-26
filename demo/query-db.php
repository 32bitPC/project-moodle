<?php
// This file is part of Moodle - http://moodle.org/
/**
 * Page to edit the question bank
 *
 * @package    moodlecore
 * @subpackage questionbank
 * @copyright  1999 onwards Martin Dougiamas {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../config.php');
function db_conn() {
    require_once 'PHPLibrary/PHPExcel/Classes/PHPExcel.php';
    $file = 'questionlist.xlsx';
    $objFile = PHPExcel_IOFactory::identify($file);
    $objData = PHPExcel_IOFactory::createReader($objFile);
    $objData->setReadDataOnly(true);
    $objPHPExcel = $objData->load($file);
    $sheet = $objPHPExcel->setActiveSheetIndex(0);
    $Totalrow = $sheet->getHighestRow();
    $LastColumn = $sheet->getHighestColumn();
    $TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);
    $data = [];
    for ($i = 2; $i <= $Totalrow; $i++) {
        for ($j = 0; $j < $TotalCol; $j++) {
            $data[$i - 2][$j] = $sheet->getCellByColumnAndRow($j, $i)->getValue();;
        }
    }
    global $DB;
    for ($i = 0; $i <= $Totalrow; $i++) {
        $rec->username = $data[$i][1];
        $rec->id = $DB->insert_record('user',$rec);
        $rec->username = $data[$i][2];
        $rec->id = $DB->insert_record('user',$rec);
    }
    
    // You can access the database via the $DB method calls here.
}
db_conn();