<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../../Homepage/logindashboard.php');
    exit();
}

try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=usjr", "root", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true); 
    if (isset($data['proceed_delete']) && isset($data['student_id'])) {
        $student_id = htmlspecialchars($data['student_id']);

        try {
            $deleteStmt = $pdo->prepare("DELETE FROM students WHERE studid = ?");
            if ($deleteStmt->execute([$student_id])) {
                echo json_encode(['status' => 'success']); 
            } else {
                $errorInfo = $deleteStmt->errorInfo();
                echo json_encode(['status' => 'error', 'message' => $errorInfo[2]]);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request. Missing required parameters.']);
        exit();
    }
}

if (isset($_GET['id'])) {
    $student_id = htmlspecialchars($_GET['id']);
    $stmt = $pdo->prepare("
        SELECT 
            students.studid, 
            students.studlastname, 
            students.studfirstname, 
            students.studmidname,
            colleges.collshortname, 
            colleges.collfullname,   
            programs.progshortname, 
            programs.progfullname,   
            students.studyear
        FROM students
        JOIN colleges ON students.studcollid = colleges.collid
        JOIN programs ON students.studprogid = programs.progid
        WHERE students.studid = ?
    ");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($student);
    exit();
}
?>
