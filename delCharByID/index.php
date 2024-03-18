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

// echo json_encode($CharID);
$delCharSQL = "DELETE FROM `char_name` WHERE id=?";
$stmt = $pdo ->prepare($delCharSQL);
$stmt -> execute([$CharID]);

$getCharsAfterDelSQL = "SELECT * FROM `char_name`";
$stmt = $pdo->query($getCharsAfterDelSQL);

$i = -1;
while($row = $stmt->fetch())
{
    $i++;
    $arr[$i] = $row;
}
echo  json_encode ($arr);