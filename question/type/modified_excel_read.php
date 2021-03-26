<?php
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
$Totalrow = $Totalrow - 2;
for ($i = 0; $i <= $Totalrow; $i++) {
    print_r("item 1: " .$data[$i][0]. "\n");
    print_r("item 2: " .$data[$i][1]. "\n");
    print_r("item 3: " .$data[$i][2]. "\n");
    print_r("item 4: " .$data[$i][3]. "\n\n");
}
