<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];

$userID = intval($_GET['userID']);
// echo json_encode($userID);

try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$getAllOrdersByUserID_SQl = "SELECT `order_list`.`id`, `order_list`.`user_id`,`order_list`.`order_user_time`,`order_list`.`order_server_time`,`order_list`.`order_status_id`,`order_status`.`order_status_description` FROM `order_list` LEFT JOIN `order_status` ON `order_list`.`order_status_id`=`order_status`.`id` WHERE `user_id`=?;";
$stmt = $pdo ->prepare($getAllOrdersByUserID_SQl);
$stmt -> execute([$userID]);
$orders = $stmt -> fetchAll(PDO::FETCH_ASSOC);
echo  json_encode ($orders);
