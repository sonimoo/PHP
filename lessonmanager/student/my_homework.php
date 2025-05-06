<?php

/**
 * Страница для отправки и редактирования домашнего задания студентом
 * 
 * 1. Проверяет авторизацию пользователя как студента
 * 2. Обрабатывает отправку или редактирование ответа на домашнее задание
 * 3. Валидирует данные и предотвращает возможные атаки (XSS, SQL инъекции)
 * 4. Выводит домашние задания с возможностью отправки или редактирования ответа
 * 5. Перезагружает страницу после успешной отправки или редактирования
 */
require_once '../includes/auth.php';
require_once '../includes/db.php';
checkAuth('student'); 

$message = ''; 

// Обработка отправки/редактирования ответа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submission_text'], $_POST['homework_id'])) {
    // Валидация и очистка данных перед использованием
    $submissionText = filter_var(trim($_POST['submission_text']), FILTER_SANITIZE_STRING); 
    $homeworkId = filter_var($_POST['homework_id'], FILTER_VALIDATE_INT); 
    $studentId = $_SESSION['user_id']; 

    // Проверяем, существует ли задание
    $stmt = $pdo->prepare("SELECT * FROM homework WHERE id = ?");
    $stmt->execute([$homeworkId]);
    $homework = $stmt->fetch(PDO::FETCH_ASSOC);

    // Сообщение об ошибке, если задание не найдено
    if (!$homework) {
        $message = '<p style="color:red;">❗ Задание не найдено.</p>';
    } elseif (empty($submissionText)) { 
        $message = '<p style="color:red;">❗ Ответ не может быть пустым.</p>';
    } elseif (new DateTime() > new DateTime($homework['due_date'])) { 
        $message = '<p style="color:red;">❗ Срок сдачи истёк, редактирование невозможно.</p>';
    } else {
        // Проверяем, есть ли уже ответ на задание
        $stmt = $pdo->prepare("SELECT * FROM submissions WHERE student_id = ? AND homework_id = ?");
        $stmt->execute([$studentId, $homeworkId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        // Если ответ уже есть - обновляем
        if ($existing) {
            $stmt = $pdo->prepare("UPDATE submissions SET answer_text = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$submissionText, $existing['id']]);
            $message = '<p style="color:green;">✅ Ответ обновлён!</p>';
        } else { 
            $stmt = $pdo->prepare("INSERT INTO submissions (student_id, homework_id, answer_text) VALUES (?, ?, ?)");
            $stmt->execute([$studentId, $homeworkId, $submissionText]);
            $message = '<p style="color:green;">✅ Ответ отправлен!</p>';
        }

        // Перезагрузка страницы после успешной отправки (Post-Redirect-Get)
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}

// Получаем домашние задания и ответы
$stmt = $pdo->prepare("SELECT h.*, s.answer_text, s.grade 
    FROM homework h
    LEFT JOIN submissions s 
        ON h.id = s.homework_id AND s.student_id = ? 
    ORDER BY h.due_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$homeworks = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start(); 
?>

<h2>Мои домашние задания</h2>

<?= $message ?> 

<?php foreach ($homeworks as $hw): ?>
    <div class="homework-container">
        <h3><?= htmlspecialchars($hw['title']) ?></h3>
        <p><strong>Описание:</strong> <?= nl2br(htmlspecialchars($hw['description'])) ?></p> 
        <p><strong>Срок сдачи:</strong> <?= htmlspecialchars($hw['due_date']) ?></p> 

        <?php
            $dueDatePassed = new DateTime() > new DateTime($hw['due_date']);
        ?>

        <!-- Если ответ уже отправлен -->
        <?php if ($hw['answer_text']): ?>
            <p style="color:green;"><strong>Вы отправили ответ:</strong></p>
            <blockquote><?= nl2br(htmlspecialchars($hw['answer_text'])) ?></blockquote> 
            <p><strong>Оценка:</strong> <?= htmlspecialchars($hw['grade'] ?? 'ещё не выставлена') ?></p>

            <?php if (!$dueDatePassed): ?>
                <!-- Если срок сдачи не истёк, можно обновить ответ -->
                <form method="post">
                    <input type="hidden" name="homework_id" value="<?= $hw['id'] ?>">
                    <label>Изменить ответ:<br>
                        <textarea name="submission_text" rows="4" cols="50" required><?= htmlspecialchars($hw['answer_text']) ?></textarea>
                    </label><br>
                    <button type="submit">Обновить ответ</button>
                </form>
            <?php else: ?>
                <p style="color:gray;">Редактирование невозможно — срок сдачи истёк.</p>
            <?php endif; ?>

        <!-- Если ответ ещё не отправлен -->
        <?php else: ?>
            <?php if (!$dueDatePassed): ?>
                <form method="post">
                    <input type="hidden" name="homework_id" value="<?= $hw['id'] ?>">
                    <label>Ответ:<br>
                        <textarea name="submission_text" rows="4" cols="50" required></textarea>
                    </label><br>
                    <button type="submit">Отправить</button>
                </form>
            <?php else: ?>
                <p style="color:red;">❗ Срок сдачи прошёл. Отправка ответа недоступна.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<?php
$content = ob_get_clean(); 
$title = 'Мои задания'; 
require '../includes/layout.php'; 
?>
