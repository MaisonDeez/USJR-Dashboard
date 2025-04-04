<?php
session_start();

try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=usjr", "root", "password", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) { 
    $username = trim($_POST['username']); 
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) { 
        try {
            $stmt = $pdo->prepare("SELECT `uid`, `name`, `password` FROM `appusers` WHERE `name` = ?");
            $stmt->execute([$username]); 
            $user = $stmt->fetch(); 

            if ($user && password_verify($password, $user['password'])) { 
                $_SESSION['uid'] = $user['uid']; 
                $_SESSION['username'] = $user['name'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error = "An error occurred while processing your request.";
        }
    } else {
        $error = "Both username and password are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }
        .navigation {
            padding: 5px 25px 8px;
            background-color: rgb(12, 78, 21);
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .navigation .logo-title-container {
            display: flex;
            align-items: center;
        }
        .navigation img {
            height: 8%;
            margin-right: 10px;
            width: 8%;
            vertical-align: middle;
            padding: 10px;
        }
        .navigation h1 {
            margin: 0;
            font-weight: 700;
            font-size: 40px;
            color: rgb(255, 200, 0);
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            background: linear-gradient(to bottom, #dff0d8, #f8f8f8);
            padding-top: 30px;
        }
        form {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        h1 {
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 20px;
            color: rgb(12, 78, 21);
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"], input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: rgb(12, 78, 21);
            outline: none;
        }
        .button-container {
            text-align: center;
        }
        input[type="submit"], input[type="reset"] {
            width: 45%;
            padding: 10px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        input[type="submit"] {
            background-color: rgb(12, 78, 21);
            color: white;
        }
        input[type="reset"] {
            background-color: rgb(255, 0, 0);
            color: white;
        }
        .error {
            color: red;
            font-size: 0.9rem;
            text-align: center;
            margin: 10px 0;
        }
        .register {
            text-align: center;
            margin-top: 10px;
            font-size: 0.9rem;
        }
        .register a {
            color: rgb(12, 78, 21);
            text-decoration: none;
        }
        .register a:hover {
            text-decoration: underline;
        }
    </style>
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
            <h1>User Login</h1>
            <?php if ($error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <div class="button-container">
                <input type="submit" value="Login" name="login">
                <input type="reset" value="Clear">
            </div>
            <div class="register">
                <p>You can register <a href="register.php">here</a>.</p>
            </div>
        </form>
    </div>
</body>
</html>
