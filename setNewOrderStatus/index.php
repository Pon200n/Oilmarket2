<?php
header("Access-Control-Allow-Origin: *");
require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$DBpassword =  $_ENV['DB_PASSWORD'];
$newStatus = $_GET['newStatus'];
$orderID = $_GET['orderID'];
// echo json_encode($orderID);

try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $DBpassword);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$setNewStatusSQL = "UPDATE `order_list` SET `order_status_id`=:newStatus WHERE `order_list`.`id`=:orderID ;";
$stmt = $pdo ->prepare($setNewStatusSQL);
$stmt -> execute([
    ':newStatus'=>$newStatus,
    ':orderID'=>$orderID,
]);
$orderUserData = "SELECT `order_list`.`order_user_time`,`order_list`.`order_server_time`,`order_list`.`delivery_place`,`order_status`.`order_status_description`,`users`.`nikName`,`users`.`lastName`,`users`.`firstName`,`users`.`patronymic`,`users`.`phone`,`users`.`eMail` FROM `order_list` LEFT JOIN `order_status` ON `order_list`.`order_status_id`=`order_status`.`id` LEFT JOIN `users` ON `order_list`.`user_id`=`users`.`id` WHERE `order_list`.`id`=?;";
$stmt2 = $pdo -> prepare($orderUserData);
$stmt2 ->execute([$orderID]);
$orderUserData = $stmt2 ->fetch(PDO::FETCH_ASSOC);
echo json_encode($orderUserData);
