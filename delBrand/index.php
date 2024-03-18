<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$DBpassword =  $_ENV['DB_PASSWORD'];


$brandID = $_GET['brand_id'];
// echo json_encode($brandID);

try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $DBpassword);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$delBrandSQL = "DELETE FROM `brands` WHERE id=?";
$stmt = $pdo ->prepare($delBrandSQL);
$stmt -> execute([$brandID]);

$getBrandSQL = "SELECT * FROM `brands`";
$stmt = $pdo->query($getBrandSQL);

$i = -1;
while($row = $stmt->fetch())
{
    $i++;

    $arr[$i] = $row;

}
echo  json_encode ($arr);