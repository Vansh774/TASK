<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}
$con = mysqli_connect("localhost","root","","web_app");
if(!$con){
  echo "Cannot connect<br>";
  exit();
}

$user_type = '';
$uname = $_SESSION['username'];
$stmt = $con->prepare("SELECT type FROM user_table WHERE Username = ?");
$stmt->bind_param("s",$uname);
$stmt->execute();
$stmt->bind_result($user_type);
$stmt->fetch();
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
    <?php if ($user_type === 'ADMIN'): ?>
    <form action="fetch_from_table.php" method="POST">
    <a href="fetch_from_table.php">View User</a>
    </form>
  <?php endif; ?>
    
    <a href="logout.php">Logout</a>
  </div>

  <div class="content">
    <h1>Logged In</h1>

  </div>
</body>
</html>