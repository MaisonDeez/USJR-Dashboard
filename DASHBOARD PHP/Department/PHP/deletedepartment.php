<?php
session_start();

try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=usjr", "root", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['deptid']) && isset($_POST['proceed_delete'])) {
        $deptid = htmlspecialchars($_POST['deptid']);

        try {
            $deleteStmt = $pdo->prepare("DELETE FROM departments WHERE deptid = :deptid");
            $deleteStmt->bindParam(':deptid', $deptid, PDO::PARAM_INT);

            if ($deleteStmt->execute()) {
                header("Location: ../HTML/departments.html");
                exit();
            } else {
                echo "Error deleting department.";
            }
        } catch (PDOException $e) {
            echo "Error deleting department: " . $e->getMessage();
        }
    }
}

if (isset($_GET['id'])) {
    $deptid = htmlspecialchars($_GET['id']);

    try {
        $stmt = $pdo->prepare("
            SELECT 
                departments.deptid, 
                departments.deptfullname, 
                departments.deptshortname, 
                colleges.collfullname AS collegeName
            FROM departments
            JOIN colleges ON departments.deptcollid = colleges.collid
            WHERE departments.deptid = :deptid
        ");
        $stmt->bindParam(':deptid', $deptid, PDO::PARAM_INT);
        $stmt->execute();
        $department = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($department) {
            echo json_encode($department);
        } else {
            echo "<script>alert('Department not found.'); window.location.href = 'departments.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Error fetching department: " . $e->getMessage();
    }
}

$pdo = null;
?>
