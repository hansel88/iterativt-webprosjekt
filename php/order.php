<?php
require 'config.php';
require 'header.php';

if (isset($_GET['id']) && is_numeric($_GET['id']))
{
	$id = $_GET['id'];
	if (isset($_GET['token']) && $_GET['token'] != "")
	{
	$token = $_GET['token'];
		confirmBooking($id, $token);
		orderInfo($id);
		echo '<br><a href="http://www.htodap.com/itw/php/cancelBooking.php?id ' . $id . '&token=' . $token . ' &cancel=1"' . 'style="margin-left: 20px; text-decoration: none; padding: 8px; background-color: #E02F1C; color: white;\">Kansellèr Booking</a>'
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


function confirmBooking($id, $token)
{
	if (!bookingExists($id))
	{
		redirect();
	}
	else
	{
		$sql = $database->prepare("update room_reservation set confirmed = 1 where token = :token");
		$sql->execute(array(
			'id' => $id,
			'token' => $token
		));

		echo '<p>Reservasjonen din er bekreftet</p><br>';
	}
}

function cancelBooking($id, $token)
{
	if (!bookingExists($id))
	{
		redirect();
	}
	else
	{
		$sql = $database->prepare("delete from room_reservation where id = :id AND token = :token");
		$sql->execute(array(
			'id' => $id,
			'token' => $token
		));
		echo '<p id="infoText>Reservasjonen din er nå kansellert.</p>';

	}
}

function orderInfo($id)
{
	if (bookingExists($id))
	{
		$sql = $database->prepare("SELECT b.room_nr, r.size, b.time, b.fromDate FROM room_reservation AS b JOIN room AS r ON b.room_nr = r.room_nr where id = :id");
		$sql->setFetchMode(PDO::FETCH_OBJ);
		$sql->execute(array(
			'id' => $id
		));
		$booking = $sql->fetch();
		echo "<p>Rom " . $booking->room_nr . " er reservert for " . $booking->size . " personer i " . $booking->time . " timer fra " . $booking->fromDate . ".</p>"
	}
}

function bookingExists($id)
{
	$sql = $database->prepare("select * from room_reservation where id = :id");
	$sql->setFetchMode(PDO::FETCH_OBJ);
	$sql->execute(array(
		'id' => $id
	));

	if (!$sql->fetch()) return false;
	else return true;
}

function redirect()
{
	header("Location: ../index.php");
	exit();
}

require 'footer.php';
?>