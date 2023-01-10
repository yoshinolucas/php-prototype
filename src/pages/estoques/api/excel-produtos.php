<?php
include_once $_SERVER['DOCUMENT_ROOT']."/includes/config.php";

include_once '../../../plugins/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

    $arquivo = "produtos.xlsx";
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $dados = "SELECT * FROM produtos;";
    $produtoras = mysql_fetchAll($dados);

    $sheet->setCellValue('A1', 'Código');
    $sheet->setCellValue('B1', 'Nome');
    $sheet->setCellValue('C1', 'Marca');
    $sheet->setCellValue('D1', 'Unidades');
    $sheet->setCellValue('E1', 'Criado em');
    $sheet->setCellValue('F1', 'Atualizado em');
    $sheet->setCellValue('H1', 'Ativo');

    $rowCount = 2;
    

    foreach($produtoras as $row){
        $sheet->getStyle('D'.$rowCount)->getNumberFormat()->
        setFormatCode('@'); 
        $sheet->getStyle('G'.$rowCount)->getNumberFormat()->
        setFormatCode('@');
        
        $sheet->setCellValue('A'. $rowCount, $row['id']);
        $sheet->setCellValue('B'. $rowCount, $row['name']);
        $sheet->setCellValue('C'. $rowCount, $row['marca']);
        $sheet->setCellValue('D'. $rowCount, "=\"".$row['unidades']."\"");
        $sheet->setCellValue('E'. $rowCount, $row['criado_em']);
        $sheet->setCellValue('F'. $rowCount, $row['atualizado_em']);
        $sheet->setCellValue('H'. $rowCount, $row['active']);
        $rowCount++;
    }

    foreach(range('A','H') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    
    
    ob_end_clean();
    header("Pragma: public");
    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    header('Content-Transfer-Encoding: Binary');
    header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
    header('Cache-Control: max-age=0');
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    die;
?>