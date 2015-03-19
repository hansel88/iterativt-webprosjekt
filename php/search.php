<?php
session_start();
require 'config.php';
require 'header.php';

?>
		<section id="wrapper">
			<h1>Velg tidspunkt</h1>
<?php

$from = $_POST['date'];
$to = $from;//++ legge til så det blir på slutten av dagen
$hours = $_POST['hours'];
$size = $_POST['size'];

echo 'hours: ' .  $hours;
echo 'size: ' .  $size;
echo 'date from --> ' . $from;
//$email = $_POST['email'];

/*
echo '<p> ballefrans</p>';
$faen = 'start:' . $from . ' end: ' . $to;
echo $faen;
*/
class availableTime
{
	public $available;
    public $time = "";
    public $room_id = "";
}

//$_SESSION['fromDate'] = $from;
//$_SESSION['toDate'] = $to; //fromDate + hours
//$_SESSION['email'] = $email;

if(isset($_POST['projector']) && $_POST['projector'] == 'yes')
{
	$rooms = $database->prepare("SELECT * FROM room WHERE size >= :size AND projector = 1 ORDER BY size, room_nr");
} 
else 
{
	$rooms = $database->prepare("SELECT * FROM room WHERE size >= :size ORDER BY size, room_nr");
}

$rooms->setFetchMode(PDO::FETCH_OBJ);
$rooms->execute(array(
	'size' => $size
));

$possibleRooms = array();
$possibleRoomIds = array();
while ($room = $rooms->fetch())
{
	array_push($possibleRooms, $room);
	array_push($possibleRoomIds, $room->room_nr);
}

//NB HUSK Å BRUKE DENNE KODEN TIL SLUTT
/*
$reservations = $database->prepare("SELECT * FROM room_reservation WHERE confirmed = 1 AND fromDate BETWEEN :fromDate AND :toDate AND room_nr IN (:possibleRoomIds) ORDER BY room_nr");
$reservations->setFetchMode(PDO::FETCH_OBJ);
$reservations->execute(array(
	'fromDate' => $from,
	'toDate' => $to,
	'possibleRoomIds' => join(',', array_filter($possibleRoomIds))//implode(", " ,$possibleRoomIds)
));
*/
$reservations = $database->prepare("SELECT * FROM room_reservation WHERE room_nr IN (:possibleRoomIds) ORDER BY room_nr");
$reservations->setFetchMode(PDO::FETCH_OBJ);
$reservations->execute(array(
	'possibleRoomIds' => join(',', array_filter($possibleRoomIds))//implode(", " ,$possibleRoomIds)
));

$reservationsOnChosenDay = array();
while ($reservation = $reservations->fetch())
{
	array_push($reservationsOnChosenDay, $reservation);
}

//Used to get the full picture of available times 
$availableTimes = array();
for ($x = 0; $x <= 12; $x++) {
    $obj = new availableTime();
	$obj->time = '' . ($x+8);
	$obj->available = false;
	array_push($availableTimes, $obj);
}  

//Used for each room when checking against reservations
$availableTimesForRoom = array();
for ($x = 0; $x <= 12; $x++) {
    $obj = new availableTime();
	$obj->time = '' . ($x+8);
	$obj->available = true;
	array_push($availableTimesForRoom, $obj);
} 


// loope igjennom alle rom/reservasjoner. Alle steder der det er <hours> timer ledig i strekk, gjøre available til true i tilsvarende tidspunkter i availableTimes-arrayet.
foreach ($possibleRooms as &$room) {
	echo 'room..';
    $hasReservations = false;

    $_availableTimesForRoom = $availableTimesForRoom; //copy

    foreach ($reservationsOnChosenDay as &$reservation) {
    	echo 'reservation...';
    	if($reservation->room_nr == $room->room_nr)
    	{
    		$hasReservations = true;

			$fromHour = $reservation->fromTime;
			$toHour = $reservation->toTime;

			for ($x = intval($fromHour); $x <= intval($toHour); $x++) { 
				$_availableTimesForRoom[$x-8]->available = false;
			}
    	}
	}

	if(!$hasReservations)
	{
		for ($x = 0; $x <= 12; $x++) {
			$availableTimes[$x]->available = true;
			$availableTimes[$x]->room_id = $room->room_nr;
		} 
		break; //Jumps out of loop as this room has available time all day (and thats all we need, eh? ;)
	}
	else
	{
		$count = 0;
		for ($x = 0; $x <= 12; $x++) {
			if($_availableTimesForRoom[$x]->available == true) 
			{
				$count++;
				if($count == $hours)
				{
					echo'count er hours';
					for($z = 0; $z < $count; $z++)
					{
						$availableTimes[$x - $z]->available = true;
						$availableTimes[$x - $z]->room_id = $room->room_nr;
					}
				}
				else if($count > $hours) //over $hours på rad
				{
					$availableTimes[$x]->available = true;
					$availableTimes[$x]->room_id = $room->room_nr;

				}
			}
			else
			{
				echo 'count settes til 0 igjen';
				$count = 0;
			}
		} 
	}
}//$_time->room_id

echo '<div id="timeListContainer"><ul id="timeList">';
	
foreach($availableTimes as $_time )
{
	if($_time->available)	
	{
		echo '<li><input id="timeInput' .$_time->time.'" type="button" onclick="testMethod(' . $_time->time . ',' . $hours . ',' . $_time->room_id . ')" class="greenTime" value="' . $_time->time . '"/></li>';
	}
	else
	{
		echo '<li><input id="timeInput' .$_time->time.'" type="button" onclick="showError()" class="redTime" value="' . $_time->time . '"/></li>';
	}
}

echo '</ul></div>';

echo '<p id="infoText"></p> <br />';

echo '<form method="post" action="sendConfirmationMail.php" class="pure-form pure-form-aligned" id="mailForm"><div class="pure-control-group" style=""><label for="email">Epost:</label><input id="email" type="email" name="email" size="25" pattern=".+@student.westerdals.no" title="@student.westerdals.no" placeholder="bruker@student.westerdals.no" required></div>';
	
echo '<input type="text" style="display: none;" id="fromTime" name="fromTime" /> <input style="display: none;"  type="text" id="toTime" name="toTime" /> <input style="display: none;"  type="text" id="room" name="room" /> <input style="display: none;"  type="date" name="date" value="' . $from . '"/>';
echo '<button id="chooseRoomSubmit" type="submit" class="pure-button pure-button-primary">Book</button></form>';					
//echo '<button id="chooseRoomSubmit" onclick="book(\'' .$from .'\','.$hours.')" class="pure-button pure-button-primary">Book</button></form>';







/*
if(isset($_POST['projector']) && $_POST['projector'] == 'yes')
{
	$sql = $database->prepare("SELECT * FROM room WHERE size >= :size AND projector = 1 AND room_nr NOT IN (SELECT room_nr FROM room_reservation WHERE :from NOT BETWEEN fromDate AND DATE_ADD(fromDate,INTERVAL :hours HOUR) OR :to NOT BETWEEN fromDate AND toDate) ORDER BY size, room_nr");
} else {
	$sql = $database->prepare("SELECT * FROM room WHERE size >= :size AND room_nr NOT IN (SELECT room_nr FROM room_reservation WHERE :from NOT BETWEEN fromDate AND toDate OR :to NOT BETWEEN fromDate AND toDate) ORDER BY projector, size, room_nr");
}

$sql->setFetchMode(PDO::FETCH_OBJ);
$sql->execute(array(
	'size' => $size,
	'from' => $from,
	'hours' => $hours
));

$possibleRooms = $sql->fetch();
*/

/*
if (!$possibleRooms)
{
	echo '<p>Det er desverre ingen ledige rom i dette tidsrommet</p>';
}
else
{
	echo '<form id="selectRoom" name="selectRoom" action="sendConfirmationMail.php" method="post"><table><tr><th>Velg</th><th>Romnr</th><th>Dato</th><th>Fra</th><th>Til</th><th>Projektor</th></tr>';
	do
	{
			$proj = 'Nei';
		if ($possibleRooms->projector == 1) $proj = 'Ja';
		echo '<tr><td><input type="radio" name="room" value="' . $possibleRooms->room_nr . '" required></td><td>' . $possibleRooms->room_nr . '</td><td>' . substr($from, 0, 10) . '</td><td>' . substr($from, -5) . '</td><td>' . substr($to, -5) . '</td><td>' . $proj . '</td></tr>';
	} while ($possibleRooms = $rooms->fetch());
	echo '</table><button id="chooseRoomSubmit" type="submit" class="pure-button pure-button-primary">Velg rom</button></form>';
}
*/

?>
</section> 

<script src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/search.js"></script>

<?php require 'footer.php'; ?>

