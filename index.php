<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Расписание и Циклы</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>Расписание</h1>
<?php
$weekDay = date('l');

function workingHours($name, $weekDay) {
    if ($name === 'John Styles') {
        return in_array($weekDay, ['Monday', 'Wednesday','Friday']) ? '8:00-12:00' : 'Нерабочий день';
    } elseif ($name === 'Jane Doe') {
        return in_array($weekDay, ['Tuesday', 'Thursday', 'Saturday']) ? '12:00-16:00' : 'Нерабочий день';
    }
    return 'Нерабочий день';
}

$person = [
    '№' => ['1', '2'],
    'Фамилия Имя' => ['John Styles', 'Jane Doe'],
    'График работы' => [
        workingHours('John Styles', $weekDay),
        workingHours('Jane Doe', $weekDay),
    ]
];
?>

<table>
    <thead>
        <tr>
            <?php foreach ($person as $key => $value): ?>
                <th><?= htmlspecialchars($key) ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 0; $i < count($person['№']); $i++): ?>
            <tr>
                <?php foreach ($person as $column): ?>
                    <td><?= htmlspecialchars($column[$i]) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>

<h1>Циклы</h1>
<div class="cycle-section">
    <h2>Цикл for</h2>
<?php
$a = 0;
$b = 0;

echo "Initial values : a = $a, b = $b<br>";

for ($i = 0; $i <= 5; $i++) {
   $a += 10;
   $b += 5;
   echo "a = $a, b = $b <br>";
}

echo "End of the loop: a = $a, b = $b";

?>

    <h2>Цикл while</h2>
<?php
$a = 0;
$b = 0;

echo "Initial values: a = $a, b = $b<br>";

$i = 0;
while ($i <= 5) {
    $a += 10;
    $b += 5;
    echo "a = $a, b = $b <br>";
    $i++;
}

echo "End of the loop: a = $a, b = $b";
?>

    <h2>Цикл do-while</h2>
    
<?php
$a = 0;
$b = 0;

echo "Initial values: a = $a, b = $b<br>";

$i = 0;
do {
    $a += 10;
    $b += 5;
    echo "a = $a, b = $b <br>";
    $i++;
} while ($i <= 5);

echo "End of the loop: a = $a, b = $b";
?>
</div>

</body>
</html>
