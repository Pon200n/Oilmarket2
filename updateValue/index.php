<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require __DIR__ . '/../index.php';

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];

// $result = [
//     'chars'=>'',
//     'values'=>'',
// ];
$valueID = $_GET['valueID'];
$newValueName = $_GET['newValueName'];
$char_id = $_GET['char_id'];

// echo json_encode($valueID);
try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$updateValueSQL = "UPDATE `values_of_char` SET `value_name`=:newValueName WHERE `values_of_char`.`id`=:valueID";
$stmt1 = $pdo -> prepare($updateValueSQL);
$stmt1 -> execute([
    ':valueID'=>$valueID,
    ':newValueName'=>$newValueName,
    // ':char_id'=>$char_id,
]);

$getAllValuesSQL = "SELECT * from `values_of_char`";
$stmt2 = $pdo ->query($getAllValuesSQL);
$Values = $stmt2 -> fetchAll(PDO::FETCH_ASSOC);
echo json_encode($Values);