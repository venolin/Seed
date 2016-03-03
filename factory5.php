<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Factory</title>
</head>

<body>

<?php
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

$sql = "SELECT * FROM resources";
$result = $conn->query($sql);

$dServer = array();

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Food: " . $row["food"] . " | Gold: " . $row["gold"]. " | Wood: " . $row["wood"]. " | Iron: " . $row["iron"]."<br>";
    }
} else {
    echo "0 results";   
}

$conn->close();

?>


<p id="demo"></p>


<script>

var myVar = setInterval(unit ,1000);
var prod_complete = '2015-12-31 5:16:00'

function unit(date_end) {
	
var d = Date.parse(prod_complete) - Date.parse(new Date());
var seconds = Math.floor((d/1000) % 60);
var minutes = Math.floor(((d/1000)/60) % 60);
var hours = Math.floor((((d/1000)/60)/60) % 24);
var days = Math.floor((((d/1000)/60)/60)/24);

document.getElementById("demo").innerHTML = "Production completion in: " + days + ":" + hours + ":" + minutes + ":" + seconds;

};

</script>



<p>What would you like to produce?</p>
<a href="factory_infantry.html">Infantry</a> (3,2,1,1) <a id="quarter" href="factory_infantry.html">1/4</a>|<a id="half" href="factory_infantry.html">2/4</a>|<a id="three_quarters" href="factory_infantry.html">3/4</a>|<a id="max" href="www.facebook.com">4/4</a>



 
<script>
var food = <?php echo $dServer["food"]; ?>; <!--all these variables should be obtained from the database-->
var gold = 1000; <!--these variables could be optomized by using an array 'resources()'-->
var wood = 1000;
var iron = 1000;



var infantry_food = 3;
var infantry_gold = 2;
var infantry_wood = 1;
var infantry_iron = 1;

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
</body>
</html>