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
    students.studid, 
    students.studlastname, 
    students.studfirstname, 
    LEFT(students.studmidname, 1) AS studmiddleinitial,
    colleges.collshortname, 
    programs.progshortname, 
    students.studyear
FROM students
JOIN colleges ON students.studcollid = colleges.collid
JOIN programs ON students.studprogid = programs.progid  
";

$stmt = $pdo->prepare($sql); 
$stmt->execute();  
$students = $stmt->fetchAll(PDO::FETCH_ASSOC); 

header('Content-Type: application/json'); 
echo json_encode([ 
    'students' => $students,
    'username' => $_SESSION['username']
]);
?>
