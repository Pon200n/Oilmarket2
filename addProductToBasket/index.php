<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];

$productId = intval($_GET['productId']);
$userId = intval($_GET['userId']);

// *mysqli подключение
// $mysql = new mysqli("localHost", "root", "", "Oil_example");
// $mysql->query("SET NAMES 'utf8'");

// * PDO:: подключение
try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

// * получить basketID по UserID   mysqli
// $getBasketIdFromUser = "SELECT `id` FROM `basket` WHERE user_id='$userId'";
// $resgetBasketIdFromUser= $mysql->query($getBasketIdFromUser);
// $rowBasketId = mysqli_fetch_array($resgetBasketIdFromUser, MYSQLI_ASSOC);
// $BasketId=intval($rowBasketId['id']);

// * получить basketID по UserID   PDO::
$getBasketIdFromUser_SQL = "SELECT `id` FROM `basket` WHERE user_id=?";
$stmt = $pdo -> prepare($getBasketIdFromUser_SQL);
$stmt -> execute(array($userId));
$basketIDrow = $stmt -> fetch(PDO::FETCH_ASSOC);
$BasketId = $basketIDrow['id'];

// * получаем product_count товара в корзине пользователя по $productId и $BasketId mysqli
// $isProduct = "SELECT basket_product.product_count FROM `basket_product` WHERE product_id=$productId AND basket_id=$BasketId";
// $isProd = $mysql->query($isProduct);
// $prod = mysqli_fetch_array($isProd, MYSQLI_ASSOC);

// * получаем product_count товара в корзине пользователя по $productId и $BasketId PDO::
$isProduct_SQL = "SELECT basket_product.product_count FROM `basket_product` WHERE product_id=? AND basket_id=?";
$stmt2 = $pdo -> prepare($isProduct_SQL);
$stmt2 -> execute([$productId,$BasketId]);
$prod = $stmt2 -> fetch(PDO::FETCH_ASSOC);


if($prod == NULL){
    // *добавляем товар в корзину пользователя mysqli
    // $addProductInBasket = "INSERT INTO `basket_product` (`id`, `product_id`,`basket_id`) VALUES (NULL, '$productId', '$BasketId')";
    // $mysql->query($addProductInBasket);
    
    // *добавляем товар в корзину пользователя PDO:: (работает)
    $addProductInBasket_SQL = "INSERT INTO `basket_product` (`id`, `product_id`,`basket_id`) VALUES (NULL, ?, ?)";
    $stmt3 = $pdo -> prepare($addProductInBasket_SQL);
    $stmt3 -> execute([$productId,$BasketId]);

}


    
 else {
   
    $isItProduct = intval($prod['product_count']);
    $incrCountProd = ++$isItProduct;
     // * увеличиваем на еденицу product_count товара если он есть  в корзине пользователя mysqli
    // $setProductCount = "UPDATE `basket_product` SET `product_count`=$incrCountProd WHERE product_id = $productId AND basket_id = $BasketId";
    // $mysql->query($setProductCount);
    // * увеличиваем на еденицу product_count товара если он есть  в корзине пользователя PDO::
    $setProductCount_SQL = "UPDATE `basket_product` SET `product_count`=$incrCountProd WHERE product_id = ? AND basket_id = ?";
    $stmt4 =$pdo -> prepare($setProductCount_SQL);
    $stmt4 -> execute([$productId, $BasketId]);

    

}


// * получаем массив товаров в корзине пользователя после обновления mysqli
// $toFront = "SELECT products.*,basket_product.product_count  FROM `basket` LEFT JOIN `basket_product` ON `basket_product`.`basket_id`= basket.id LEFT JOIN `products` ON `basket_product`.`product_id`= products.id WHERE user_id = '$userId'";
// $result = $mysql->query($toFront);
// $i = -1;
//  while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
//  {
//      $i++;

//      $arr[$i] = $row;

//  }
//  echo  json_encode ($arr);

// * получаем массив товаров в корзине пользователя после обновления pDO::
$toFront_SQL = "SELECT products.*,basket_product.product_count  FROM `basket` LEFT JOIN `basket_product` ON `basket_product`.`basket_id`= basket.id LEFT JOIN `products` ON `basket_product`.`product_id`= products.id WHERE user_id = ?";
$stmt5 = $pdo -> prepare($toFront_SQL);
$stmt5 -> execute([$userId]);
$arr = $stmt5 -> fetchAll(PDO::FETCH_ASSOC);
echo  json_encode ($arr);

