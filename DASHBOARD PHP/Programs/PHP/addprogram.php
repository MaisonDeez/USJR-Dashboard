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

if (isset($_GET['colleges'])) {
    try {
        $stmt = $pdo->prepare("SELECT collid, collfullname FROM colleges");
        $stmt->execute();
        $colleges = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($colleges);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

if (isset($_GET['fetch_departments'])) {
    $collegeId = $_GET['college_id'];

    try {
        $stmt = $pdo->prepare("SELECT deptid, deptfullname AS deptname FROM departments WHERE deptcollid = ?");
        $stmt->execute([$collegeId]);
        $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($departments);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

if (isset($_GET['check_program_id'])) {
    $programId = $_GET['check_program_id'];

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM programs WHERE progid = ?");
        $stmt->execute([$programId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['exists' => $result['count'] > 0]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $progid = $_POST['progid'];
    $progfullname = $_POST['progfullname'];
    $progshortname = $_POST['progshortname'];
    $progcollid = $_POST['progcollid'];
    $progcolldeptid = $_POST['progcolldeptid'];

    try {
        $stmt = $pdo->prepare("INSERT INTO programs (progid, progfullname, progshortname, progcollid, progcolldeptid) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$progid, $progfullname, $progshortname, $progcollid, $progcolldeptid]);
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}
?>
