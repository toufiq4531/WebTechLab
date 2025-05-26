<?php
    session_start();
    if(!isset($_SESSION["signin_email"])) {
        header("Location: index.html");
        exit();
    }

$bg_color = isset($_COOKIE['bg_color']) ? $_COOKIE['bg_color'] : '#f4f4f4';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Cities</title>
    <style>
        body{
            background-image: url('cloudy_1.gif');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        #option {
            display: flex;
            justify-content: center;
        }
        #head {
            text-align: center;
            margin-bottom: 20px;
        }
        .checkbox-container {
            background-color: rgb(180, 201, 201);
            padding: 20px;
            margin: -130px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .checkbox-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 300px;
            margin-bottom: 10px;
            font-family: Arial, sans-serif;
            padding: 5px;
        }
        .checkbox-row:hover {
            background-color: rgba(255,255,255,0.3);
        }
        input[type="submit"] {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div id="head">
        <h1>Select 10 Cities for AQI Comparison</h1>
    </div>
    <div style='text-align: right; padding: 10px 20px;'>
        <form action='logout.php' method='post' style='display:inline;'>
        <input type='submit' value='Logout' style='background-color:#f44336; color:white; padding:8px 16px; border:none; border-radius:5px; cursor:pointer; font-weight:bold;'>
        </form>
    </div>
    <?php
        echo "<div style='text-align: right; padding: 10px'>
        <h2>" . $_SESSION['signin_email'] . "</h2>
          </div>";

    ?>
    <div id="option">
    <?php
    $con = mysqli_connect("localhost", "root", "", "aqi"); 

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT City, Country FROM aqi_table ORDER BY City ASC";
    $obj = mysqli_query($con, $sql);

    echo "<form action='show_aqi.php' method='post' class='checkbox-container'>";
    if (mysqli_num_rows($obj) > 0) {
        $i = 1;
        while ($entry = mysqli_fetch_assoc($obj)) {
            echo "<div class='checkbox-row'>
                    <span>" . $i . ". " . htmlspecialchars($entry['City']) . ", " . htmlspecialchars($entry['Country']) . "</span>
                    <input type='checkbox' value='" . htmlspecialchars($entry['City']) . "' class='checkbox' name='checkbox[]'>
                  </div>";
            $i++;
        }
    } else {
        echo "<p>No cities found in database.</p>";
    }

    echo "<div style='text-align: center;'>
            <input type='submit' value='Submit' style='border-radius: 5px; padding: 10px; font-weight: bold'>
          </div></form>";

    mysqli_close($con);
    ?>
    </div>
</body>
</html>