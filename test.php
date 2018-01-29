<?php
  $filename = 'test';
    require_once ('PHPExcel.php');
    $objPHPExcel = new PHPExcel();
    // Set properties
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Hello')
                ->setCellValue('B1', 'world!');
                
    $objPHPExcel->getActiveSheet()->setTitle('Report'); //give title to sheet
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;Filename=$filename.xlsx");
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
    
?>