<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: logindashboard.php');
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: logindashboard.php');
    exit();
}

if (isset($_POST['students'])) {
    header('Location: ../Students/HTML/students.html');
    exit();
}

if (isset($_POST['departments'])) {
    header('Location: ../Department/HTML/departments.html');
    exit();
}

if (isset($_POST['colleges'])) {
    header('Location: ../Colleges/HTML/colleges.html');
    exit();
}

if (isset($_POST['programs'])) {
    header('Location: ../Programs/HTML/programs.html');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .link.active {
            font-weight: bold;
            color: rgb(255, 200, 0);
        }
        #logo {
            width: 10%;
            margin-right: 10px;
            vertical-align: middle;
            padding: 10px;
        }
        .navigation {
            padding: 5px 25px 8px;
            background-color: rgb(12, 78, 21);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .navigation .logo-title-container {
            display: flex;
            align-items: center;
        }
        .navigation h1 {
            margin: 0;
            font-weight: 700;
            font-size: 40px;
            color: rgb(255, 200, 0);
        }
        .container {
            padding-top: 35px;
        }
        .title {
            text-align: center;
            margin-bottom: 30px;
            color: rgb(12, 78, 21);
            font-weight: 700;
        }
        .logout {
            border-color: rgb(255, 200, 0);
            border-radius: 5px;
            height: 40px;
            border: 3px bold;
            color: rgb(255, 200, 0);
            font-weight: 700;
            padding: 5px 20px;
            background-color: rgb(12, 78, 21);
        }
        .dashboard {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 30px;
        }
        .dashboard-button {
            background-color: rgb(255, 200, 0);
            border-radius: 5px;
            border: 2px solid rgb(60, 60, 60);
            padding: 20px;
            color: white;
            font-size: 20px;
            font-weight: bold;
            width: 200px;
            text-align: center;
            margin: 10px;
            text-decoration: none;
        }
        .dashboard-button i {
            font-size: 50px;
            margin-bottom: 10px;
        }
        .dashboard-button:hover {
            background-color: rgb(12, 78, 21);
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <nav class="navigation">
        <div class="logo-title-container">
            <img src="../assets/usjrlogo.png" alt="usjrlogo" id="logo">
            <h1>School of Computer Studies</h1>
        </div>
        <form method="POST">
            <button type="submit" name="logout" class="logout">Logout</button>
        </form>
    </nav>

    <div class="container">
        <h1 class="title">School Management Dashboard</h1>
        <div class="dashboard">
            <form method="POST">
                <button type="submit" name="students" class="dashboard-button">
                    <i class="fas fa-user-graduate"></i><br>
                    Students
                </button>
                <button type="submit" name="departments" class="dashboard-button">
                    <i class="fas fa-building"></i><br>
                    Departments
                </button>
                <button type="submit" name="colleges" class="dashboard-button">
                    <i class="fas fa-university"></i><br>
                    Colleges
                </button>
                <button type="submit" name="programs" class="dashboard-button">
                    <i class="fas fa-book"></i><br>
                    Programs
                </button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
