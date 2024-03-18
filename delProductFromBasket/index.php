<?php
header("Access-Control-Allow-Origin: *");
require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$DBpassword =  $_ENV['DB_PASSWORD'];

// *
$productId = intval($_GET['productId']);
$userId = intval($_GET['userId']);


// *mysqli подключение
// $mysql = new mysqli("localHost", "root", "", "Oil_example");
// $mysql->query("SET NAMES 'utf8'");

// * PDO:: подключение
try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $DBpassword);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

// * удаление продукта из корзины пользователя mysqli
// $del="DELETE basket_product FROM basket_product LEFT JOIN basket ON basket.id = basket_product.basket_id WHERE user_id='$userId' AND product_id='$productId'";
// $result = $mysql->query($del);
// echo json_encode('товар удален');

// * удаление продукта из корзины пользователя PDO::
$del_SQL="DELETE basket_product FROM basket_product LEFT JOIN basket ON basket.id = basket_product.basket_id WHERE user_id=:userId AND product_id=:productId";
$stmt = $pdo ->prepare($del_SQL);
$stmt -> execute([':userId'=>$userId,':productId'=>$productId]);
echo json_encode('товар удален');

