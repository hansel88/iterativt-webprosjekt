<?php
require 'config.php';

// Sjekker om alt er fylt ut som det skal
if(!isset($_POST['email'], $_POST['date'], $_POST['room'], $_POST['fromTime'], $_POST['toTime'])) {
    http_response_code(400); // 400 bad request
    exit();
}
else {
    $to = $_POST['email']; 
    $token = uniqid(mt_rand(), true);
    $from = "no-reply@room-booking.westerdals.no";
    $room = $_POST['room'];
    $date = $_POST['date'];
    $fromTime = $_POST['fromTime'];
    $toTime = $_POST['toTime'];

    $sql = $database->prepare("INSERT INTO room_reservation (room_nr, user_email, date, fromTime, toTime, token) VALUES (:room_nr, :user_email, :date, :fromTime, :toTime, :token)");
    $sql->execute(array(
        'room_nr' => $room,
        'user_email' => $to,
        'date' => $date,
        'fromTime' => $fromTime,
        'toTime' => $toTime,
        'token' => $token
    ));

    $sql = $database->prepare("SELECT * FROM room_reservation WHERE room_nr = :room_nr AND user_email = :user_email AND date = :date AND fromTime = :fromTime AND toTime = :toTime AND token = :token");
    $sql->setFetchMode(PDO::FETCH_OBJ);
    $sql->execute(array(
        'room_nr' => $room,
        'user_email' => $to,
        'date' => $date,
        'fromTime' => $fromTime,
        'toTime' => $toTime,
        'token' => $token
    ));

    $id = $sql->fetch()->id;
    $subject = "Vennligst bekreft romreservasjon";

    $message = '<html><body>';
    $message .= '<h3>Hei, ' .$to . '</h3>';
    $message .= '<p>Rom ' .  $room . " " . "er holdt av for reservasjon av deg den " . $date . " fra " . $fromTime . " til " . $toTime . ". Bekreft romreservasjon ved å trykke på knappen under. Her kan du også se på orderen din og evt. kansellere den.<p>";
    $message .= '<a href="http://www.htodap.com/itw/php/order.php?id=' . $id . '&token=' . $token . '"' . 'style=\"margin-left: 20px; text-decoration: none; padding: 8px; background-color: #5A9E23; color: white;\">Bekreft Booking</a>';
    $message .= '<p>Med vennlig hilsen Rom-booking Westerdals</p>';
    $message .= "<p>English: Room" . $room . " " . " is reserved by you on " . $date . " from " . $fromTime . " to " . $toTime . ". Confirm reservation by clicking the link above. From there you can look at your order and cancel it.</p>";
    $message .= '</body></html>';

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= "From:" . $from;
    if(mail($to,$subject,$message,$headers)) {
        http_response_code(200);  // mail ble sendt, all is well
        redirect();
    }
    else
    {
        http_response_code(500); // klarte ikke å sende mail
    }
}

function redirect()
{
    header("Location: order.php?id=" . $id);
    exit;
}
?>