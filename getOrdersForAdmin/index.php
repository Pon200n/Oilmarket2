<?php
header("Access-Control-Allow-Origin: *");
require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];

// $brand = $_GET["brand"];

try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$ordersForAdminSQL = "SELECT `order_list`.`id`,`order_list`.`order_user_time`,`order_list`.`order_server_time`,`order_list`.`delivery_place`,`users`.`nikName`,`users`.`lastName`,`users`.`firstName`,`users`.`patronymic`,`users`.`phone`,`users`.`eMail`,`order_status`.`order_status_description` FROM `order_list` LEFT JOIN `users` ON `order_list`.`user_id`=`users`.`id` LEFT JOIN `order_status` ON `order_list`.`order_status_id`=`order_status`.`id`;";
$stmt =$pdo ->query($ordersForAdminSQL);

$i = -1;
while($row = $stmt->fetch())
{
    $i++;
    $arr[$i] = $row;
}
echo json_encode($arr);    