<?php
// Генерация хеша пароля для администратора
$password = 'admin'; // Это пароль администратора
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo $hashed_password; // Выведет хеш пароля, который нужно вставить в базу данных
?>
