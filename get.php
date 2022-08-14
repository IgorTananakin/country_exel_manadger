<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
 include 'class/BD.php';//расширения класса PDO
//  echo $chat_id;
$connection = new BD('mysql:host=localhost;dbname=country-excel-manager;charset=utf8', 'manadger', 'rZ2pD7jV8z');
$date = date('Y-m-d H:i:s');
$sunday = strtotime('sunday this week') + 86399;//конец текущего воскресенья
$sunday = date("Y-m-d H:i:s", $sunday);

$date_pref = date('Y-m-d H:i:s',strtotime("-7 days"));

$mas = $connection->all_data_for_hash($_GET['country'],$date,$sunday,$date_pref);

if (empty($mas)) {
    echo "Вы уже получили данные на этой неделе";
} else {

    include 'phpspreadsheet/get_static.php';  //Подключаем базу

    $udate_date = $connection->udate_date($_GET['country'],$date,$_GET['user_id'],$sunday,$date_pref);
   
    
}
?>