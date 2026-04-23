<?php
$file = 'queue.txt';
$lines = [];
if (file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

$queue = [];
foreach ($lines as $line) {
    $parts = explode('|', $line, 3);
    if (count($parts) >= 3) {
        $queue[] = ['id' => $parts[0], 'name' => $parts[1], 'reason' => $parts[2]];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <!-- Auto refresh every 10s for professor -->
    <meta http-equiv="refresh" content="10">
</head>
<body>
    <div class="container">
        <h1>Professor Dashboard</h1>
        
        <?php if (!empty($queue)): ?>
            <form action="api.php?action=complete" method="POST">
                <button type="submit" class="btn-complete">Mark Top Student as Complete</button>
            </form>
        <?php endif; ?>

        <h2>Current Queue</h2>
        <ul class="queue-list">
            <?php if (empty($queue)): ?>
                <p class="empty-msg">No students in the queue. Time for a coffee break!</p>
            <?php else: ?>
                <?php foreach ($queue as $index => $student): ?>
                    <li class="queue-item" style="<?= $index === 0 ? 'border-left-color: #28a745; background-color: #333c33;' : '' ?>">
                        <div>
                            <h3>#<?= $index + 1 ?> - <?= htmlspecialchars($student['name']) ?></h3>
                            <p><?= htmlspecialchars($student['reason']) ?></p>
                        </div>
                        <?php if ($index === 0): ?>
                            <span style="color: #28a745; font-weight: bold;">[In Session]</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <div class="links">
            <a href="student.php">Switch to Student View</a>
        </div>
    </div>
</body>
</html>
