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
echo $from ;
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
}

$_SESSION['fromDate'] = $from;
$_SESSION['toDate'] = $to; //fromDate + hours
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

echo '!!! ' . join(', ', array_filter($possibleRoomIds));

$reservations = $database->prepare("SELECT * FROM room_reservation WHERE confirmed = 1 AND fromDate BETWEEN :fromDate AND :toDate AND room_nr IN (:possibleRoomIds) ORDER BY room_nr");
$reservations->setFetchMode(PDO::FETCH_OBJ);
$reservations->execute(array(
	'fromDate' => $from,
	'toDate' => $to,
	'possibleRoomIds' => join(',', array_filter($possibleRoomIds))//implode(", " ,$possibleRoomIds)
));
/*
$reservations = $database->prepare("SELECT * FROM room_reservation WHERE room_nr IN (:possibleRoomIds) ORDER BY room_nr");
$reservations->setFetchMode(PDO::FETCH_OBJ);
$reservations->execute(array(
	'possibleRoomIds' => join(',', array_filter($possibleRoomIds))//implode(", " ,$possibleRoomIds)
));
*/
$reservationsOnChosenDay = array();
while ($reservation = $reservations->fetch())
{
	array_push($reservationsOnChosenDay, $reservation);
}

echo 'reservations on chosen day: ';
print_r($reservationsOnChosenDay);

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


//TODO here: loope igjennom alle rom/reservasjoner. Alle steder der det er <hours> timer ledig i strekk, gjøre available til true i tilsvarende tidspunkter i availableTimes-arrayet.
foreach ($possibleRooms as &$room) {
	echo 'room..';
    $hasReservations = false;

    $_availableTimesForRoom = $availableTimesForRoom; //copy

    foreach ($reservationsOnChosenDay as &$reservation) {
    	echo 'reservation...';
    	if($reservation->room_nr == $room->room_nr)
    	{
    		$hasReservations = true;
			//$from = date_create($reservation->fromDate);
			$from = date("H", strtotime($reservation->fromDate));
			$to = date("H", strtotime($reservation->toDate));

			for ($x =  intval($from); $x <= intval($to); $x++) { 
				$_availableTimesForRoom[$x-8]->available = false;
			}
    	}
	}

	if(!$hasReservations)
	{
		for ($x = 0; $x <= 12; $x++) {
			$availableTimes[$x]->available = true;
		} 
		break; //Jumps out of loop as this room has available time all day (and thats all we need, eh? ;)
	}
	else
	{
		$temp = $availableTimes;
		$count = 0;
		for ($x = 0; $x <= 12; $x++) {
			if($_availableTimesForRoom[$x] == true) //TODO: legg til logikk for å sjekke at det er x antall timer ledige i strekk
			{
				$count++;
				if(count == $hours)
				{
					for($z = 0; $z <= $count; $z++)
					{
						$availableTimes[$x - $z]->available = true;
					}
				}
				else //over $hours på rad
				{
					$availableTimes[$x]->available = true;
				}
			}
			else
			{
				$count = 0;
			}
		} 

		/*
		for ($x = 0; $x <= 12; $x++) {
			if($_availableTimesForRoom[$x] == true) //TODO: legg til logikk for å sjekke at det er x antall timer ledige i strekk
			{
				$availableTimes[$x]->available = true;
			}
		} 
		*/
	}
}

echo '<div id="timeListContainer"><ul id="timeList">';

foreach($availableTimes as $time )
{
	if($time->available)
	{
		echo '<li><input type="button" onclick="testMethod(' . $time->time . ')" class="greenTime" value="' . $time->time . '"/></li>';
	}
	else
	{
		echo '<li><input type="button" onclick="showError()" class="redTime" value="' . $time->time . '"/></li>';
	}
}

echo '</ul></div>';






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

<script>
	testMethod = function(k)
	{
		alert(k);
	}

	showError = function()
	{
		alert('Ikke et gyldig tidspunkt');
	}
</script>

<?php require 'footer.php'; ?>