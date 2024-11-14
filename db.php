<?php
// Menyimpan task ke dalam file
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task = $_POST['task'];
    if (!empty($task)) {
        file_put_contents('tasks.txt', $task . PHP_EOL, FILE_APPEND);
    }
}

// Membaca task dari file
$tasks = file('tasks.txt', FILE_IGNORE_NEW_LINES);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>To-Do List</title>
</head>
<body>
    <div class="container">
        <h1>To-Do List</h1>
        <form method="POST">
            <input type="text" name="task" placeholder="Masukkan tugas baru" required>
            <button type="submit">Tambah</button>
        </form>
        <ul>
            <?php foreach ($tasks as $task): ?>
                <li><?php echo htmlspecialchars($task); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>