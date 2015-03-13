<?php
session_start();
require 'config.php';
require 'header.php';

// Sjekker om alt er fylt ut som det skal
if(!isset($_SESSION['email'], $_POST['room'], $_SESSION['fromDate'], $_SESSION['toDate'])) {
    http_response_code(400); // 400 bad request
    exit();
}
else {
    $to = $_SESSION['email']; 
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

    $message = '<html><body>';
    $message .= '<h3>Hei, ' .$to . '</h3>';
    $message .= '<p>Rom ' .  $room . " " . "er holdt av for reservasjon av deg fra " . $fromDate . " til " . $toDate . ". Bekreft romreservasjon ved å trykke på knappen under.<p>";
    $message .= '<a href="http://www.htodap.com/itw/php/confirmBooking.php?token=' . $token . '"' . 'style=\"margin-left: 20px; text-decoration: none; padding: 8px; background-color: #5A9E23; color: white;\">Bekreft Booking</a>';
    $message .= '<p>For kansellering av rom, åpne denne linken: ';
    $message .= '<a href="http://www.htodap.com/itw/php/cancelBooking.php?token=' . $token . '"' . 'style=\"margin-left: 20px; text-decoration: none; padding: 8px; background-color: #E02F1C; color: white;\">Kansellèr Booking</a>';
    $message .= '<p>Med vennlig hilsen Rom-booking Westerdals</p>';
    $message .= "<p>English: Room" . $room . " " . " is reserved by you from " . $fromDate . " to " . $toDate . ". Confirm reservation by clicking the first link above. Cancel reservation by opening the second one.</p>";
    $message .= '</body></html>';

    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= "From:" . $from;
    if(mail($to,$subject,$message,$headers)) {
        http_response_code(200);  // mail ble sendt, all is well
        echo '<section id=wrapper><h1>Rom satt av</h1><p>Rommet er nå satt av, for å fullføre bestillingen må du følge instruksjonene som er sendt til ' . $to . ' .</p></submit>';
        exit();
    }
    else
    {
        http_response_code(500); // klarte ikke å sende mail
        exit();
    }
}
require 'footer.php';
session_unset();
session_destroy();
?>