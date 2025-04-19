<?php
// Connect to the database
$con = mysqli_connect("localhost", "root", "", "web_app");

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Escape input values
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Query user by username
    $sql = "SELECT * FROM user_table WHERE Username = '$username'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) === 1) {
        $fetch = mysqli_fetch_assoc($result);
        
        // Compare plain-text passwords
        if ($fetch['Password'] === $password) {
            echo "<h3>Login successful</h3><br>";
        } else {
            echo "<p style='color:red;'>Incorrect password</p>";
        }
    } else {
        echo "<p style='color:red;'>User not found</p>";
    }
}
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f4f4f4;
    }

    .navbar {
      background-color: #333;
      overflow: hidden;
      padding: 15px 20px;
    }

    .navbar a {
      color: white;
      padding: 14px 20px;
      text-decoration: none;
      float: left;
    }

    .navbar a:hover {
      background-color: #575757;
    }

    .content {
      padding: 20px;
    }

    h1 {
      color: #333;
    }
  </style>
</head>
<body>
  <div class="navbar">
    <a href="create_account.php">Add User</a>
    <a href="fetch_from_table.php">View User</a>
  </div>

  <div class="content">
    <h1>Welcome to the Dashboard!</h1>
  </div>
</body>
</html>