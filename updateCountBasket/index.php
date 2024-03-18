<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$DBpassword =  $_ENV['DB_PASSWORD'];

$productID = intval($_GET["productID"]);
$userID = intval($_GET["userID"]);
$count = intval($_GET["count"]);

// *mysqli подключение
// $mysql = new mysqli("localHost", "root", "", "Oil_example");
// $mysql->query("SET NAMES 'utf8'");

// *PDO подключение
try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $DBpassword);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

// * получение basket ID по user ID mysqli
// $getBasketIdFromUser = "SELECT `id` FROM `basket` WHERE user_id='$userID'";
// $resgetBasketIdFromUser= $mysql->query($getBasketIdFromUser);
// $rowBasketId = mysqli_fetch_array($resgetBasketIdFromUser, MYSQLI_ASSOC);
// $BasketId=intval($rowBasketId['id']);

// * получение basket ID по user ID PDO::
$basketID_SQL = "SELECT `id` FROM `basket` WHERE user_id=?";
$stmt = $pdo ->prepare($basketID_SQL);
$stmt -> execute([$userID]);
$BasketIdSTM = $stmt -> fetch(PDO:: FETCH_ASSOC);
$BasketId = $BasketIdSTM['id'];


// * установка значения product_count товара mysqli
// $setProductCount = "UPDATE `basket_product` SET `product_count`=$count WHERE product_id = $productID AND basket_id = $BasketId";
// $mysql->query($setProductCount);

// * установка значения product_count товара PDO::
$setProductCount_SQL = "UPDATE `basket_product` SET `product_count`=? WHERE product_id = ? AND basket_id = ?";
$stmt2 = $pdo ->prepare($setProductCount_SQL);
$stmt2 ->execute([$count,$productID,$BasketId]);

// * получаем все продукты из корзины пользователя по его ID mysqli
// $toFront = "SELECT products.*,basket_product.product_count  FROM `basket` LEFT JOIN `basket_product` ON `basket_product`.`basket_id`= basket.id LEFT JOIN `products` ON `basket_product`.`product_id`= products.id WHERE user_id = '$userID'";
// $result = $mysql->query($toFront);
// $i = -1;
//  while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
//  {
//      $i++;

//      $arr[$i] = $row;

//  }
//  echo  json_encode ($arr);

// * получаем все продукты из корзины пользователя по его ID PDO::
$AllProductFromBasket_SQL = "SELECT products.*,basket_product.product_count  FROM `basket` LEFT JOIN `basket_product` ON `basket_product`.`basket_id`= basket.id LEFT JOIN `products` ON `basket_product`.`product_id`= products.id WHERE user_id = ?";
$stmt3 = $pdo -> prepare($AllProductFromBasket_SQL);
$stmt3 -> execute([$userID]);
$arr = $stmt3 -> fetchAll(PDO:: FETCH_ASSOC);
echo  json_encode ($arr);


