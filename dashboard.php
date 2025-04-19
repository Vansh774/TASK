<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
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
    <a href="logout.php">Logout</a>
  </div>

  <div class="content">
    <h1>Logged In</h1>
  </div>
</body>
</html>