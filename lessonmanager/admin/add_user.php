<?php
/**
 * Файл добавления пользователей (admin/add_user.php)
 *
 * Этот скрипт позволяет администратору добавлять новых пользователей в систему.
 * Обрабатывает форму регистрации, валидирует данные и сохраняет их в БД.
 */

require_once '../includes/auth.php';
require_once '../includes/db.php';

// Проверка аутентификации пользователя с ролью 'admin'
checkAuth('admin');

// Инициализация переменных для сообщений
$error = '';
$success = '';

/**
 * Обрабатывает POST-запрос при отправке формы добавления пользователя
 *
 * Валидирует входные данные:
 * - Проверяет заполненность обязательных полей
 * - Проверяет формат логина (3-20 символов, буквы/цифры/подчёркивания)
 * - Проверяет допустимость роли (admin/student)
 * - Валидирует email
 * - Хэширует пароль перед сохранением
 *
 * В случае успеха добавляет пользователя в базу данных
 * В случае ошибки устанавливает соответствующее сообщение
 */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Получение и очистка входных данных
    $full_name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $email = trim($_POST['email'] ?? '');

    // Валидация данных
    if (empty($full_name) || empty($username) || empty($password) || empty($role) || empty($email)) {
        $error = "Пожалуйста, заполните все поля.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $error = "Логин должен содержать только буквы, цифры и подчёркивания (3–20 символов).";
    } elseif (!in_array($role, ['admin', 'student'])) {
        $error = "Недопустимая роль пользователя.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Неверный формат email.";
    } else {
        // Хэширование пароля перед сохранением
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        /**
         * Вставка нового пользователя в таблицу users
         *
         * @throws PDOException В случае ошибки SQL-запроса
         */
        try {
            $stmt = $pdo->prepare("
                INSERT INTO users (full_name, username, password, role, email)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                htmlspecialchars($full_name),
                htmlspecialchars($username),
                $hashedPassword,
                $role,
                htmlspecialchars($email)
            ]);
            $success = "Пользователь успешно добавлен.";
        } catch (PDOException $e) {
            // Обработка ошибки дублирования уникального поля
            $error = ($e->getCode() == 23000)
                ? "Пользователь с таким логином уже существует."
                : "Ошибка: " . $e->getMessage();
        }
    }
}