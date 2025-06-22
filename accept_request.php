<?php
session_start();
include 'config.php';

// Check user logged in and is admin (optional, or adjust as needed)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $id = intval($_POST['id'] ?? 0);

    if ($id > 0) {
        if ($type === 'recycle') {
            $conn->query("UPDATE history SET accepted = 1 WHERE id = $id");
        } elseif ($type === 'donation') {
            $conn->query("UPDATE donations SET accepted = 1 WHERE id = $id");
        }
    }
}

// Redirect back to page2.php
header('Location: page2.php');
exit();
