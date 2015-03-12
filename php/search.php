<?php
require 'config.php';

/*
$sql = $database->prepare("select room_nr from room");
$sql->setFetchMode(PDO::FETCH_OBJ);
$sql->execute();
$room_numbers = $sql->fetch();
*/

$from = $_POST['fromDate'];
$to = $_POST['toDate'];
$projector = false;
if (isset($_POST['projector'])) $projector = true;
$size = $_POST['size'];
$email = $_POST['email'];


if($projector)
{
	$sql = $database->prepare("SELECT * FROM room WHERE size >=:size AND :projector = true AND room_nr NOT IN (SELECT room_nr FROM room_reservation WHERE :from NOT BETWEEN fromDate AND toDate OR :to NOT BETWEEN fromDate AND toDate) ORDER BY size, room_nr");
	$sql->setFetchMode(PDO::FETCH_OBJ);
	$sql->execute(array(
		'size' => $size,
		'projector' => $projector,
		'from' => $from,
		'to' => $to
	));
}
else{
	$sql = $database->prepare("SELECT * FROM room WHERE size >=:size AND room_nr NOT IN (SELECT room_nr FROM room_reservation WHERE :from NOT BETWEEN fromDate AND toDate OR :to NOT BETWEEN fromDate AND toDate) ORDER BY projector, size, room_nr");
	$sql->setFetchMode(PDO::FETCH_OBJ);
	$sql->execute(array(
		'size' => $size,
		'from' => $from,
		'to' => $to
	));
}

$sql->execute();

if (!$sql->fetch())
{
	echo '<p>Det er desverre ingen ledige rom i dette tidsrommet</p>';
}
else
{
	$i = 1;
	echo '<form id="selectRoom" name="selectRoom" action="sendConfirmationMail.php" method="post"><table><tr><th>Romnummer</th><th>Fra</th><th>Til</th><th>Projektor</th></tr>';
	while ($possibleRooms = $sql->fetch())
	{
		$proj = 'Nei';
		if ($sql->projector = true) $proj = 'Ja';
		echo '<input type="radio" name="option" value="' . $i . '" required><tr><td>' . $possibleRooms->room_nr . '</td><td>' . $possibleRooms->fromDate . '</td><td>' . $possibleRooms->toDate . '</td><td>' . $proj . '</td></tr>';
		$i++;
	}
	echo '</table></form>';
}



/* todo: code to retrieve rooms available in given period */