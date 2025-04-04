<?php
session_start();

header('Content-Type: application/json');

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
    echo json_encode(['error' => "Connection failed: " . $e->getMessage()]);
    exit();
}

$sql = "
SELECT 
    progid, 
    progfullname, 
    progshortname, 
    progcollid, 
    progcolldeptid
FROM programs
";

try {
    $stmt = $pdo->query($sql);
    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo json_encode(['error' => "Query failed: " . $e->getMessage()]);
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

echo json_encode([
    'programs' => $programs,
    'username' => $username
]);

$pdo = null;
?>
