<?php
function connectDB() {
    $con = mysqli_connect("localhost", "root", "", "aqi");
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $con;
}

// Handle form submission (first request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['action'])) {
    // Store all form data in variables
    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $password = $_POST['pass'];
    $dob = $_POST['DOB'];
    $country = $_POST['country'];
    $favcolor = $_POST['favcolor'];
    $feedback = $_POST['feedback'];
    
    // Set the background color cookie
    setcookie('bg_color', $favcolor, time() + (30 * 24 * 60 * 60), '/');
    
    // Display confirmation page with all data as hidden fields
    echo 
    "<html>
    <head>
        <title>Confirm Registration</title>
        <style>
            body{     
                background-image: url('cloudy_1.gif');
                background-size: cover;
                background-attachment: fixed;
                background-position: center;
                background-repeat: no-repeat;
                position: relative;
            }

            .confirmation-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                margin-top: 50px;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 10px;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
                background-color: #f9f9f9;
            }
            .confirmation-details {
                width: 100%;
                margin-bottom: 20px;
            }
            .confirmation-details p {
                margin: 10px 0;
                padding: 5px;
                border-bottom: 1px solid #eee;
            }
            .button-group {
                display: flex;
                gap: 20px;
                margin-top: 20px;
            }
            .confirm-btn, .cancel-btn {
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-weight: bold;
                text-decoration: none;
                display: inline-block;
                text-align: center;
            }
            .confirm-btn {
                background-color: #4CAF50;
                color: white;
            }
            .cancel-btn {
                background-color: #f44336;
                color: white;
            }
            .confirm-btn:hover {
                background-color: #45a049;
            }
            .cancel-btn:hover {
                background-color: #d32f2f;
            }
        </style>
    </head>
    <body>
        <div class='confirmation-container'>
            <h2>Please confirm your registration details</h2>
            <div class='confirmation-details'>
                <p><strong>Full Name:</strong> $fname</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Gender:</strong> $gender</p>
                <p><strong>Date of Birth:</strong> $dob</p>
                <p><strong>Country:</strong> $country</p>
                <p><strong>Favorite Color:</strong> $favcolor</p>
                <p><strong>Feedback:</strong> $feedback</p>
            </div>
            <form method='post' action='process.php?action=confirm'>
                <input type='hidden' name='fname' value='".htmlspecialchars($fname)."'>
                <input type='hidden' name='email' value='".htmlspecialchars($email)."'>
                <input type='hidden' name='gender' value='".htmlspecialchars($gender)."'>
                <input type='hidden' name='pass' value='".htmlspecialchars($password)."'>
                <input type='hidden' name='DOB' value='".htmlspecialchars($dob)."'>
                <input type='hidden' name='country' value='".htmlspecialchars($country)."'>
                <input type='hidden' name='favcolor' value='".htmlspecialchars($favcolor)."'>
                <input type='hidden' name='feedback' value='".htmlspecialchars($feedback)."'>
                <div class='button-group'>
                    <button type='submit' class='confirm-btn'>Confirm</button>
                    <a href='index.html' class='cancel-btn'>Cancel</a>
                </div>
            </form>
        </div>
    </body>
    </html>";
    exit();
}

// Handle confirmation
if (isset($_GET['action']) && $_GET['action'] === 'confirm') {
    $con = connectDB();
    
    // Check for duplicate email
    $email = $_POST['email'];
    $checkEmailQuery = "SELECT * FROM user WHERE email = ?";
    $stmt = $con->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<div style='text-align: center; margin-top: 100px; color: red;'>
                This email is already registered.
                <br><br><a href='index.html'>Go back</a>
              </div>";
    } else {
        
        // Insert new user
        $insertQuery = "INSERT INTO user (user_fname, email, gender, password, DOB, country, feedback) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($insertQuery);
        
        $stmt->bind_param("sssssss", 
            $_POST['fname'],
            $_POST['email'],
            $_POST['gender'],
            $_POST['pass'],
            $_POST['DOB'],
            $_POST['country'],
            $_POST['feedback']
        );
        
        if ($stmt->execute()) {
        // Redirect to index.html after 2 seconds
            header("Refresh: 2; url=index.html");
            echo "<div style='text-align: center; margin-top: 100px; color: green;'>
            Registration successful! Redirecting to sign in page...
                </div>";
        } else {
            echo "<div style='text-align: center; margin-top: 100px; color: red;'>
            Error: ".$stmt->error."
            <br><br><a href='index.html'>Go back</a>
                </div>";
        }
    }

    $stmt->close();
    $con->close();
    exit();
}
?>