<?php
header("Access-Control-Allow-Origin: *");

// require __DIR__ . '/../vendor/autoload.php';
// Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

require __DIR__ . '/../index.php';


$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$DBpassword =  $_ENV['DB_PASSWORD'];

// * PDO:: подключение
try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $DBpassword);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$getCategorySQL = "SELECT * FROM `categories`";
$stmt = $pdo->query($getCategorySQL);

$i = -1;
while($row = $stmt->fetch())
{
    $i++;

    $arr[$i] = $row;

}

$getBrandsOfCatAndIDs = "SELECT products.category, products.manufact, brands.brand_name FROM `products` LEFT JOIN categories ON products.category = categories.id LEFT JOIN brands ON products.manufact=brands.id GROUP BY products.category, products.manufact ORDER BY `products`.`category` ASC;";
$stmt2 = $pdo->query($getBrandsOfCatAndIDs);

$i = -1;
while($row2 = $stmt2->fetch())
{
    $i++;
    $brandsOfcat[$i] = $row2;
}
echo  json_encode ([
    'categories'=>$arr,
    'brands'=>$brandsOfcat,
]);