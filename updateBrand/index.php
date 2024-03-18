<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
$id = $data['id'];
$newName = $data['name'];
// echo json_encode($newName);


try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$updateBrandSQL = "UPDATE `brands` SET `brand_name`=:new_brand_name WHERE id=:id";
$stmt = $pdo -> prepare($updateBrandSQL);
$stmt -> execute([
    ':new_brand_name'=>$newName,
    ':id'=>$id,
]);

$getBrandSQL = "SELECT * FROM `brands`";
$stmt3 = $pdo->query($getBrandSQL);

$i = -1;
while($row = $stmt3->fetch())
{
    $i++;
    $arr[$i] = $row;
}
echo json_encode($arr);    
