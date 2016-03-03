<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Factory</title>
</head>

<body>
<p id="resources"></p>



<!--On page load retrieve all variables necessary for page usage from the database-->

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
	//Still to code: multiply hitpoint_production by unit's hp located in 'Units' table
} else {
    echo "0 results";   
}

$conn3->close();
$result3->free();

?>

<p id="demo"></p>


<script>



var myVar = setInterval(unit ,1000);
var time_offset = Date.parse(new Date());
var last_seen = '<?php echo $last_seen; ?>'; //The string Last_seen (i.e. quoted text) is being hidden within the script. Escape as done in this example for efficiency's sake. When security is of utmost importance, escape using proper character illimination.
var bool_if_result = false;
var hitpoint_production = <?php echo $hitpoint_production; ?>;

function unit(date_end) {
	


var d = Date.parse(last_seen) + hitpoint_production - Date.parse(new Date()); //Date.parse Converts all dates to miliseconds
var seconds = Math.floor((d/1000) % 60);
var minutes = Math.floor(((d/1000)/60) % 60);
var hours = Math.floor((((d/1000)/60)/60) % 24);
var days = Math.floor((((d/1000)/60)/60)/24);


if(d<=0){ //Checks if the production timer has reached zero and if so, stops the loop.

clearInterval(myVar);
document.getElementById("demo").innerHTML = "Nothing in the production chain.";
return bool_if_result=false;
} else {
	//Create unit or part of.
	
	document.getElementById("demo").innerHTML = "Production completion in: " + days + ":" + hours + ":" + minutes + ":" + seconds;
	
	}
	
	
};


</script>

<script>
document.writeln(myVar);
if(myVar==true){
	var time_elapse = Date.parse(new Date()) - Date.parse(last_seen); //Re-look above function so that it's reusable here too.
	
	
	var infantry_added=0;
	var infantry_manufactured_total=0;

	
	var hp_manufactured = (time_elapse/1000)/2; //Still to pull number of owned infantry from database.
	document.writeln("<br>" + "HP Produced in the last " + time_elapse/1000 + " seconds: " + hp_manufactured);
	var infantry_manufactured=hp_manufactured/150;
	document.writeln("<br>" + "Infantry Manufactured: " + infantry_manufactured);
	infantry_added += Math.floor(infantry_manufactured); //Initialize this or else error will occur below.
	document.writeln("<br>" + "Infantry Added: " + infantry_added);
	infantry_manufactured_total = infantry_manufactured - infantry_added;
	document.writeln("<br>" + "Infantry Manufactured Remainder: " + infantry_manufactured_total);
	
};
</script>


<form id="post_selection_to_server" method="post"> <!--Hidden form to pass calculated Javascript value to the server once an option is selected-->
    <input type="hidden" id="units_select_to_server" name="units_select_to_server"/> <!--Remember to use name here and not ID or else your form won't bind with your PHP-->
    
    <p>What would you like to produce?</p>
<a href="factory_infantry.html">Infantry</a> (<span id="ifood"></span>,<span id="igold"></span>,<span id="iwood"></span>,<span id="iiron"></span>) <a id="quarter" href="#">1/4</a>|<a id="half" href="#">2/4</a>|<a id="three_quarters" href="#">3/4</a>|<a id="max" href="#">4/4</a>

</form>

<?php
	
	$conn6 = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn6->connect_error) {
    	die("Connection failed: " . $conn6->connect_error);
	} 
	//echo "Connected successfully";

	//Prepare and bind
	$stmt = $conn6->prepare("UPDATE military_hq SET hitpoint_production=? WHERE user='" . $user . "'");
	$stmt->bind_param("d",$infantry_production); //d - Integer
	

	//Get user details/set parameters and execute
	
	if(isset($_POST["units_select_to_server"])){$infantry_production = $_POST["units_select_to_server"];} //if here avoids null error
	$stmt->execute();

	$stmt->close();
	$conn6->close();
	?>

 
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

//document.getElementById("max").setAttribute("href","factory8.php");
document.getElementById("quarter").textContent = quarter_units;
document.getElementById("half").textContent = half_units;
document.getElementById("three_quarters").textContent = three_quarters_units;
document.getElementById("max").textContent = max_units;


</script>

<script>

document.getElementById("max").setAttribute("onClick","document.getElementById('units_select_to_server').value = max_units * infantry_hp;document.getElementById('post_selection_to_server').submit();");
document.getElementById("quarter").setAttribute("onClick","document.getElementById('units_select_to_server').value = quarter_units * infantry_hp;document.getElementById('post_selection_to_server').submit();");
document.getElementById("half").setAttribute("onClick","document.getElementById('units_select_to_server').value = half_units * infantry_hp;document.getElementById('post_selection_to_server').submit();");
document.getElementById("three_quarters").setAttribute("onClick","document.getElementById('units_select_to_server').value = three_quarters_units * infantry_hp;document.getElementById('post_selection_to_server').submit();");

</script>

<?php //writes last_seen to the database on every page load.
// Create connection
$conn4 = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn4->connect_error) {
    die("Connection failed: " . $conn4->connect_error);
} 
//echo "Connected successfully";

$sqlLastSeen = "UPDATE military_hq SET last_seen='" . date("Y-m-d H:i:s"). "' WHERE user='" . $user . "'"; //Remember to change timezone in php.ini - Africa/Harare. Also, for more precision, I advise this be relooked to allow for saving of milliseconds.

$conn4->query($sqlLastSeen);

$conn4->close();


?>

</body>
</html>