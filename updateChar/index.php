<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

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
$CharID = $_GET['charID'];
$newCharName = $_GET['newCharName'];
$categoryID = $_GET['categoryID'];

$updateCharSQL = "UPDATE `char_name` SET `char_name`=:newCharName,`category_id`=:categoryID WHERE id = :CharID";
$stmt1 = $pdo -> prepare($updateCharSQL);
$stmt1 -> execute([
    ':CharID'=> $CharID,
    ':newCharName'=> $newCharName,
    ':categoryID'=> $categoryID,
]);

$getAllCharsSQl = "SELECT * FROM `char_name`;";
$stmt = $pdo ->query($getAllCharsSQl);
$Chars = $stmt -> fetchAll(PDO::FETCH_ASSOC);
echo json_encode($Chars);