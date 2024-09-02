
<?php
$conn = mysqli_connect("localhost","root","Aa1@monu","schooldb");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

?>
