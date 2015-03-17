<?php
session_start();

require 'config.php';
require 'header.php';

if (isset($_SESSION['email'], $_POST['room'], $_SESSION['fromDate'], $_SESSION['toDate']))
{
    $to = $_SESSION['email']; 
    $room = $_POST['room'];
    $fromDate = $_SESSION['fromDate'];
    $toDate = $_SESSION['toDate'];
	sendMail($to, $room, $fromDate, $toDate);
}
else
{
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
}

function sendMail($to, $room, $fromDate, $toDate)
{
    $token = uniqid(mt_rand(), true);
    echo $token;
    $from = "no-reply@room-booking.westerdals.no";

    $sql = $database->prepare(
    "INSERT INTO room_reservation (room_nr, user_email, fromDate, toDate, token, confirmed) VALUES (:room_nr, :user_email, :fromDate, :toDate, :token, 0);"
    );
    $sql->execute(array(
        'room_nr' => $room,
        'user_email' => $to,
        'fromDate' => $fromDate,
        'toDate' => $toDate,
        'token' => $token
    ));
    $sql = $database->prepare("SELECT id FROM room_reservation WHERE room_nr = :room_nr AND user_email = :user_email AND fromDate = :fromDate AND toDate = :toDate AND token = :token");
	$sql->setFetchMode(PDO::FETCH_OBJ);
    $sql->execute(array(
        'room_nr' => $room,
        'user_email' => $to,
        'fromDate' => $fromDate,
        'toDate' => $toDate,
        'token' => $token
    ));

    $id = $sql->fetch()->id;

    $subject = "Vennligst bekreft romreservasjon";

    $message = '<html><body>';
    $message .= '<h3>Hei, ' .$to . '</h3>';
    $message .= '<p>Rom ' .  $room . " " . "er holdt av for reservasjon av deg fra " . $fromDate . " til " . $toDate . ". Bekreft romreservasjon ved å trykke på knappen under.<p>";
    $message .= '<a href="http://www.htodap.com/itw/php/confirmBooking.php?id' . $id . '&token=' . $token . '"' . 'style=\"margin-left: 20px; text-decoration: none; padding: 8px; background-color: #5A9E23; color: white;\">Bekreft Booking</a>';
    $message .= '<p>Med vennlig hilsen Rom-booking Westerdals</p>';
    $message .= "<p>English: Room" . $room . " " . " is reserved by you from " . $fromDate . " to " . $toDate . ". Confirm reservation by clicking the first link above. Cancel reservation by opening the second one.</p>";
    $message .= '</body></html>';

    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= "From:" . $from;

    if(mail($to,$subject,$message,$headers)) {
        http_response_code(200);  // mail ble sendt, all is well
        echo '<section id=wrapper><h1>Rom satt av</h1><p>Rommet er nå satt av, for å fullføre bestillingen må du følge instruksjonene som er sendt til ' . $to . ' .</p></submit>';
        redirect();
    }
    else
    {
        http_response_code(500); // klarte ikke å sende mail
        redirect();
    }
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
	header("Location: ../index.php")
	session_unset();
	session_destroy();
	exit();
}

require 'footer.php';

session_unset();
session_destroy();
?>