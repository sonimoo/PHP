<?php
/**
 * Форма восстановления пароля
 * 
 * Позволяет пользователю запросить ссылку для сброса пароля
 * Отправляет данные на обработчик send_reset.php
 */

// Буферизация вывода для вставки в основной шаблон
ob_start();
?>

<!-- Форма запроса восстановления пароля -->
<h2>🔁 Восстановление пароля</h2>
<form method="post" action="send_reset.php" class="form-card">
    <label for="email">📧 Ваш email:</label>
    <input type="email" name="email" id="email" required>
    <button type="submit">Отправить ссылку</button>
</form>

<p><a href="login.php">← Назад к входу</a></p>

<?php
// Получение содержимого буфера и подключение основного шаблона
$content = ob_get_clean();
$title = 'Восстановление пароля';
include '../includes/layout.php';