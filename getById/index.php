<?php
header("Access-Control-Allow-Origin: *");
require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$DBpassword =  $_ENV['DB_PASSWORD'];

// *mysqli подключение
// $mysql = new mysqli("localHost", "root", "", "Oil_example");
// $mysql->query("SET NAMES 'utf8'");

// * PDO:: подключение
try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $DBpassword);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$id = intval($_GET['id']);

$result = [
    'product'=>'',
    'chars'=>'',
];

// *получение товара по ID mysqli
// $getProductById = "SELECT * FROM `products` WHERE id='$id'";
// $result = $mysql->query($getProductById);
// $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
// echo  json_encode ($row);

// *получение товара по ID PDO::
$getProductById_SQL = "SELECT `products`.*,`categories`.`category_name`,`brands`.`brand_name` FROM `products` LEFT JOIN `categories` ON `products`.`category` = categories.id LEFT JOIN `brands` ON `products`.`manufact` = brands.id WHERE `products`.id = ?";
$stmt = $pdo -> prepare($getProductById_SQL);
$stmt -> execute([$id]);
$product = $stmt -> fetch(PDO::FETCH_ASSOC);
$result['product']=$product;
// echo  json_encode ($product);

$getCharsValuesSQL = "SELECT `product_char_values`.*,`values_of_char`.`value_name`,`char_name`.`char_name` FROM `product_char_values` LEFT JOIN `values_of_char` ON `product_char_values`.`value_id`=`values_of_char`.`id` LEFT JOIN `char_name` ON `product_char_values`.`char_id`=`char_name`.`id` WHERE `product_char_values`.`product_id`=?";
$stmt2 = $pdo -> prepare($getCharsValuesSQL);
$stmt2 -> execute([$id]);
$chars = $stmt2 -> fetchAll(PDO::FETCH_ASSOC);
$result['chars']=$chars;
echo  json_encode ($result);

// *mysqli очистка подключения mysqli
// $result->free();
?>