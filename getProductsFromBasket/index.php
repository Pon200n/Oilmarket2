<?php
header("Access-Control-Allow-Origin: *");
require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$DBpassword =  $_ENV['DB_PASSWORD'];

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
// * получение всех товаров в козине пользователя mysqli
// $toFront = "SELECT products.*,basket_product.product_count  FROM `basket` LEFT JOIN `basket_product` ON `basket_product`.`basket_id`= basket.id LEFT JOIN `products` ON `basket_product`.`product_id`= products.id WHERE user_id = '$userId'";
// $result = $mysql->query($toFront);
// $i = -1;
//  while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
//  {
//      $i++;

//      $arr[$i] = $row;

//  }
//  echo  json_encode ($arr);

// * получение всех товаров в козине пользователя PDO::
$toFront_SQL = "SELECT products.*,basket_product.product_count  FROM `basket` LEFT JOIN `basket_product` ON `basket_product`.`basket_id`= basket.id LEFT JOIN `products` ON `basket_product`.`product_id`= products.id WHERE user_id = ?";
$stmt = $pdo -> prepare($toFront_SQL);
$stmt -> execute([$userId]);
$arr = $stmt -> fetchAll(PDO::FETCH_ASSOC);
echo  json_encode ($arr);

