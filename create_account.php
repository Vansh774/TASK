<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// DB connection
$conn = new mysqli("localhost", "root", "", "web_app");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current user type from session or DB
$user_type = '';
$uname = $_SESSION['username'];

$stmt_check = $conn->prepare("SELECT type FROM user_table WHERE Username = ?");
$stmt_check->bind_param("s", $uname);
$stmt_check->execute();
$stmt_check->bind_result($user_type);
$stmt_check->fetch();
$stmt_check->close();

$msg = "";

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $address   = $_POST['address'] ?? '';
    $age       = $_POST['age'] ?? '';
    $gender    = $_POST['gender'] ?? '';
    $birthdate = $_POST['dob'] ?? '';
    $username  = $_POST['username'] ?? '';
    $email     = $_POST['email'] ?? '';
    $password  = $_POST['password'] ?? '';
    $phone     = $_POST['phone'] ?? '';
    $type      = 'MEMBER';

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Insert into user_info_table
    $stmt_info = $conn->prepare("INSERT INTO user_info_table (Address, Age, Gender, DOB) VALUES (?, ?, ?, ?)");
    $stmt_info->bind_param("siss", $address, $age, $gender, $birthdate);
    $stmt_info->execute();
    $stmt_info->close();

    // Insert into user_table
    $stmt_user = $conn->prepare("INSERT INTO user_table (Phone_Number, Password, Username, User_Email, type) VALUES (?, ?, ?, ?, ?)");
    $stmt_user->bind_param("sssss", $phone, $password, $username, $email, $type);
    if ($stmt_user->execute()) {
        $msg = "User created successfully.";
    } else {
        $msg = "Error: " . $stmt_user->error;
    }
    $stmt_user->close();
}

$conn->close();
?>

<!-- HTML part remains the same -->


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registration Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      margin: 1px;
      padding: 0;
    }

    .navbar {
      background-color: #4CAF50;
      padding: 15px;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar a {
      color: white;
      text-decoration: none;
      margin-left: 20px;
      font-weight: bold;
    }

    .form-container {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      margin: 40px auto;
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    input[type="text"],
    input[type="email"],
    input[type="number"],
    input[type="date"],
    input[type="password"],
    input[type="tel"],
    textarea {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
    }

    input[type="submit"],
    button[type="submit"] {
      width: 100%;
      background-color: #4CAF50;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
    }

    input[type="submit"]:hover,
    button[type="submit"]:hover {
      background-color: #45a049;
    }

    .form-footer {
      text-align: center;
      margin-top: 10px;
      font-size: 0.9em;
      color: #666;
    }

    .message {
      text-align: center;
      color: green;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="navbar">
  <div><strong>Add User</strong></div>
  <div><a href="dashboard.php">Go to Dashboard</a></div>
</div>

<div class="form-container">
  <h2>Create Account</h2>
  <p class="message"><?php echo $msg; ?></p>

  <!-- Register Form -->
  <form action="" method="POST">
    <input type="hidden" name="register" value="1">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="tel" name="phone" placeholder="Phone Number" required>
    <input type="password" name="password" placeholder="Password" required>
    <textarea name="address" rows="3" placeholder="Your full address" required></textarea>
    <input type="number" name="age" min="1" max="120" placeholder="Enter your age" required>

    <label><input type="radio" name="gender" value="Male" required> Male</label>
    <label><input type="radio" name="gender" value="Female" required> Female</label>
    <label><input type="radio" name="gender" value="Other" required> Other</label>

    <input type="date" name="dob" required>
    <input type="submit" value="Register">
  </form>

  <!-- Only show to ADMIN -->
  <?php if ($user_type === 'ADMIN'): ?>
    <form action="generate_user.php" method="POST" style="margin-top: 15px;">
      <button type="submit">Generate 100 Random Users</button>
    </form>
  <?php endif; ?>

  <div class="form-footer">
    Already have an account? <a href="login.php">Login</a>
  </div>
</div>

</body>
</html>