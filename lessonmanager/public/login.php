<?php
/**
 * Страница авторизации пользователей
 * 
 * Обрабатывает форму входа и перенаправляет:
 * - Администраторов → в админ-панель
 * - Учеников → в раздел заданий
 */

session_start();
require_once '../includes/db.php';

// Обработка POST-запроса
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Поиск пользователя в БД
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Проверка пароля и авторизация
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Перенаправление по роли
        header("Location: ../" . ($user['role'] == 'admin' ? 'admin' : 'student') . "/index.php");
        exit();
    } else {
        $error = "Неверный логин или пароль!";
    }
}

// Форма входа
ob_start();
?>
<h2 class="centered">🔐 Вход в систему</h2>
<form method="post" class="form-card">
    <label for="username">👤 Логин:</label>
    <input type="text" name="username" id="username" required>

    <label for="password">🔒 Пароль:</label>
    <input type="password" name="password" id="password" required>

    <button type="submit">Войти</button>
    
    <p class="centered">
        <a href="forgot_password.php">🔁 Забыли пароль?</a>
    </p>

    <?php if (isset($error)): ?>
        <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
</form>

<?php
$content = ob_get_clean();
$title = 'Вход';
include '../includes/layout.php';