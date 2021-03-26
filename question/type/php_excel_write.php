<?php
require "PHPLibrary/PHPExcel/Classes/PHPExcel.php";
$data = [
    ['37', 'Question 1', 'Why Valkyrie operation failed','1.0000000'],
    ['37', 'Question 2', 'How did WWII start','1.0000000'],
    ['37', 'Question 3', 'Name of the beach American invaded during WWII','1.0000000'],
    ['37', 'Question 4', 'What was the year WWII ended','1.0000000']
];
echo gettype($data);
//Khởi tạo đối tượng
$excel = new PHPExcel();
//Chọn trang cần ghi (là số từ 0->n)
$excel->setActiveSheetIndex(0);
//Tạo tiêu đề cho trang. (có thể không cần)
$excel->getActiveSheet()->setTitle('demo ghi dữ liệu');

//Xét chiều rộng cho từng, nếu muốn set height thì dùng setRowHeight()
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
//Xét in đậm cho khoảng cột
$excel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
//Tạo tiêu đề cho từng cột
//Vị trí có dạng như sau:
/**
 * |A1|B1|C1|..|n1|
 * |A2|B2|C2|..|n1|
 * |..|..|..|..|..|
 * |An|Bn|Cn|..|nn|
 */
$excel->getActiveSheet()->setCellValue('A1', 'category');
$excel->getActiveSheet()->setCellValue('B1', 'name');
$excel->getActiveSheet()->setCellValue('C1', 'questiontext');
$excel->getActiveSheet()->setCellValue('D1', 'defaultmark');
// thực hiện thêm dữ liệu vào từng ô bằng vòng lặp
// dòng bắt đầu = 2
$numRow = 2;
foreach ($data as $row) {
    $excel->getActiveSheet()->setCellValue('A' . $numRow, $row[0]);
    $excel->getActiveSheet()->setCellValue('B' . $numRow, $row[1]);
    $excel->getActiveSheet()->setCellValue('C' . $numRow, $row[2]);
    $excel->getActiveSheet()->setCellValue('D' . $numRow, $row[3]);
    $numRow++;
}
// Khởi tạo đối tượng PHPExcel_IOFactory để thực hiện ghi file
// ở đây mình lưu file dưới dạng excel2007
PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save('questionlist.xlsx');