<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$DBpassword =  $_ENV['DB_PASSWORD'];


$categoryID = $_GET['category_id'];
// echo json_encode($categoryID);

try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $DBpassword);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$delCatSQL = "DELETE FROM `categories` WHERE id=?";
$stmt = $pdo ->prepare($delCatSQL);
$stmt -> execute([$categoryID]);

$getCategorySQL = "SELECT * FROM `categories`";
$stmt = $pdo->query($getCategorySQL);

$i = -1;
while($row = $stmt->fetch())
{
    $i++;

    $arr[$i] = $row;

}
echo  json_encode ($arr);