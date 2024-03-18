<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require __DIR__ . '/../index.php';

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];

$categoryID = $_GET['categoryID'];
$charName = $_GET['charName'];
try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}


$addCharSQL = "INSERT INTO `char_name`(`char_name`, `category_id`) VALUES (:charName,:categoryID)";
$stmt1 = $pdo ->prepare($addCharSQL);
$stmt1 -> execute([
    ':categoryID'=>$categoryID,
    ':charName'=>$charName,
]);
$getAllCharsSQl = "SELECT * FROM `char_name`;";
$stmt2 = $pdo ->query($getAllCharsSQl);
$Chars = $stmt2 -> fetchAll(PDO::FETCH_ASSOC);
echo json_encode($Chars);
// echo json_encode($categoryID);
// echo json_encode($charName);