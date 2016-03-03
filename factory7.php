<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Factory</title>
</head>

<body>
<p id="resources"></p>

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

<?php

$conn2 = new mysqli($servername, $username, $password, $dbname);
$sqlUnits = "SELECT * FROM units ";

$result2 = $conn2->query($sqlUnits);


if ($result2->num_rows > 0) {
	
	$row = $result2->fetch_assoc(); //Converts mysqli_result object to array
	
        $infantry_food = $row['food'];
		$infantry_gold = $row['gold'];
		$infantry_wood = $row['wood'];
		$infantry_iron = $row['iron'];
		$infantry_hp = $row['hp'];
		
		
    
} else {
    echo "0 results";   
}

$conn2->close();
$result2->free();

?>

<?php //Write last seen to database in order to calculate the number of units produced.

// Create connection
$conn3 = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn3->connect_error) {
    die("Connection failed: " . $conn3->connect_error);
} 
//echo "Connected successfully";

$user = "cloud";
$sqlMil = "SELECT * FROM military_hq " . "WHERE user='" . $user . "'";

$result3 = $conn3->query($sqlMil);


if ($result3->num_rows > 0) {
	
	$row = $result3->fetch_assoc(); //Converts mysqli_result object to array
	
    $last_seen = $row['last_seen'];
	$hitpoint_production = ($row['hitpoint_production'] * (2*1000)); //1 hitpoint takes 2 seconds to produce. That's 150 hitpoints in 5 minutes.
	
} else {
    echo "0 results";   
}

$conn3->close();
$result3->free();

?>

<p id="demo"></p>


<script>

var last_seen = '<?php echo $last_seen; ?>'; //The string Last_seen (i.e. quoted text) is being hidden within the script. Escape as done in this example for efficiency's sake. When security is of utmost importance, escape using proper character illimination.
var hitpoint_production = <?php echo $hitpoint_production; ?>;

var myVar = setInterval(unit ,1000);
var time_offset = Date.parse(new Date());


function unit(date_end) {
	
var d = Date.parse(last_seen) + hitpoint_production - Date.parse(new Date());
var seconds = Math.floor((d/1000) % 60);
var minutes = Math.floor(((d/1000)/60) % 60);
var hours = Math.floor((((d/1000)/60)/60) % 24);
var days = Math.floor((((d/1000)/60)/60)/24);

document.getElementById("demo").innerHTML = "Production completion in: " + days + ":" + hours + ":" + minutes + ":" + seconds;

if(d<=0){ //Checks if the production timer has reached zero and if so, stops the loop.

clearInterval(myVar);
document.getElementById("demo").innerHTML = "Nothing in the production chain.";
}

};


var time_elapse = Date.parse(last_seen) - Date.parse(new Date()); //Re-look above function so that it's reusable here too.


</script>


</script>


<p>What would you like to produce?</p>
<a href="factory_infantry.html">Infantry</a> (<span id="ifood"></span>,<span id="igold"></span>,<span id="iwood"></span>,<span id="iiron"></span>) <a id="quarter" href="factory_infantry.html">1/4</a>|<a id="half" href="factory_infantry.html">2/4</a>|<a id="three_quarters" href="factory_infantry.html">3/4</a>|<a id="max" href="www.facebook.com">4/4</a>

 

 
<script>


var food = <?php echo $food; ?>; 
var gold = <?php echo $gold; ?>; <!--these variables could be optomized by using an array 'resources()'-->
var wood = <?php echo $wood; ?>;
var iron = <?php echo $iron; ?>;

document.getElementById("resources").innerHTML = "Food: " + food + " | " + "Gold: " + gold + " | " + "Wood: " + wood + " | " + "Iron: " + iron;

var infantry_food = <?php echo $infantry_food; ?>;
var infantry_gold = <?php echo $infantry_gold; ?>;
var infantry_wood = <?php echo $infantry_wood; ?>;
var infantry_iron = <?php echo $infantry_iron; ?>;
var infantry_hp = <?php echo $infantry_hp; ?>;

document.getElementById("ifood").innerHTML = infantry_food;
document.getElementById("igold").innerHTML = infantry_gold;
document.getElementById("iwood").innerHTML = infantry_wood;
document.getElementById("iiron").innerHTML = infantry_iron;

var max_units;
var quarter_units;
var half_units;
var three_quarters_units;



function unit_production() {
max_units = Math.floor(Math.min(food / infantry_food,gold / infantry_gold,wood / infantry_wood,iron / infantry_iron));
quarter_units = Math.floor(max_units / 4);
half_units = Math.floor(max_units / 2);
three_quarters_units = (quarter_units * 3);

	return max_units;

}

unit_production()

document.getElementById("max").setAttribute("href","2.html" + max_units);
document.getElementById("quarter").textContent = quarter_units;
document.getElementById("half").textContent = half_units;
document.getElementById("three_quarters").textContent = three_quarters_units;
document.getElementById("max").textContent = max_units;


</script>



<?php //writes last_seen to the database on every page load.
// Create connection
$conn4 = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn4->connect_error) {
    die("Connection failed: " . $conn4->connect_error);
} 
//echo "Connected successfully";

$sqlLastSeen = "UPDATE military_hq SET last_seen='" . date("Y-m-d H:i:s"). "' WHERE user='" . $user . "'"; //Remember to change timezone in php.ini - Africa/Harare

$conn4->query($sqlLastSeen);

$conn4->close();


?>

<form> <!--Hidden form to pass calculated Javascript value to the server once an option is selected-->
    <input type="text" id="units_select_to_server" name="foo" />
    
</form>

<script>
document.getElementById("foo").value = yourNewValue;
</script>

<script> //Write script to write to the database on click

function unit_production_selection(unit_amount) { //This won't work unless I send the Javascript on the page back to the server. This may be done on the page reload.
	
	<?php
	
	$conn5 = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn5->connect_error) {
    	die("Connection failed: " . $conn5->connect_error);
	} 
	//echo "Connected successfully";

	$sqlUnitProduction = "UPDATE military_hq SET infantry_production='" . unit_amount . "' WHERE user='" . $user . "'";

	$conn5->query($sqlUnitProduction);

	$conn5->close();
	?>
}


document.getElementById("max").setAttribute("onClick","unit_production_selection(max_units)");


</script>


</body>
</html>