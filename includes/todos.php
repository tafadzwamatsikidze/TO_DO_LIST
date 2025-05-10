<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';

$error = '';
$success = '';

// Add new todo
if (isset($_POST['add_todo'])) {
    $task = sanitizeInput($_POST['task']);
    
    if (!empty($task)) {
        $stmt = $conn->prepare("INSERT INTO todos (user_id, task) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $task);
        
        if ($stmt->execute()) {
            $success = 'Task added successfully!';
        } else {
            $error = 'Failed to add task. Please try again.';
        }
    } else {
        $error = 'Task cannot be empty!';
    }
}

// Mark todo as complete/incomplete
if (isset($_GET['complete'])) {
    $todo_id = (int)$_GET['complete'];
    $completed = (int)$_GET['status'];
    
    $stmt = $conn->prepare("UPDATE todos SET completed = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iii", $completed, $todo_id, $user_id);
    $stmt->execute();
}

// Delete todo
if (isset($_GET['delete'])) {
    $todo_id = (int)$_GET['delete'];
    
    $stmt = $conn->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $todo_id, $user_id);
    $stmt->execute();
}

// Get all todos for the current user
$todos = [];
$stmt = $conn->prepare("SELECT id, task, completed, created_at FROM todos WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $todos[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Todos | Todo App</title>
    <link rel="stylesheet" href="/TO_DO_LIST/includes/style.css">
    <link rel="stylesheet" href="/TO_DO_LIST/includes/dark-mode.css" id="dark-mode-style" disabled>
</head>
<body>
    <header>
        <h1>BIG<span>MIKE</span> TASK <span>MANAGER</span></h1>
        <div class="header-actions">
            <button id="dark-mode-toggle" class="btn-icon">🌓</button>
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </header>
    
    <main class="container">
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form action="todos.php" method="POST" class="todo-form">
            <input type="text" name="task" placeholder="Add a new task..." required>
            <button type="submit" name="add_todo" class="btn">Add</button>
        </form>
        
        <div class="todos-container">
            <?php if (empty($todos)): ?>
                <p class="empty-message">No tasks yet. Add one above!</p>
            <?php else: ?>
                <ul class="todos-list">
                    <?php foreach ($todos as $todo): ?>
                        <li class="todo-item <?php echo $todo['completed'] ? 'completed' : ''; ?>">
                            <span class="task-text"><?php echo htmlspecialchars($todo['task']); ?></span>
                            
                            <div class="todo-actions">
                                <a href="todos.php?complete=<?php echo $todo['id']; ?>&status=<?php echo $todo['completed'] ? 0 : 1; ?>" 
                                   class="btn-icon"><?php echo $todo['completed'] ? '↩' : '✓'; ?></a>
                                <a href="todos.php?delete=<?php echo $todo['id']; ?>" class="btn-icon delete">✕</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </main>
    
    <script src="/TO_DO_LIST/includes/script.js"></script>
</body>
</html>