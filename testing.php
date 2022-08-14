<?php
$date = strtotime('day this week'); 
$date1 = strtotime('sunday this week') + 86399;
var_dump($date);
var_dump($date1);
$test = date("Y-m-d H:i:s", $date1);
var_dump($test);

// include 'class/BD.php';//расширения класса PDO
// //  echo $chat_id;
// $connection = new BD('mysql:host=localhost;dbname=country-excel-manager;charset=utf8', 'manadger', 'rZ2pD7jV8z');

var_dump($_GET['country']);
var_dump($_GET['user_id']);


