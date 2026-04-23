<?php
$file = 'queue.txt';

// Ensure file exists
if (!file_exists($file)) {
    file_put_contents($file, "");
}

$action = $_GET['action'] ?? '';

if ($action === 'get') {
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $queue = [];
    foreach ($lines as $line) {
        $parts = explode('|', $line, 3);
        if (count($parts) >= 3) {
            $queue[] = ['id' => $parts[0], 'name' => $parts[1], 'reason' => $parts[2]];
        }
    }
    header('Content-Type: application/json');
    echo json_encode($queue);
    exit;
}

if ($action === 'join' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $reason = trim($_POST['reason'] ?? '');
    if ($name !== '' && $reason !== '') {
        $id = uniqid();
        // Replacing newlines in reason to prevent messing up the text file structure
        $reason = str_replace(array("\r", "\n"), ' ', $reason);
        $name = str_replace(array("\r", "\n", "|"), ' ', $name);
        $reason = str_replace("|", ' ', $reason);
        
        $line = "$id|$name|$reason\n";
        file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
    }
    header("Location: student.php");
    exit;
}

if ($action === 'complete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!empty($lines)) {
        array_shift($lines); // Remove top student
        $newContent = empty($lines) ? "" : implode("\n", $lines) . "\n";
        file_put_contents($file, $newContent, LOCK_EX);
    }
    header("Location: professor.php");
    exit;
}
?>
