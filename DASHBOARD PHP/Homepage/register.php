<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        $dbh = new PDO("mysql:host=127.0.0.1;dbname=usjr", "root", "password");

        $name = trim($_POST['username']);
        $userpassword = trim($_POST['password']);
        $verifypassword = trim($_POST['verifypass']);

        if ($userpassword !== $verifypassword) {
            echo "<script>alert('Passwords do not match');</script>";
            exit;
        }

        if (!preg_match('/^[a-zA-Z]+$/', $name)) {
            echo "<script>alert('Username must only contain letters (no numbers or special characters).');</script>";
            exit;
        }        

        $checkSql = "SELECT COUNT(*) FROM appusers WHERE name = ?";
        $stmt = $dbh->prepare($checkSql);
        $stmt->bindParam(1, $name, PDO::PARAM_STR);
        $stmt->execute();
        $nameExists = $stmt->fetchColumn();

        if ($nameExists > 0) {
            echo "<script>alert('The username already exists. Try another name.');</script>";
        } else {
            $sql = "INSERT INTO appusers (name, password) VALUES (?, ?)";
            $stmt = $dbh->prepare($sql);

            $hashedPassword = password_hash($userpassword, PASSWORD_BCRYPT);

            $stmt->bindParam(1, $name, PDO::PARAM_STR);
            $stmt->bindParam(2, $hashedPassword, PDO::PARAM_STR);

            $result = $stmt->execute();

            if ($result) {
                echo "<script>alert('New user added successfully!');</script>";
            } else {
                echo "<script>alert('Failed to add user. Please try again later.');</script>";
            }
        }
    } catch (PDOException $e) {
        echo "<script>alert('Database Error: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <nav class="navigation">
        <div class="logo-title-container">
            <img src="../assets/usjrlogo.png" alt="USJ-R Logo">
            <h1>School of Computer Studies</h1>
        </div>
    </nav>
    <div class="container">
        <form action="" method="POST">
            <h1>Add New User</h1>

            <label for="username">Username</label>
            <input type="text" name="username" required><br>

            <label for="password">Password</label>
            <input type="password" name="password" required><br>

            <label for="verifypass">Verify Password</label>
            <input type="password" name="verifypass" required><br>

            <div class="button-container">
                <input type="submit" value="Register" name="register">
                <input type="reset" value="Clear">
            </div>
            <div class="register-link">
                <p>Already registered? <a href="logindashboard.php">Login here</a>.</p>
            </div>
        </form>
    </div>
</body>
</html>
