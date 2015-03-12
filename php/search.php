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
$projector = $_POST['projector'];	
$size = $_POST['size'];
$email = $_POST['email'];


if($projector)
{
	$sql = $database->prepare("select * from room where size >=:size and :projector = true");
	$sql->setFetchMode(PDO::FETCH_OBJ);
	$sql->execute(array(
		'size' => $size,
		'projector' => $projector
	));
}
else{
	$sql = $database->prepare("select * from room where size >=:size");
	$sql->setFetchMode(PDO::FETCH_OBJ);
	$sql->execute(array(
		'size' => $size,
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