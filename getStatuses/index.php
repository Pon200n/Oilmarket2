<?php
header("Access-Control-Allow-Origin: *");

// require __DIR__ . '/../vendor/autoload.php';
// Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

require __DIR__ . '/../index.php';

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];


try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}
$getStat = "SELECT * FROM `order_status`";
$stmt= $pdo->query($getStat);

$i = -1;
while($row = $stmt->fetch())
{
    $i++;
    $arr[$i] = $row;
}
echo json_encode($arr);