<?php

echo "Name: ".$_POST['fname']."<br>";
echo "Email: ".$_POST['email']."<br>";
echo "Gender: ".$_POST['gender']."<br>";
echo "Date of Birth: ".$_POST['DOB']."<br>";
echo "Country: ".$_POST['country']."<br>";
echo "Fav Color: ".$_POST['favcolor']."<br>";
echo "Feedback: ".$_POST['feedback']."<br>";


//echo "Hi ".$_POST['uname']; // ASSOCIATIVE ARRAY K-V  - SUPERGLOBAL ARRAY
// echo "<br>".$_POST['email'];
// echo "<br>".$_GET['uname'];

//var_dump($_GET);
// if (isset($_POST['submit'])) {

// if ($_POST['uname'] != "") {
// echo $_POST['uname'];
// }
// else
//     print_r("NO DATA");

// }
// else
//     print_r("NO DATA");

?>