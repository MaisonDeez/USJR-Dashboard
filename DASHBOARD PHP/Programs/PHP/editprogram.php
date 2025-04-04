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
        $program_id = htmlspecialchars($_GET['id']);
        
        $stmt = $pdo->prepare("
            SELECT 
                progid, 
                progfullname, 
                progshortname, 
                progcollid, 
                progcolldeptid
            FROM programs 
            WHERE progid = ?
        ");
        $stmt->execute([$program_id]);
        $program = $stmt->fetch(PDO::FETCH_ASSOC);

        $colleges = $pdo->query("SELECT collid, collfullname FROM colleges")->fetchAll(PDO::FETCH_ASSOC);
        $departments = $pdo->query("SELECT deptid, deptfullname FROM departments")->fetchAll(PDO::FETCH_ASSOC);

        if (!$program) {
            echo json_encode(['error' => 'Program not found']);
            exit();
        }

        echo json_encode([
            'program' => $program,
            'colleges' => $colleges,
            'departments' => $departments,
        ]);
        exit();
    } else {
        echo json_encode(['error' => 'Invalid request.']);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $progid = htmlspecialchars($_POST['progid']);
    $progfullname = htmlspecialchars($_POST['progfullname']);
    $progshortname = htmlspecialchars($_POST['progshortname']);
    $progcollid = htmlspecialchars($_POST['progcollid']);
    $progcolldeptid = htmlspecialchars($_POST['progcolldeptid']);

    try {
        $stmt = $pdo->prepare("
            UPDATE programs 
            SET progfullname = ?, progshortname = ?, progcollid = ?, progcolldeptid = ? 
            WHERE progid = ?
        ");
        $stmt->execute([$progfullname, $progshortname, $progcollid, $progcolldeptid, $progid]);

        header("Location: ../HTML/programs.html"); 
        exit();
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to update program: ' . $e->getMessage()]);
        exit();
    }
}

echo json_encode(['error' => 'Invalid request method.']);
exit();
