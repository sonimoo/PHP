<?php
require_once '../includes/db.php';

// Получаем расписание
$schedule = $pdo->query("
    SELECT 
        s.day, 
        s.time, 
        subj.name AS subject, 
        s.room, 
        t.full_name AS teacher
    FROM schedule s
    JOIN subjects subj ON s.subject_id = subj.id
    JOIN teachers t ON s.teacher_id = t.id
    ORDER BY FIELD(s.day, 'Понедельник','Вторник','Среда','Четверг','Пятница'), s.time
")->fetchAll(PDO::FETCH_ASSOC);


// Получаем новости
$news = $pdo->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Получаем преподавателей
$teachers = $pdo->query("
    SELECT t.full_name, s.name AS subject, t.email, t.phone 
    FROM teachers t
    JOIN subjects s ON t.subject_id = s.id
    ORDER BY t.full_name
")->fetchAll(PDO::FETCH_ASSOC);


ob_start();
?>

<h2>📅 Расписание на неделю</h2>
<?php
$current_day = null;
foreach ($schedule as $lesson):
    if ($lesson['day'] !== $current_day):
        if ($current_day !== null) echo '</tbody></table>';
        $current_day = $lesson['day'];
        echo "<h3 class='schedule-day'>" . htmlspecialchars($current_day) . "</h3>";
        echo "
        <table class='schedule-table'>
            <thead>
                <tr>
                    <th>Время</th>
                    <th>Предмет</th>
                    <th>Каб.</th>
                    <th>Учитель</th>
                </tr>
            </thead>
            <tbody>
        ";
    endif;
?>
    <tr>
        <td><?= date('H:i', strtotime($lesson['time'])) ?></td>
        <td><?= htmlspecialchars($lesson['subject']) ?></td>
        <td><?= htmlspecialchars($lesson['room']) ?></td>
        <td><?= htmlspecialchars($lesson['teacher']) ?></td>
    </tr>
<?php endforeach; ?>
</tbody></table>

<h2>📰 Последние новости</h2>
<?php foreach ($news as $item): ?>
    <div style="margin-bottom: 15px;">
        <strong><?= htmlspecialchars($item['title']) ?></strong><br>
        <em><?= date("d.m.Y", strtotime($item['created_at'])) ?></em><br>
        <p><?= nl2br(htmlspecialchars($item['content'])) ?></p>
    </div>
<?php endforeach; ?>

<h2>👩‍🏫 Наши преподаватели</h2>
<div class="teacher-list">
    <?php foreach ($teachers as $teacher): ?>
        <div class="teacher-card">
            <strong><?= htmlspecialchars($teacher['full_name']) ?></strong><br>
            <em><?= htmlspecialchars($teacher['subject']) ?></em><br>
            📧 <?= htmlspecialchars($teacher['email']) ?><br>
            📞 <?= htmlspecialchars($teacher['phone']) ?><br><br>
        </div>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
$title = "LessonManager — Главная";
require_once __DIR__ . '/../includes/layout.php';