<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../../Homepage/logindashboard.php');
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
    $sql_colleges = "SELECT collid, collfullname FROM colleges";
    $stmt_colleges = $pdo->query($sql_colleges);
    $colleges = $stmt_colleges->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($colleges);
    exit();
}

if (isset($_GET['college'])) {
    try {
        $college_id = htmlspecialchars($_GET['college']);
        $sql_programs = "SELECT progid, progfullname FROM programs WHERE progcollid = ?"; 
        $stmt_programs = $pdo->prepare($sql_programs);
        $stmt_programs->execute([$college_id]);
        $programs = $stmt_programs->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($programs);
        exit();
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to fetch programs.']);
        exit();
    }
}

if (isset($_GET['check_student_id'])) {
    $student_id = htmlspecialchars($_GET['student_id']);
    $checkStmt = $pdo->prepare("SELECT * FROM students WHERE studid = ?");
    $checkStmt->execute([$student_id]);
    if ($checkStmt->rowCount() > 0) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = htmlspecialchars($_POST['student_id']);
    $first_name = htmlspecialchars($_POST['first_name']);
    $middle_name = htmlspecialchars($_POST['middle_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $college_id = htmlspecialchars($_POST['college']);
    $program_id = htmlspecialchars($_POST['program']);
    $year = htmlspecialchars($_POST['year']);

    $sql = "
        INSERT INTO students (studid, studfirstname, studmidname, studlastname, studcollid, studprogid, studyear) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$student_id, $first_name, $middle_name, $last_name, $college_id, $program_id, $year])) {
        echo json_encode(['status' => 'success']);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'error' => 'Error inserting student data']);
        exit();
    }
}
?>
