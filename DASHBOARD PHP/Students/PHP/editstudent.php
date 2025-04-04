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
    die(json_encode(['error' => "Connection failed: " . $e->getMessage()]));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $student_id = htmlspecialchars($_GET['id']);

        $stmt = $pdo->prepare(" 
            SELECT 
                students.studid, 
                students.studlastname, 
                students.studfirstname, 
                students.studmidname,
                students.studyear,
                colleges.collid, 
                programs.progid
            FROM students
            JOIN colleges ON students.studcollid = colleges.collid
            JOIN programs ON students.studprogid = programs.progid
            WHERE students.studid = ?
        ");
        $stmt->execute([$student_id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        $colleges = $pdo->query("SELECT collid, collfullname FROM colleges")->fetchAll(PDO::FETCH_ASSOC);
        $programs = $pdo->query("SELECT progid, progfullname, progcollid FROM programs")->fetchAll(PDO::FETCH_ASSOC);

        if (!$student) {
            echo json_encode(['error' => 'Student not found']);
            exit();
        }

        echo json_encode([
            'student' => $student,
            'colleges' => $colleges,
            'programs' => $programs
        ]);
        exit();
    } else {
        echo json_encode(['error' => 'Invalid request.']);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = htmlspecialchars($_POST['student_id']);
    $first_name = htmlspecialchars($_POST['first_name']);
    $middle_name = htmlspecialchars($_POST['middle_name']);
    $last_name = htmlspecialchars($_POST['last_name'] );
    $college_id = htmlspecialchars($_POST['college']);
    $program_id = htmlspecialchars($_POST['program']);
    $year = htmlspecialchars($_POST['year']);

    if ($student_id && $first_name && $last_name && $college_id && $program_id && $year) {
        $updateStmt = $pdo->prepare("
            UPDATE students
            SET studfirstname = ?, studlastname = ?, studmidname = ?, studcollid = ?, studprogid = ?, studyear = ?
            WHERE studid = ?
        ");
        $updateStmt->execute([$first_name, $last_name, $middle_name, $college_id, $program_id, $year, $student_id]);

        header("Location: ../HTML/students.html"); 
        exit();
    } else {
        echo json_encode(['error' => 'Please fill in all required fields.']);
        exit();
    }
}

echo json_encode(['error' => 'Invalid request method.']);
exit();
