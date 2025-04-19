<?php
    //Build connection
    $con = mysqli_connect("localhost","root","","web_app");
    //check connection
    if(!$con){
        die("Conncetion failed:".mysqli_conncet_error());
    }

    function user($len = 6)
    {
        return substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),0,$len);
    }

    function phone()
    {
        return "91".rand(100000000,9999999999);
    }

    function address()
    {
        $add = ['University','akashvani','raiya road','indira circle'];
        return $add[array_rand($add)].rand(1000,9999);
    }

    function gender()
    {
        $gender = ["Male","Female","Others"];
        return $gender[array_rand($gender)];
    }

    function dob($startYear = 2000,$endYear = 2025)
    {
        $start = strtotime("$startYear-01-01");
        $end = strtotime("$endYear-01-01");

        return date("y-m-d",rand($start,$end));
    }

    for($i = 0;$i < 100;$i++)
    {
        //for new account
        $username = 'user_'.user(5);
        $email = $username . '@gmail.com';
        $phone = phone();
        $password = password_hash('pass' . rand(1000, 9999), PASSWORD_DEFAULT);


        //for user info
        $address = address();
        $age = rand(19,25);
        $gender = gender();
        $dob = dob();

        $stmt1 = "INSERT INTO user_table (Phone_Number,Username,User_email,Password) VALUES ('$phone','$username','$email','$password')";
        $result = $con->query($stmt1); 
        $stmt2 = "INSERT INTO user_info_table (Address,Age,Gender,DOB) VALUES ('$address','$age','$gender','$dob')";
        $result2 = $con->query($stmt2);
    }
    echo "<h3>Insertion Done</h3><br>"
?>