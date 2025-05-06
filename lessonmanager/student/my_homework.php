<?php
/**
 * Страница домашних заданий студента
 * 
 * Позволяет:
 * - Просматривать список заданий
 * - Отправлять/редактировать ответы
 * - Видеть оценки
 * 
 * Доступ: только для студентов
 */

require_once '../includes/auth.php';
require_once '../includes/db.php';
checkAuth('student'); 
$message = ''; // Сообщения об операциях

// Обработка отправки ответа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submissionText = trim($_POST['submission_text']);
    $homeworkId = (int)$_POST['homework_id'];
    
    // Проверки задания
    $homework = $pdo->prepare("SELECT * FROM homework WHERE id = ?")
                   ->execute([$homeworkId])
                   ->fetch();

    if (!$homework) {
        $message = '❗ Задание не найдено';
    } elseif (empty($submissionText)) {
        $message = '❗ Ответ не может быть пустым';
    } elseif (new DateTime() > new DateTime($homework['due_date'])) {
        $message = '❗ Срок сдачи истёк';
    } else {
        // Обновление или создание ответа
        $existing = $pdo->prepare("SELECT * FROM submissions WHERE student_id = ? AND homework_id = ?")
                       ->execute([$_SESSION['user_id'], $homeworkId])
                       ->fetch();

        if ($existing) {
            $pdo->prepare("UPDATE submissions SET answer_text = ?, updated_at = NOW() WHERE id = ?")
               ->execute([$submissionText, $existing['id']]);
            $message = '✅ Ответ обновлён';
        } else {
            $pdo->prepare("INSERT INTO submissions (student_id, homework_id, answer_text) VALUES (?, ?, ?)")
               ->execute([$_SESSION['user_id'], $homeworkId, $submissionText]);
            $message = '✅ Ответ отправлен';
        }
        
        header("Location: " . $_SERVER['REQUEST_URI']); // PRG-паттерн
        exit();
    }
}

// Получение списка заданий с ответами
$homeworks = $pdo->prepare("
    SELECT h.*, s.answer_text, s.grade 
    FROM homework h
    LEFT JOIN submissions s ON h.id = s.homework_id AND s.student_id = ?
    ORDER BY h.due_date DESC
")->execute([$_SESSION['user_id']])
  ->fetchAll();
?>

<h2>Мои домашние задания</h2>

<?php if ($message): ?>
    <div class="alert"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php foreach ($homeworks as $hw): ?>
    <div class="homework-card">
        <h3><?= htmlspecialchars($hw['title']) ?></h3>
        <p><strong>Срок:</strong> <?= htmlspecialchars($hw['due_date']) ?></p>
        
        <?php $expired = new DateTime() > new DateTime($hw['due_date']); ?>
        
        <?php if ($hw['answer_text']): ?>
            <div class="submission">
                <p>Ваш ответ:</p>
                <blockquote><?= nl2br(htmlspecialchars($hw['answer_text'])) ?></blockquote>
                <p>Оценка: <?= $hw['grade'] ?? '—' ?></p>
                
                <?php if (!$expired): ?>
                    <form method="post">
                        <input type="hidden" name="homework_id" value="<?= $hw['id'] ?>">
                        <textarea name="submission_text" required><?= 
                            htmlspecialchars($hw['answer_text']) 
                        ?></textarea>
                        <button type="submit">Обновить</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php elseif (!$expired): ?>
            <form method="post">
                <input type="hidden" name="homework_id" value="<?= $hw['id'] ?>">
                <textarea name="submission_text" required></textarea>
                <button type="submit">Отправить</button>
            </form>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<?php
$content = ob_get_clean();
$title = 'Мои задания';
require '../includes/layout.php';