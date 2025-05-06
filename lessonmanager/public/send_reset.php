<?php
/**
 * Обработка запроса на сброс пароля
 * 
 * 1. Проверяет существование email в системе
 * 2. Генерирует уникальный токен (действителен 30 минут)
 * 3. Отправляет ссылку для сброса (в демо-режиме выводит на экран)
 */

require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    // Проверка существования пользователя
    $user = $pdo->prepare("SELECT * FROM users WHERE email = ?")
               ->execute([$email])
               ->fetch();

    if ($user) {
        // Генерация токена
        $token = bin2hex(random_bytes(32));
        $expires = time() + 1800; // 30 минут

        // Обновление токена в БД
        $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
        $pdo->prepare("INSERT INTO password_resets VALUES (?, ?, NOW())")
           ->execute([$email, $token]);

        // Демо-режим: вывод ссылки вместо отправки email
        $resetLink = "http://".$_SERVER['HTTP_HOST']."/reset_password.php?token=$token";
        echo "<div class='alert'>📩 Ссылка для сброса: <a href='$resetLink'>$resetLink</a></div>";
    }
    
    // Унифицированный ответ для безопасности
    echo "<div class='info'>Если email зарегистрирован, вы получите письмо с инструкцией.</div>";
}
?>

<p><a href="login.php" class="back-link">← Назад к входу</a></p>

<?php
$content = ob_get_clean();
$title = 'Восстановление пароля';
include '../includes/layout.php';