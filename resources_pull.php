<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php //Database pull for a specific user's available resources

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "seed";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
//echo "Connected successfully";

$user = "cloud";
$sqlRes = "SELECT * FROM resources " . "WHERE user='" . $user . "'";

$result = $conn->query($sqlRes);


if ($result->num_rows > 0) {
	
	$row = $result->fetch_assoc(); //Converts mysqli_result object to array
	
        $food = $row['food'];
		$gold = $row['gold'];
		$wood = $row['wood'];
		$iron = $row['iron'];
		
    
} else {
    echo "0 results";   
}

$conn->close();
$result->free();

?>
</body>
</html>