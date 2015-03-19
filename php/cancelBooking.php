<?php
include 'header.php';

echo '<section id="wrapper"><h3>Booking kansellert</h3>';

if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['token']) && $_GET['token'] != "" && checkToken($_GET['id'], $_GET['token']))
{
	$id = $_GET['id'];
	$token = $_GET['token'];
	cancelBooking($id, $token);
}
else
{
	redirect();
}

function cancelBooking($id, $token)
{
	require 'config.php';
	$sql = $database->prepare("delete from room_reservation where id = :id AND token = :token");
	$sql->execute(array(
		'id' => $id,
		'token' => $token
	));
	echo '<p>Reservasjonen din er nå kansellert.</p>';
}

function checkToken($id, $token)
{
	require 'config.php';
	$sql = $database->prepare("select token from room_reservation where id = :id");
	$sql->setFetchMode(PDO::FETCH_OBJ);
	$sql->execute(array(
		'id' => $id
	));
	$sqltoken = $sql->fetch()->token;
	if ($sqltoken == $token) return true;
	else return false;
}

function redirect()
{
	header("Location: ../index.php");
	exit();
}
echo '</section>';

include 'footer.php';