<?php
require 'config.php';
require 'header.php';

echo '<section id="wrapper"><h1>Booking</h1><br>';

if (isset($_GET['id']) && is_numeric($_GET['id']) && bookingExists($_GET['id']))
{
	$id = $_GET['id'];
	if (isset($_GET['token']) && $_GET['token'] != "" && checkToken($id, $_GET['token']))
	{
		$token = $_GET['token'];
		if (!isConfirmed($id)) confirmBooking($id, $token);
		orderInfo($id);
		echo '<br><a href="http://www.htodap.com/itw/php/cancelBooking.php?id=' . $id . '&token=' . $token . '"' . 'style="margin-left: 20px; text-decoration: none; padding: 8px; background-color: #E02F1C; color: white;\">Kansellèr Booking</a>';
	}
	else
	{
		orderInfo($id);
	}
}
else
{
	redirect();
}

echo '</section>';

function confirmBooking($id, $token)
{
	require 'config.php';
	$sql = $database->prepare("update room_reservation set confirmed = 1 where id = :id AND token = :token");
	$sql->execute(array(
		'id' => $id,
		'token' => $token
	));
	echo '<p>Reservasjonen din er bekreftet</p><br>';
}


function orderInfo($id)
{
	require 'config.php';
	if (!isConfirmed($id)) echo '<p>Denne orderen er ikke konfirmert enda, vennligst konfirmer den ved å gå inn på linken sendt til deg på epost.</p>';
	$sql = $database->prepare("SELECT b.room_nr, r.size, b.fromDate, b.toDate FROM room_reservation AS b JOIN room AS r ON b.room_nr = r.room_nr where id = :id");
	$sql->setFetchMode(PDO::FETCH_OBJ);
	$sql->execute(array(
		'id' => $id
	));
	$booking = $sql->fetch();
	echo "<p>Rom " . $booking->room_nr . " er reservert for " . $booking->size . " personer i " . $booking->fromDate . " timer fra " . $booking->toDate . ".</p>";
}

function bookingExists($id)
{
	require 'config.php';
	$sql = $database->prepare("select count(*) as sum from room_reservation where id = :id");
	$sql->setFetchMode(PDO::FETCH_OBJ);
	$sql->execute(array(
		'id' => $id
	));
	$fetch = $sql->fetch()->sum;
	if ($fetch != 1) return false;
	else return true;
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

function isConfirmed($id)
{
	require 'config.php';
	$sql = $database->prepare("select confirmed from room_reservation where id = :id");
	$sql->setFetchMode(PDO::FETCH_OBJ);
	$sql->execute(array(
		'id' => $id
	));
	$confirmed = $sql->fetch()->confirmed;
	return $confirmed;
}

function redirect()
{
	header("Location: ../index.php");
	exit();
}

require 'footer.php';
?>