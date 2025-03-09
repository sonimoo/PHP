<?php

declare(strict_types=1);

$transactions = [
    [
        "id" => 1,
        "date" => "2019-01-01",
        "amount" => 100.00,
        "description" => "Payment for groceries",
        "merchant" => "SuperMart",
    ],
    [
        "id" => 2,
        "date" => "2020-02-15",
        "amount" => 75.50,
        "description" => "Dinner with friends",
        "merchant" => "Local Restaurant",
    ],
    [
        "id" => 3,
        "date" => "2025-03-09",
        "amount" => 250.30,
        "description" => "Payment for groceries",
        "merchant" => "SuperMart",
    ],
];

/**
 * Вычисляет общую сумму всех транзакций.
 *
 * @param array $transactions
 * @return float
 */
function calculateTotalAmount(array $transactions): float {
    return array_sum(array_column($transactions, 'amount'));
}

/**
 * Ищет транзакцию по части описания.
 *
 * @param string $descriptionPart
 * @return array
 */
function findTransactionByDescription(string $descriptionPart): array {
    global $transactions;
    return array_filter($transactions, function ($transaction) use ($descriptionPart) {
        return stripos($transaction['description'], $descriptionPart) !== false;
    });
}

/**
 * Ищет транзакцию по идентификатору.
 *
 * @param int $id
 * @return array|null
 */
function findTransactionById(int $id): ?array {
    global $transactions;
    foreach ($transactions as $transaction) {
        if ($transaction['id'] === $id) {
            return $transaction;
        }
    }
    return null;
}

/**
 * Вычисляет количество дней с момента транзакции.
 *
 * @param string $date
 * @return int
 */
function daysSinceTransaction(string $date): int {
    $transactionDate = new DateTime($date);
    $currentDate = new DateTime();
    return $transactionDate->diff($currentDate)->days;
}

/**
 * Добавляет новую транзакцию в массив.
 *
 * @param int $id
 * @param string $date
 * @param float $amount
 * @param string $description
 * @param string $merchant
 */
function addTransaction(int $id, string $date, float $amount, string $description, string $merchant): void {
    global $transactions;
    $transactions[] = [
        'id' => $id,
        'date' => $date,
        'amount' => $amount,
        'description' => $description,
        'merchant' => $merchant,
    ];
}

/**
 * Сортирует транзакции по дате.
 *
 * @param array $transactions
 * @return void
 */
function sortTransactionsByDate(array &$transactions): void {
    usort($transactions, function ($a, $b) {
        return strtotime($a['date']) <=> strtotime($b['date']);
    });
}

/**
 * Сортирует транзакции по сумме (по убыванию).
 *
 * @param array $transactions
 * @return void
 */
function sortTransactionsByAmount(array &$transactions): void {
    usort($transactions, function ($a, $b) {
        return $b['amount'] <=> $a['amount'];
    });
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Transactions</title>
    <style>
        body {
            text-align: center;
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 70%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: lightgray;
        }
    </style>
</head>
<body>
    <h2>Список банковских транзакций</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Дата</th>
                <th>Сумма</th>
                <th>Описание</th>
                <th>Магазин</th>
                <th>Дней с транзакции</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= $transaction['id'] ?></td>
                    <td><?= $transaction['date'] ?></td>
                    <td><?= number_format($transaction['amount'], 2) ?></td>
                    <td><?= $transaction['description'] ?></td>
                    <td><?= $transaction['merchant'] ?></td>
                    <td><?= daysSinceTransaction($transaction['date']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><strong>Общая сумма транзакций:</strong> <?= number_format(calculateTotalAmount($transactions), 2) ?></p>
</body>
</html>
