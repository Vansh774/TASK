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
    <meta charset="UTF-8">
    <title>View Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #4CAF50;
            padding: 15px;
            color: white;
            display: flex;
            justify-content: space-between;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }

        table {
            margin: 30px auto;
            border-collapse: collapse;
            width: 90%;
            background: white;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="navbar">
    <div><strong>Total users</strong></div>
    <div>
        <a href="dashboard.php">Go to Dashboard</a>
    </div>
</div>
</body>
</html>

<?php
    // Connect to the database
$con = mysqli_connect("localhost", "root", "", "web_app");

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql1 = "SELECT * FROM user_table";
    $result1 = $con->query($sql1);
    if(mysqli_num_rows($result1) > 0 )
    {
        echo "<table border = '1' cellpadding = '10'";
        echo "<tr>
                    <th>Phone_Number</th>
                    <th>Username</th>
                    <th>User_email</th>
                    <th>Password</th>
                    <th>Type</th>
            </tr>";
        while($row = mysqli_fetch_assoc($result1))
        {
            echo "<tr>
                        <td>".$row['Phone_Number']."</td>
                        <td>".$row['Username']."</td>
                        <td>".$row['User_Email']."</td>
                        <td>".$row['Password']."</td>
                        <td>".$row['type']."</td>
                    </tr>";

        }
        echo "</table>";

    }else{
            echo "Data not found";
    }
?>