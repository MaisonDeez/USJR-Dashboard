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

if (isset($_GET['colleges']) && $_GET['colleges'] === 'true') {
    $sql_colleges = "SELECT collid, collfullname FROM colleges";
    $stmt_colleges = $pdo->query($sql_colleges);
    $colleges = $stmt_colleges->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($colleges);
    exit();
}

if (isset($_GET['check_dept_id'])) {
    $dept_id = htmlspecialchars($_GET['department_id']);
    $checkStmt = $pdo->prepare("SELECT * FROM departments WHERE deptid = ?");
    $checkStmt->execute([$dept_id]);
    if ($checkStmt->rowCount() > 0) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
    exit();
}

if (isset($_GET['fetch_departments']) && $_GET['fetch_departments'] === 'true') {
    $sql = "
        SELECT 
            d.deptid, 
            d.deptfullname AS deptname, 
            d.deptshortname, 
            c.collfullname AS collegename
        FROM departments d
        JOIN colleges c ON d.deptcollid = c.collid
    ";

    $stmt = $pdo->query($sql);
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(['departments' => $departments]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dept_id = htmlspecialchars($_POST['deptid']);
    $full_name = htmlspecialchars($_POST['deptfullname']);
    $short_name = htmlspecialchars($_POST['deptshortname']);
    $college_id = htmlspecialchars($_POST['deptcollid']);

    $sql = "
        INSERT INTO departments (deptid, deptfullname, deptshortname, deptcollid) 
        VALUES (?, ?, ?, ?)
    ";
            
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$dept_id, $full_name, $short_name, $college_id])) {
        echo json_encode(['status' => 'success']);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'errors' => $errors]);
        exit();
    }
}
?>
