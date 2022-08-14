
<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$result_sql = $connection->data_for_menedger($_GET['country'],$date);
// var_dump($result_sql[0]['country']);
 $country = $result_sql[0]['country'];//получить страну без хеша массив в переменную

// var_dump($country);
 require 'vendor/autoload.php';

 use PhpOffice\PhpSpreadsheet\Spreadsheet;
 use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

 $fileName = "test.xls";

if (!empty($result_sql)) {
    //make a new spreadsheet object
    $spreadsheet = new Spreadsheet();
    //get current active sheet (first sheet)
    $sheet = $spreadsheet->getActiveSheet();
    //set the value of cell a1 to "Hello World!"
    // $sheet->setCellValue('A1', 'Hello World !');
    $sheet->setCellValue('A1', 'ID партнёра');
    $sheet->setCellValue('B1', 'Имя пользователя');
    $sheet->setCellValue('C1', 'Email');
    $sheet->setCellValue('D1', 'Страна');
    $sheet->setCellValue('E1', 'Сайт');
    $sheet->setCellValue('F1', 'Дата регистрации');
    $sheet->setCellValue('G1', 'Статус ');
    $sheet->setCellValue('H1', 'Партнёрская группа ');
    $sheet->setCellValue('I1', 'Комиссионная группа ');
    $sheet->setCellValue('J1', 'Привлечен партнером ');
    $sheet->setCellValue('K1', 'Дата окончания комиссионной группы ');
    $sheet->setCellValue('L1', 'Игроков ');

    //$$sheet->getColumnDimensionByColumn('A')->setAutoSize(false);
    $sheet->getColumnDimension('A')->setWidth(12);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(40);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(65);
    $sheet->getColumnDimension('F')->setWidth(17);
    $sheet->getColumnDimension('G')->setWidth(10);
    $sheet->getColumnDimension('H')->setWidth(25);
    $sheet->getColumnDimension('I')->setWidth(20);
    $sheet->getColumnDimension('J')->setWidth(20);
    $sheet->getColumnDimension('K')->setWidth(32);
    $sheet->getColumnDimension('L')->setWidth(10);
    $i = 1;
    foreach ($result_sql as $line ) {
        $i++;
        foreach ($line as $key => $value) {
            
            $sheet->setCellValue('A' . $i, $line["id_partner"]);
            $sheet->setCellValue('B' . $i, $line["users"]);
            $sheet->setCellValue('C' . $i, $line["email"]);
            $sheet->setCellValue('D' . $i, $line["country"]);
            $sheet->setCellValue('E' . $i, $line["website"]);
            $sheet->setCellValue('F' . $i, $line["date_registration"]);
            $sheet->setCellValue('G' . $i, $line["status"]);
            $sheet->setCellValue('H' . $i, $line["partner_group"]);
            $sheet->setCellValue('I' . $i, $line["commission_group"]);
            $sheet->setCellValue('J' . $i, $line["attracted_by_partner"]);
            $sheet->setCellValue('K' . $i, $line["commission_group_end_date"]);
            $sheet->setCellValue('L' . $i, $line["players"]);
            
        }
    }
    //make an xlsx writer object using above spreadsheet
    $writer = new Xlsx($spreadsheet);
    //write the file in current directory
    $writer->save('партнёры_по_странам_' . $country .  date('Y-m-d') . '.xlsx');
    //redirect to the file
    echo "<meta http-equiv='refresh' content='0;url=партнёры_по_странам_" . $country .  date('Y-m-d') . ".xlsx'/> ";
    

    
}

 //header("Location: http://localhost/wesport/index.php");
 //exit( );
 echo "Таблица успешно скачена";
  ?>

