<?php
session_start();

if (!isset($_SESSION['username'])) {
    session_destroy();
    header('Location: ../../Homepage/logindashboard.php');
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ../../Homepage/logindashboard.php');
    exit();
}

try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=usjr", "root", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => "Connection failed: " . $e->getMessage()]));
}

try {
    $stmt = $pdo->query("SELECT collid, collfullname, collshortname FROM colleges");
    $colleges = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die(json_encode(['error' => "Query failed: " . $e->getMessage()]));
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

header('Content-Type: application/json');
echo json_encode([
    'colleges' => $colleges,
    'username' => $username
]);

$pdo = null;
?>
