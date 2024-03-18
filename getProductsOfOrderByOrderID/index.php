<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

// require __DIR__ . '/../vendor/autoload.php';
// Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();
require __DIR__ . '/../index.php';
$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];


$orderID = intval($_GET['order_id']);
// $date = $_GET['date'];
// echo json_encode($orderID);

try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$getOrderProducts = "SELECT * FROM `order_product`LEFT JOIN `products` ON `order_product`.`order_product_id`=`products`.`id` WHERE `order_product`.`order_id`=?";
$stmt = $pdo ->prepare($getOrderProducts);
$stmt -> execute(array($orderID));
$orderProd = $stmt -> fetchAll(PDO::FETCH_ASSOC);
echo  json_encode ($orderProd);
