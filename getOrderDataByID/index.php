<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];


$order = intval($_GET['orderID']);
// echo json_encode($order);

try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$result=[
    'order_data' =>'',
    'user_data' =>'',
];

$orderProductsSQL =  "SELECT `products`.`id`,`order_product`.`product_count`,`products`.`name`,`products`.`manufact`,`products`.`volume`,`products`.`img`,`products`.`price`,`categories`.`category_name` FROM `order_product` LEFT JOIN `products` ON `order_product`.`order_product_id`=`products`.`id` LEFT JOIN `categories` ON `products`.`category`=`categories`.`id` WHERE `order_product`.`order_id`=?;";
$stmt = $pdo -> prepare($orderProductsSQL);
$stmt -> execute([$order]);
$orderProductsUser = $stmt ->fetchAll(PDO::FETCH_ASSOC);
$result['order_data'] = $orderProductsUser;


$orderUserData = "SELECT `order_list`.`order_user_time`,`order_list`.`order_server_time`,`order_list`.`delivery_place`,`order_status`.`order_status_description`,`users`.`nikName`,`users`.`lastName`,`users`.`firstName`,`users`.`patronymic`,`users`.`phone`,`users`.`eMail` FROM `order_list` LEFT JOIN `order_status` ON `order_list`.`order_status_id`=`order_status`.`id` LEFT JOIN `users` ON `order_list`.`user_id`=`users`.`id` WHERE `order_list`.`id`=?;";
$stmt2 = $pdo -> prepare($orderUserData);
$stmt2 ->execute([$order]);
$orderUserData = $stmt2 ->fetch(PDO::FETCH_ASSOC);
$result['user_data'] = $orderUserData;
echo json_encode($result);
