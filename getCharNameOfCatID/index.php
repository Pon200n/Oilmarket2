<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];


$categoryID = intval($_GET['categoryID']);

$result = [
    'chars'=>'',
    'all_chars'=>'',
    'values'=>'',
];
try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$getCharsSQL = "SELECT `char_name`.`id` ,`char_name`.`char_name` FROM `char_name` WHERE `category_id`=?";
$stmt = $pdo ->prepare($getCharsSQL);
$stmt -> execute([$categoryID]);
$CharsNames = $stmt->fetchAll(PDO::FETCH_ASSOC);
$result['chars'] = $CharsNames;

$getAllCharsSQL = "SELECT * FROM `char_name`;";
$stmt = $pdo->query($getAllCharsSQL);
$i = -1;
while($row = $stmt->fetch())
{
    $i++;

    $ALLchars[$i] = $row;
}
$result['all_chars']=$ALLchars;


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