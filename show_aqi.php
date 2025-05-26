<?php
session_start();
if(!isset($_SESSION["signin_email"])) {
    header("Location: index.html");
    exit();
}

$bg_color = isset($_COOKIE['bg_color']) ? $_COOKIE['bg_color'] : '#f4f4f4';

if (!isset($_POST['checkbox'])) {
    echo "<h3 style='color:red; text-align: center;'>No cities selected. You will be redirected back shortly...</h3>";
    header("Refresh: 2; url=" . $_SERVER['HTTP_REFERER']);
    exit();
}

$selected = $_POST['checkbox'];
$count = count($selected);

if ($count !== 10) {
    echo "<h3 style='color:red; text-align: center;'>Please select exactly 10 cities. You will be redirected back shortly...</h3>";
    header("Refresh: 2; url=" . $_SERVER['HTTP_REFERER']);
    exit();
}

$con = mysqli_connect("localhost", "root", "", "aqi"); 

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Output HTML and CSS
echo "
<!DOCTYPE html>
<html>
<head>
    <title>AQI Comparison</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: $bg_color;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: rgba(180, 201, 201, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: white;
            color: #333;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .aqi-low {
            color: green;
            font-weight: bold;
        }
        .aqi-moderate {
            color: orange;
            font-weight: bold;
        }
        .aqi-high {
            color: red;
            font-weight: bold;
        }
        .user-info {
            text-align: right;
            padding: 10px 20px;
            background-color: rgba(255,255,255,0.7);
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>";

// User info and logout button
echo "<div class='user-info'>
        <span style='margin-right: 20px;'>Logged in as: " . htmlspecialchars($_SESSION['signin_email']) . "</span>
        <form action='logout.php' method='post' style='display:inline;'>
            <input type='submit' value='Logout' style='background-color:#f44336; color:white; padding:8px 16px; border:none; border-radius:5px; cursor:pointer; font-weight:bold;'>
        </form>
      </div>";

// AQI Table
echo "<div class='container'>
        <h1>AQI Comparison of Selected Cities</h1>
        <table>
            <tr>
                <th>City</th>
                <th>Country</th>
                <th>AQI</th>
                <th>Status</th>
            </tr>";

// Process each selected city
foreach ($selected as $city) {
    $cityEscaped = mysqli_real_escape_string($con, $city);
    $sql = "SELECT City, Country, AQI FROM aqi_table WHERE City = '$cityEscaped'";
    $result = mysqli_query($con, $sql);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    while ($entry = mysqli_fetch_assoc($result)) {
        $aqiClass = '';
        $status = '';
        
        if ($entry['AQI'] <= 50) {
            $aqiClass = 'aqi-low';
            $status = 'Good';
        } elseif ($entry['AQI'] <= 100) {
            $aqiClass = 'aqi-moderate';
            $status = 'Moderate';
        } else {
            $aqiClass = 'aqi-high';
            $status = 'Unhealthy';
        }

        echo "<tr>
                <td>" . htmlspecialchars($entry['City']) . "</td>
                <td>" . htmlspecialchars($entry['Country']) . "</td>
                <td class='$aqiClass'>" . htmlspecialchars($entry['AQI']) . "</td>
                <td class='$aqiClass'>$status</td>
              </tr>";
    }
}

echo "      </table>
      </div>
</body>
</html>";

mysqli_close($con);
?>