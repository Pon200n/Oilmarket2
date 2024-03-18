<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require __DIR__ . '/../index.php';

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];

$result = [
    'chars'=>'',
    'values'=>'',
];
try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$getAllCharsSQl = "SELECT * FROM `char_name`;";
$stmt = $pdo ->query($getAllCharsSQl);
$Chars = $stmt -> fetchAll(PDO::FETCH_ASSOC);
$result['chars']=$Chars;

$getValuesSQL = "SELECT * FROM `values_of_char`;";
$stmt2 = $pdo->query($getValuesSQL);
$i = -1;
while($row = $stmt2->fetch())
{
    $i++;

    $values[$i] = $row;
}
$result['values']=$values;



echo json_encode($result);