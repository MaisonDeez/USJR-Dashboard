<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../logindashboard.php');
    exit();
}

try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=usjr", "root", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => "Connection failed: " . $e->getMessage()]));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $department_id = htmlspecialchars($_GET['id']);
        
        $stmt = $pdo->prepare("
            SELECT 
                deptid, 
                deptfullname, 
                deptshortname, 
                deptcollid 
            FROM departments 
            WHERE deptid = ?
        ");
        $stmt->execute([$department_id]);
        $department = $stmt->fetch(PDO::FETCH_ASSOC);

        $colleges = $pdo->query("SELECT collid, collfullname FROM colleges")->fetchAll(PDO::FETCH_ASSOC);

        if (!$department) {
            echo json_encode(['error' => 'Department not found']);
            exit();
        }

        echo json_encode([
            'department' => $department,
            'colleges' => $colleges,
        ]);
        exit();
    } else {
        echo json_encode(['error' => 'Invalid request.']);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deptid = htmlspecialchars($_POST['deptid']);
    $deptfullname = htmlspecialchars($_POST['deptfullname']);
    $deptshortname = htmlspecialchars($_POST['deptshortname']);
    $deptcollid = htmlspecialchars($_POST['deptcollid']);

    try {
        $stmt = $pdo->prepare("
            UPDATE departments 
            SET deptfullname = ?, deptshortname = ?, deptcollid = ? 
            WHERE deptid = ?
        ");
        $stmt->execute([$deptfullname, $deptshortname, $deptcollid, $deptid]);

        header("Location: ../HTML/departments.html"); 
        exit();
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to update department: ' . $e->getMessage()]);
        exit();
    }
}

echo json_encode(['error' => 'Invalid request method.']);
exit();
