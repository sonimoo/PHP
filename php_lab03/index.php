<?php
/**
 * Сканирует директорию для поиска изображений и отображает их в галерее.
 *
 * В случае ошибки открытия директории выводится сообщение об ошибке.
 *
 * @var string $dir Путь к директории с изображениями.
 * @var array|false $files Массив файлов в директории или false в случае ошибки.
 */
$dir = 'image/';
$files = scandir($dir);

/**
 * Проверка на успешное открытие директории.
 */
if ($files === false) {
    echo "<p>Ошибка: не удалось открыть директорию</p>";
    return;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>KinoCritic</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-content">
            <h1 class="logo">KinoCritic</h1>
            <nav>
                <a href="#">Главная</a>
                <a href="#">Фильмы</a>
                <a href="#">Контакты</a>
            </nav>
        </div>
    </header>
    <main>
        <div class="gallery">
            <?php
            /**
             * Проходит по всем файлам в директории и выводит изображения в виде карточек.
             *
             * @var int $i Индекс текущего файла.
             * @var string $path Путь к изображению.
             */
            for ($i = 0; $i < count($files); $i++) {
                if ($files[$i] != "." && $files[$i] != "..") {
                    $path = $dir . $files[$i];
                    echo "<div class='card'><img src='$path' alt='Обложка фильма'></div>";
                }
            }
            ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 KinoCritic </p>
    </footer>
</body>
</html>
