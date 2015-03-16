<?php
session_start();
require 'config.php';
require 'header.php';

?>
		<section id="wrapper">
			<h1>Velg tidspunkt</h1>
<?php
$from = $_POST['fromDate'];
$to = $_POST['toDate'];
//$hours = $_POST['hours'];
$size = $_POST['size'];
$email = $_POST['email'];

/*
echo '<p> ballefrans</p>';
$faen = 'start:' . $from . ' end: ' . $to;
echo $faen;
*/

$_SESSION['fromDate'] = $from;
$_SESSION['toDate'] = $to; //fromDate + hours
$_SESSION['email'] = $email;


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
	} while ($possibleRooms = $sql->fetch());
	echo '</table><button id="chooseRoomSubmit" type="submit" class="pure-button pure-button-primary">Velg rom</button></form>';
}
?>
</section>


<?php require 'footer.php'; ?>