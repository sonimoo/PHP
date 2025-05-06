<?php
/**
 * Настройки подключения к базе данных
 * @var string $host Сервер базы данных 
 * @var string $dbname Имя базы данных
 * @var string $user Имя пользователя MySQL
 * @var string $pass Пароль пользователя MySQL 
 */
$host = "localhost";
$dbname = "lesson_manager";
$user = "root";
$pass = ""; 

/**
 * Инициализация PDO соединения с базой данных
 * 
 * @var PDO $pdo Объект PDO для работы с базой данных
 * 
 * @throws PDOException Если соединение не может быть установлено
 */
try {
    // Создание нового подключения PDO
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4", 
        $user, 
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    // Альтернативный способ установки атрибутов
    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Завершение работы скрипта с сообщением об ошибке
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}