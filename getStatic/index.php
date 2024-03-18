<?php
header("Access-Control-Allow-Origin: *");

// echo  json_encode("from server");

// // Путь к директории с изображениями
// $imageDir = '../static/';

// // Имя изображения, которое необходимо прочитать
// $imageName = 'oil1.jpg';

// // Полный путь к изображению
// $imagePath = $imageDir . $imageName;

// // Проверяем существование файла
// if (file_exists($imagePath)) {
//     // Открываем файл для чтения
//     $file = fopen($imagePath, 'rb');

//     // Устанавливаем заголовок Content-Type
//     header('Content-Type: image/jpeg');

//     // Выводим содержимое файла
//     fpassthru($file);

//     // Закрываем файл
//     fclose($file);
// } else {
//     // Выводим сообщение об ошибке, если файл не найден
//     echo 'Файл не найден';
// }

header('Content-Type: image/jpeg');
fpassthru(fopen("../static/oli6.jpg", 'rb'));



