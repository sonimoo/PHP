<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
checkAuth('admin');

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // 🟢 ВАЖНО: получаем роль из формы

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if (empty($username) || empty($password) || empty($full_name)) {
        $error = "Пожалуйста, заполните все поля.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (full_name, username, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$full_name, $username, $hashedPassword, 'student']);
            $success = "Ученик успешно добавлен.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Пользователь с таким логином уже существует.";
            } else {
                $error = "Ошибка: " . $e->getMessage();
            }
        }
    }
}

// Начинаем буферизацию вывода
ob_start();
?>

<h2>Добавление ученика</h2>
<?php if ($error): ?><p style="color:red"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<?php if ($success): ?><p style="color:green"><?= htmlspecialchars($success) ?></p><?php endif; ?>
<form method="post">
    <label>ФИО ученика: <input type="text" name="full_name" required></label><br>
    <label>Логин: <input type="text" name="username" required></label><br>
    <label>Пароль: <input type="password" name="password" required></label><br>
    <label for="role">Роль:</label>
    <select name="role" id="role" required>
        <option value="student">Ученик</option>
        <option value="admin">Администратор</option>
    </select>

    <button type="submit">Добавить</button>
</form>
<p><a href="/LessonManager/admin/index.php">Назад</a></p>

<?php
// Получаем содержимое страницы
$content = ob_get_clean();

// Название страницы
$title = 'Добавить ученика';

// Подключаем общий шаблон
include '../includes/layout.php';
?>
