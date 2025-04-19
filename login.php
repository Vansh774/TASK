<?php
ob_start(); 
session_start();
$dbname = "web_app";
// Database connection
$con = mysqli_connect("localhost", "root", "");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$msg = "";
$setup = false;
$db_exists = mysqli_query($con, "SHOW DATABASES LIKE '$dbname'");

if (mysqli_num_rows($db_exists) === 0) {
    // Database doesn't exist
    $setup = true;
} else {
    // Select DB only if it exists
    mysqli_select_db($con, $dbname);

    // Now check if required tables exist
    $check_user = mysqli_query($con,"SHOW TABLES LIKE 'user_table'");
    $check_info = mysqli_query($con,"SHOW TABLES LIKE 'user_info_table'");

    if (mysqli_num_rows($check_user) == 0 || mysqli_num_rows($check_info) == 0) {
        $setup = true;
    }
}
if (isset($_POST['setup'])) {
  mysqli_query($con, "CREATE DATABASE IF NOT EXISTS $dbname");
  mysqli_select_db($con, $dbname);

  $create_user = "CREATE TABLE IF NOT EXISTS user_table (
      Phone_Number VARCHAR(20),
      Username VARCHAR(20),
      User_Email VARCHAR(40),
      Password VARCHAR(255),
      type ENUM('MEMBER','ADMIN')
  )";
  mysqli_query($con, $create_user);

  $create_info = "CREATE TABLE IF NOT EXISTS user_info_table (
      Address VARCHAR(50),
      Age INT(11),
      Gender VARCHAR(10),
      DOB DATE
  )";
  mysqli_query($con, $create_info);

  header("Location: login.php"); // reload to reflect changes
  exit();
}
// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$setup) {
    $username = $_POST['username'];
    $password = $_POST['password']; // raw input

    // Check if username exists
    $sql = "SELECT * FROM user_table WHERE Username = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $fetch = mysqli_fetch_assoc($result);

        // Verify password
        if ($password === $fetch['Password']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $fetch['Username'];
            $_SESSION['user_type'] = $fetch['type']; // optional

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
          $msg = "<p style='color:red;'>Incorrect Password</p>";
        }
    } else {
        $msg = "<p style='color:red;'>Incorrect Username</p>";
    }
}

ob_end_flush(); // Flush output
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .form-container {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
    }

    input[type="submit"] {
      width: 100%;
      background-color: #4CAF50;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
    }
    #center {
      align-items: center;
      width: 100%;
      background-color: #4CAF50;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
    }
    #center:hover {
      align-items: center;
      width: 100%;
      background-color:rgb(65, 118, 67);
      color: white;
      padding: 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
    }

    input[type="submit"]:hover {
      background-color: #45a049;
    }

    .form-footer {
      text-align: center;
      margin-top: 10px;
      font-size: 0.9em;
      color: #666;
    }

    .error-msg {
      color: red;
      text-align: center;
    }
  </style>
</head>
<body>
<div class="form-container">
    <?php if ($setup): ?>
      <form method="POST">
        <button type="submit" name="setup" id="center">Setup Database</button>
      </form>
    <?php else: ?>
      <h2>Login</h2>
      <?php echo $msg; ?>
      <form action="" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="LOGIN">
      </form>
      <div class="form-footer">
        Don't have an account? <a href="register.php">Register here</a>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>