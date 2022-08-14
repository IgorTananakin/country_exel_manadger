<?php
//тестовая страница
//echo $_GET['country'];
include 'class/BD.php';//расширения класса PDO
$connection = new BD('mysql:host=localhost;dbname=country-excel-manager;charset=utf8', 'manadger', 'rZ2pD7jV8z');

$mas_country = $connection->country_hash();
foreach ($mas_country as $country => $mas) { 
    //foreach ($country as $mas ) {" 
        //echo "<p>Страна " . $mas["country"] .   "</p>";
   // }
    
}
//var_dump($mas_country);
?>



