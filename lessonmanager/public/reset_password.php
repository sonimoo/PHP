<?php
/**
 * Обработка сброса пароля по токену
 * 
 * 1. Проверяет валидность токена (30 минут)
 * 2. Обновляет пароль пользователя
 * 3. Удаляет использованный токен
 */

require_once '../includes/db.php';

// Получение токена из URL
$token = $_GET['token'] ?? '';
$error = $success = '';

// Обработка формы смены пароля
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    // Проверка токена в базе
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);
    $reset = $stmt->fetch();

    if ($reset && strtotime($reset['created_at']) > time() - 1800) { // 30 минут
        // Обновление пароля
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password = ? WHERE email = ?")
            ->execute([$hashed, $reset['email']]);
        
        // Очистка токена
        $pdo->prepare("DELETE FROM password_resets WHERE email = ?")
            ->execute([$reset['email']]);
        
        $success = "Пароль успешно обновлён. Вы можете войти.";
    } else {
        $error = "Ссылка недействительна или устарела.";
    }
}

// Отображение формы/сообщений
ob_start();
?>
<h2>🔐 Новый пароль</h2>

<?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php elseif ($success): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
    <p><a href="login.php">Войти</a></p>
<?php else: ?>
    <form method="post" class="form-card">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <label for="new_password">🔒 Новый пароль:</label>
        <input type="password" name="new_password" required>
        <button type="submit">Сменить пароль</button>
    </form>
<?php endif; ?>

<?php
$content = ob_get_clean();
$title = 'Сброс пароля';
include '../includes/layout.php';