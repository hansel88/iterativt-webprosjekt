<?php
session_start();
require 'config.php';
require 'header.php';

// Sjekker om alt er fylt ut som det skal
if(!isset($_SESSION['mail'], $_POST['room'], $_SESSION['fromDate'], $_SESSION['toDate'])) {
    http_response_code(400); // 400 bad request
    exit();
}
else {
    $to = $_SESSION['mail']; 
    $token = uniqid(mt_rand(), true);
    echo $token;
    $from = "no-reply@room-booking.westerdals.no";
    $room = $_POST['room'];
    $fromDate = $_SESSION['fromDate'];
    $toDate = $_SESSION['toDate'];

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

    $subject = "Vennligst bekreft romreservasjon";

    $message = $room . " " . "er holdt av for reservasjon av deg fra " . $from . " til " . $to . ". Bekreft romreservasjon ved å trykke på linken som er vedlagt." . "\n\n" . "www.home.nith.no/blablabla/confirmBooking?bookingToken=" . $_POST['generatedToken'] . "\n\n" . "Mvh Rom-booking Westerdals" ."\n\n\n\n" . "English: " . $room . " " . " is reserved by you from " . $from . " to " . $to . ". Confirm reservation by clicking the link above.";

    $headers = "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From:" . $from;
    if(mail($to,$subject,$message,$headers)) {
        http_response_code(200);  // mail ble sendt, all is well
        exit();
    }
    else
    {
        http_response_code(500); // klarte ikke å sende mail
        exit();
    }
}

echo '<section id=wrapper><h1>Rom satt av</h1><p>Rommet er nå satt av, for å fullføre bestillingen må du følge instruksjonene fått på mail.</p></submit>';
require 'footer.php';
session_unset();
session_destroy();
?>