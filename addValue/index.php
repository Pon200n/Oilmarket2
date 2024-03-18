<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require __DIR__ . '/../index.php';

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];

$charID = $_GET['charID'];
$valueName = $_GET['valueName'];
// echo json_encode($valueName);
try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}


$addValueSQL = "INSERT INTO `values_of_char`(`value_name`, `char_id`) VALUES (:valueName,:charID)";
$stmt1 = $pdo ->prepare($addValueSQL);
$stmt1 -> execute([
    ':charID'=>$charID,
    ':valueName'=>$valueName,
]);
$getAllCharsSQl = "SELECT * FROM `values_of_char`;";
$stmt2 = $pdo ->query($getAllCharsSQl);
$Chars = $stmt2 -> fetchAll(PDO::FETCH_ASSOC);
echo json_encode($Chars);
// INSERT INTO `values_of_char`(`value_name`, `char_id`) VALUES (',')