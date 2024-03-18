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

$categoryID = intval($_GET['categoryID']);
// echo json_encode($categoryID);


try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$getBrandsOfCat = "SELECT DISTINCT `products`.`manufact`, `brands`.`brand_name` FROM `products` LEFT JOIN `brands` ON `products`.`manufact`=`brands`.`id` WHERE `products`.`category`=?;";
$stmt = $pdo ->prepare($getBrandsOfCat);
$stmt -> execute(array($categoryID));
$brands = $stmt -> fetchAll(PDO::FETCH_ASSOC);
echo  json_encode ($brands);

