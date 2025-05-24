<?php
session_start();
    if (session_status() >= 0){
        if(isset($_SESSION["signin_email"])) {
            header("refresh: 0.5; url = request.php");
        }
    }

    if(isset($_POST["submit"])) {
        $signInEmail = $_POST["signin_email"];
        $signInPass = $_POST["signin_pass"];
    }

    $conn = mysqli_connect('localhost', 'root', '', 'aqi');
    $sql = "SELECT * FROM user WHERE email = '$signInEmail' and password = '$signInPass'";
    
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $count = mysqli_num_rows($result);

    if($count == 1){
        // session_start();
        $username = $row['user_fname'];
        $_SESSION["username"] = $username;
        $_SESSION["signin_email"] = $signInEmail;

        echo "You are now redirected";
        header("refresh: 2; url = request.php");
        exit();
    }
    else{
        echo "User not found";
        header("refresh: 2; url = index.html");
        exit();
    }
    if(!isset($_POST["submit"])){
        echo "Fill the email and password."."<br>";
        header("refresh: 2; url = index.html");
    }

?>