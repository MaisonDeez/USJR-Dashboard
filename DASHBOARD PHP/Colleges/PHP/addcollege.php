<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../Homepage/logindashboard.php');
    exit();
}

try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=usjr", "root", "password", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (isset($_GET['check_coll_id'])) {
    $coll_id = htmlspecialchars($_GET['college_id']);
    $checkStmt = $pdo->prepare("SELECT * FROM colleges WHERE collid = ?");
    $checkStmt->execute([$coll_id]);
    if ($checkStmt->rowCount() > 0) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
    exit();
}

if (isset($_GET['fetch_colleges']) && $_GET['fetch_colleges'] === 'true') {
    $sql = "SELECT collid, collfullname AS collname, collshortname FROM colleges";
    $stmt = $pdo->query($sql);
    $colleges = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(['colleges' => $colleges]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $coll_id = htmlspecialchars($_POST['collid']);
    $full_name = htmlspecialchars($_POST['collfullname']);
    $short_name = htmlspecialchars($_POST['collshortname']);

    $sql = "
        INSERT INTO colleges (collid, collfullname, collshortname) 
        VALUES (?, ?, ?)
    ";

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$coll_id, $full_name, $short_name])) {
        echo json_encode(['status' => 'success']);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'errors' => $errors]);
        exit();
    }
}
?>
