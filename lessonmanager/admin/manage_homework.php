<?php
/**
 * Файл добавления домашнего задания
 * 
 * Этот скрипт позволяет администратору добавлять новое домашнее задание в систему.
 * Проверяет авторизацию, обрабатывает форму добавления задания и выводит результат операции.
 */

require_once '../includes/db.php';
require_once '../includes/auth.php';

// Проверяем аутентификацию пользователя с ролью 'admin'
checkAuth('admin');

// Инициализация переменных для сообщений об ошибках и успехе
$error = '';
$success = '';

// Обработка POST-запроса при отправке формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $subject = $_POST['subject'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    // Валидация обязательных полей
    if (empty($subject) || empty($description) || empty($due_date)) {
        $error = "Пожалуйста, заполните все поля.";
    } else {
        // Подготовка и выполнение SQL-запроса для добавления задания
        $stmt = $pdo->prepare("INSERT INTO homework (subject, description, due_date) VALUES (?, ?, ?)");
        $stmt->execute([$subject, $description, $due_date]);
        $success = "Домашнее задание добавлено!";
    }
}

// Начало буферизации вывода для шаблона
ob_start();
?>

<h2>Добавить домашнее задание</h2>

<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Предмет:</label>
    <select name="subject" required>
        <option value="">-- Выберите предмет --</option>
        <option value="Математика">Математика</option>
        <option value="Русский язык">Русский язык</option>
        <option value="Окружающий мир">Окружающий мир</option>
        <option value="Литературное чтение">Литературное чтение</option>
        <option value="ИЗО">ИЗО</option>
        <option value="Технология">Технология</option>
        <option value="Физкультура">Физкультура</option>
    </select><br><br>

    <label>Задание:</label><br>
    <textarea name="description" rows="4" cols="50" required></textarea><br><br>

    <label>Срок сдачи:</label>
    <input type="date" name="due_date" required><br><br>

    <input type="submit" name="submit" value="Добавить задание">
</form>

<p><a href="/LessonManager/admin/index.php">Назад</a></p>

<?php
// Получение содержимого буфера и его очистка
$content = ob_get_clean();

// Установка заголовка страницы
$title = 'Добавить домашнее задание';

// Включение основного шаблона страницы
include '../includes/layout.php';
?>