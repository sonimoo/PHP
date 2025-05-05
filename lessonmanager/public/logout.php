<?php
session_start();
session_destroy();

ob_start();
?>

<h2>Вы вышли из системы</h2>
<p><a href="login.php">Вернуться ко входу</a></p>

<?php
$content = ob_get_clean();
$title = 'Выход';
require '../includes/layout.php';
