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
    die("Connection failed: " . $e->getMessage());
}

$sql = "
SELECT 
    d.deptid, 
    d.deptfullname AS deptname, 
    d.deptshortname,
    c.collfullname AS collegename
FROM departments d
JOIN colleges c ON d.deptcollid = c.collid
";

try {
    $stmt = $pdo->query($sql);
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

header('Content-Type: application/json');
echo json_encode([
    'departments' => $departments,
    'username' => $username
]);
?>
