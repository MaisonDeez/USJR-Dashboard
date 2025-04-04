<?php
session_start();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=usjr', 'root', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_GET['id'])) {
    $progid = htmlspecialchars($_GET['id']);

    try {
        $stmt = $pdo->prepare("
            SELECT 
                programs.progid, 
                programs.progfullname, 
                programs.progshortname, 
                departments.deptfullname AS departmentName, 
                colleges.collfullname AS collegeName
            FROM programs
            JOIN departments ON programs.progcolldeptid = departments.deptid
            JOIN colleges ON programs.progcollid = colleges.collid
            WHERE programs.progid = :progid
        ");
        $stmt->bindParam(':progid', $progid, PDO::PARAM_INT);
        $stmt->execute();
        $program = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($program) {
            echo json_encode($program); 
        } else {
            echo "<script>alert('Program not found.'); window.location.href = 'programs.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Error fetching program: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['progid']) && isset($_POST['proceed_delete'])) {
        $progid = htmlspecialchars($_POST['progid']);

        try {
            $deleteStmt = $pdo->prepare("DELETE FROM programs WHERE progid = :progid");
            $deleteStmt->bindParam(':progid', $progid, PDO::PARAM_INT);

            if ($deleteStmt->execute()) {
                header("Location: ../HTML/programs.html"); 
                exit();
            } else {
                echo "Error deleting program.";
            }
        } catch (PDOException $e) {
            echo "Error deleting program: " . $e->getMessage();
        }
    }
}

$pdo = null;
?>
