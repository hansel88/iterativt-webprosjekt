<?php 
// Sjekker om alt er fylt ut som det skal
if(!isset($_POST['mailInput'], $_POST['roomNumber'], $_POST['fromDate'], $_POST['toDate'], $_POST['generatedToken'])) {
    http_response_code(400); // 400 bad request
    exit();
}
else {
    $to = $_POST['mailInput']; 

    $from = "no-reply@room-booking.westerdals.no";
    $room = $_POST['roomNumber'];
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];

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
?>