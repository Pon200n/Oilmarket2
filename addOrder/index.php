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
$user_date = $_GET['date'];
$delivery_place = $_GET['delivery_place'];
// echo json_encode($delivery_place);

try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}
date_default_timezone_set('Asia/Krasnoyarsk');
$order_server_time = date("d.m.Y, H:i:s"); 

$createOrderSQL = "INSERT INTO `order_list` SET `user_id`=?, `order_user_time`=?, `order_server_time`=?, `delivery_place`=?";
$stmt0 = $pdo ->prepare($createOrderSQL);
$stmt0 -> execute(array($userID,$user_date,$order_server_time,$delivery_place ));


$getOrderListID_SQL="SELECT `id` FROM `order_list` WHERE `user_id`=? AND `order_user_time`=?";
$stmt1 = $pdo ->prepare($getOrderListID_SQL);
$stmt1 -> execute(array($userID,$user_date));
$order_listID = $stmt1 -> fetch(PDO::FETCH_ASSOC);
$orderID = $order_listID['id'];
// echo json_encode($order_listID['id']);

$getBasketIdFromUser_SQL = "SELECT `id` FROM `basket` WHERE user_id=?";
$stmt = $pdo -> prepare($getBasketIdFromUser_SQL);
$stmt -> execute(array($userID));
$basketIDrow = $stmt -> fetch(PDO::FETCH_ASSOC);
$BasketId = $basketIDrow['id'];
// echo json_encode($BasketId);

$addToOrderFromBasketProductsSQL = "INSERT INTO `order_product`( `order_product_id`,`order_id`, `product_count`) SELECT `product_id`, :orderID , `product_count` FROM `basket_product` WHERE `basket_product`.`basket_id`=:basketID";
$stmt2 = $pdo -> prepare($addToOrderFromBasketProductsSQL);
$stmt2 -> execute([
    ':orderID' => $orderID, 
    ':basketID' => $BasketId, 
]);

