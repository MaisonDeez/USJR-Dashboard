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
        $college_id = htmlspecialchars($_GET['id']);

        $stmt = $pdo->prepare("SELECT collid, collfullname, collshortname FROM colleges WHERE collid = ?");
        $stmt->execute([$college_id]);
        $college = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$college) {
            echo json_encode(['error' => 'College not found']);
            exit();
        }

        echo json_encode(['college' => $college]);
        exit();
    } else {
        echo json_encode(['error' => 'Invalid request.']);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $college_id = htmlspecialchars($_POST['college_id'] ?? '');
    $college_name = htmlspecialchars($_POST['college_name'] ?? '');
    $college_shortname = htmlspecialchars($_POST['college_shortname'] ?? '');

    if ($college_id && $college_name && $college_shortname) {
        $updateStmt = $pdo->prepare("
            UPDATE colleges
            SET collfullname = ?, collshortname = ?
            WHERE collid = ?
        ");
        $updateStmt->execute([$college_name, $college_shortname, $college_id]);

        header("Location: ../HTML/colleges.html"); 
        exit();
    } else {
        echo json_encode(['error' => 'Please fill in all required fields.']);
        exit();
    }
}

echo json_encode(['error' => 'Invalid request method.']);
exit();
?>
