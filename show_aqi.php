<?php
if (isset($_POST['checkbox'])) {
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

    echo "
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
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
            background-color: rgb(194, 194, 229);
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
    </style>";

    echo "<div class='container'>
            <h1>AQI Comparison of Selected Cities</h1>
            <table>
                <tr>
                    <th>City</th>
                    <th>Country</th>
                    <th>AQI</th>
                    <th>Status</th>
                </tr>";

    foreach ($selected as $city) {
        $cityEscaped = mysqli_real_escape_string($con, $city);
        $sql = "SELECT City, Country, AQI FROM aqi_table WHERE City = '$cityEscaped'";
        $obj = mysqli_query($con, $sql);

        while ($entry = mysqli_fetch_assoc($obj)) {
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

            echo "<tr>";
            echo "<td>" . htmlspecialchars($entry['City']) . "</td>";
            echo "<td>" . htmlspecialchars($entry['Country']) . "</td>";
            echo "<td class='$aqiClass'>" . htmlspecialchars($entry['AQI']) . "</td>";
            echo "<td class='$aqiClass'>$status</td>";
            echo "</tr>";
        }
    }

    echo "</table></div>";

    mysqli_close($con);

} else {
    echo "<h3 style='color:red; text-align: center;'>No cities selected. You will be redirected back shortly...</h3>";
    header("Refresh: 2; url=" . $_SERVER['HTTP_REFERER']);
    exit();
}
?>