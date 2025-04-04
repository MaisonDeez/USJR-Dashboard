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
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true); 
    if (isset($data['proceed_delete']) && isset($data['collid'])) {
        $collid = htmlspecialchars($data['collid']);

        try {
            $deleteStmt = $pdo->prepare("DELETE FROM colleges WHERE collid = ?");
            if ($deleteStmt->execute([$collid])) {
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
    $collid = htmlspecialchars($_GET['id']);
    $stmt = $pdo->prepare("SELECT collid, collfullname, collshortname FROM colleges WHERE collid = ?");
    $stmt->execute([$collid]);
    $college = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($college);
    exit();
}
?>
