<?php
header("Access-Control-Allow-Origin: *");
require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$DBpassword =  $_ENV['DB_PASSWORD'];

// *mysqli
// $mysql = new mysqli("localHost", "root", "", "Oil_example");
// $mysql->query("SET NAMES 'utf8'");

// * PDO
try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $DBpassword);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

// echo json_encode('del');
// $postData = file_get_contents('php://input');
// $data = json_decode($postData, true);
// var_dump ($postData);
// echo json_encode($_POST);

$id = ($_GET['id']);
// * получение img name по id mysqli
// $getImgName = "SELECT img FROM `products` WHERE id='$id'";
// $result1 = $mysql->query($getImgName);
// $row=mysqli_fetch_array($result1,MYSQLI_ASSOC);
// var_dump ($row);
// $img = $row['img'];

// *получение img name по id PDO::
$getImgName_SQL = "SELECT img FROM `products` WHERE id=?";
$stmt9 = $pdo -> prepare($getImgName_SQL);
$stmt9 -> execute([$id]);
$imgST = $stmt9 -> fetch(PDO::FETCH_ASSOC);
$img = $imgST['img']; 


// $result1 = '1407606oil1.jpg';
unlink('../static/'.$img);

// * удаление товара из БД mysqli
// $delProductById = "DELETE FROM products WHERE `products`.`id` = '$id'";
// $result = $mysql->query($delProductById);

// * удаление товара из БД PDO::
$delProductById_SQL = "DELETE FROM products WHERE `products`.`id` = ?";
$stmt2 = $pdo -> prepare($delProductById_SQL);
$stmt2 -> execute([$id]);



// *mysqli очистка подключения mysqli
// $result1->free();
// $result->free();

