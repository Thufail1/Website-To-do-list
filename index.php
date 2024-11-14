<?php
// Fungsi untuk membaca tugas dari file
function readTasks() {
    if (file_exists('tasks.json')) {
        return json_decode(file_get_contents('tasks.json'), true);
    }
    return [];
}

// Fungsi untuk menyimpan tugas ke file
function saveTasks($tasks) {
    file_put_contents('tasks.json', json_encode($tasks));
}

// Menangani penambahan tugas
if (isset($_POST['add'])) {
    $task = trim($_POST['task']);
    $priority = $_POST['priority'];
    $taskDate = $_POST['task_date'];
    $taskTime = $_POST['task_time'];
    if (!empty($task)) {
        $tasks = readTasks();
        $tasks[] = json_encode(['task' => $task, 'priority' => $priority, 'date' => $taskDate, 'time' => $taskTime]);
        saveTasks($tasks);
    }
}

// Menangani pengeditan tugas
if (isset($_POST['edit'])) {
    $taskId = $_POST['task_id'];
    $newTask = trim($_POST['task']);
    $newPriority = $_POST['priority'];
    $newTaskDate = $_POST['task_date'];
    $newTaskTime = $_POST['task_time'];
    if (!empty($newTask)) {
        $tasks = readTasks();
        $tasks[$taskId] = json_encode(['task' => $newTask, 'priority' => $newPriority, 'date' => $newTaskDate, 'time' => $newTaskTime]);
        saveTasks($tasks);
    }
}

// Menangani penghapusan tugas
if (isset($_POST['delete'])) {
    $taskId = $_POST['task_id'];
    $tasks = readTasks();
    unset($tasks[$taskId]);
    saveTasks(array_values($tasks));
}

$tasks = readTasks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Aplikasi To-Do List</h1>
        <form method="POST">
            <input type="text" name="task" placeholder="Masukkan tugas baru" required>
            <select name="priority">
                <option value="optional">Opsional</option>
                <option value="medium">Hampir Prioritas</option>
                <option value="high">Prioritas Tinggi</option>
            </select>
            <div class="date-time-container">
                <input type="date" name="task_date" required>
                <input type="time" name="task_time" required>
            </div>
            <button type="submit" name="add">Tambah</button>
        </form>
        <ul>
            <?php foreach ($tasks as $index => $taskJson): ?>
                <?php $taskData = json_decode($taskJson, true); ?>
                <li>
                    <input type="checkbox">
                    <span><?php echo $taskData['task']; ?> - Prioritas: <?php echo ucfirst($taskData['priority']); ?> - Tanggal: <?php echo $taskData['date']; ?> - Waktu: <?php echo $taskData['time']; ?></span>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="task_id" value="<?php echo $index; ?>">
                        <input type="text" name="task" value="<?php echo $taskData['task']; ?>" required style="display:none;">
                        <select name="priority" style="display:none;">
                            <option value="optional" <?php echo $taskData['priority'] == 'optional' ? 'selected' : ''; ?>>Opsional</option>
                            <option value="medium" <?php echo $taskData['priority'] == 'medium' ? 'selected' : ''; ?>>Hampir Prioritas</option>
                            <option value="high" <?php echo $taskData['priority'] == 'high' ? 'selected' : ''; ?>>Prioritas Tinggi</option>
                        </select>
                        <input type="date" name="task_date" value="<?php echo $taskData['date']; ?>" required style="display:none;">
                        <input type="time" name="task_time" value="<?php echo $taskData['time']; ?>" required style="display:none;">
                        <button type="submit" name="edit" style="display:none;">Simpan</button>
                        <button type="button" onclick="toggleEdit(<?php echo $index; ?>)">Edit</button>
                        <button type="submit" name="delete">Hapus</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script>
        function toggleEdit(index) {
            const form = document.querySelectorAll('form')[index + 1]; // +1 to skip the add form
            const inputs = form.querySelectorAll('input[type="text"], select, input[type="date"], input[type="time"], button[type="submit"]');
            inputs.forEach(input => {
                if (input.style.display === "none") {
                    input.style.display = "block";
                } else {
                    input.style.display = "none";
                }
            });
        }
    </script>
</body>
</html>