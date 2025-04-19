<?php
ob_start(); 
session_start();

// Database connection
$con = mysqli_connect("localhost", "root", "", "web_app");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$msg = "";

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
  </div>
</body>
</html>