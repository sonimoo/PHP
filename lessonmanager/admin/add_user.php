<?php
/**
 * Страница создания нового пользователя
 * 
 * - Проверяет права доступа через сессию
 * - Валидирует и фильтрует входные данные
 * - Добавляет нового пользователя в базу данных
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/auth.php';
require_once '../includes/db.php';

checkAuth('admin');

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $email = trim($_POST['email'] ?? '');

    // Валидация формы
    if (empty($full_name) || empty($username) || empty($password) || empty($role) || empty($email)) {
        $error = "❗ Пожалуйста, заполните все поля.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $error = "❗ Логин должен содержать только буквы, цифры и подчёркивания (3-20 символов).";
    } elseif (!in_array($role, ['admin', 'student'])) {
        $error = "❗ Недопустимая роль пользователя.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❗ Неверный формат email.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (full_name, username, password, role, email) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                htmlspecialchars($full_name),
                htmlspecialchars($username),
                $hashedPassword,
                $role,
                htmlspecialchars($email)
            ]);
            $success = "✅ Пользователь успешно добавлен.";
        } catch (PDOException $e) {
            $error = $e->getCode() == 23000
                ? "❗ Пользователь с таким логином уже существует."
                : "❗ Ошибка: " . $e->getMessage();
        }
    }
}

// Шаблон вывода
ob_start();
?>

<h2>Создание пользователя</h2>

<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="post">
    <label>ФИО:<br>
        <input type="text" name="full_name" required>
    </label><br><br>

    <label>Логин:<br>
        <input type="text" name="username" required>
    </label><br><br>

    <label>Пароль:<br>
        <input type="password" name="password" required>
    </label><br><br>

    <label>Email:<br>
        <input type="email" name="email" required>
    </label><br><br>

    <label>Роль:<br>
        <select name="role" required>
            <option value="">-- Выберите роль --</option>
            <option value="admin">Администратор</option>
            <option value="student">Ученик</option>
        </select>
    </label><br><br>

    <button type="submit">Создать пользователя</button>
</form>

<p><a href="index.php">⬅ Назад в панель администратора</a></p>

<?php
$content = ob_get_clean();
$title = 'Создание пользователя';
include '../includes/layout.php';
?>
