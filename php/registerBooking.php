<?php
require 'config.php';

/*
$sql = $database->prepare("select room_nr from room");
$sql->setFetchMode(PDO::FETCH_OBJ);
$sql->execute();
$room_numbers = $sql->fetch();
*/
$from = $_GET['fromDate'];
$to = $_GET['toDate'];
$projector = $_GET['projector'];
$size = $_GET['size'];
$email = $_GET['email'];

if($projector)
{
	$sql = $database->prepare("select * from room where size >=:size and :projector = true");
	$sql->setFetchMode(PDO::FETCH_OBJ);
	'size' => $size,
	'projector' => $projector
));
}
else{
	$sql = $database->prepare("select * from room where size >=:size");
	$sql->setFetchMode(PDO::FETCH_OBJ);
	'size' => size
));
}

$possibleRooms = $sql->fetch();