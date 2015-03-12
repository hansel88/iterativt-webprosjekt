<?php

require 'config.php';
include 'header.php';

$token = isset($_GET['token']) ? $_GET['token'] : '';

if($token == "")
{
// no username entered
	echo "Noe gikk galt.";
}
else
{
	$sql = $database->prepare("select * from room_reservation where generatedToken = :token");
	$sql->setFetchMode(PDO::FETCH_OBJ);
	$sql->execute(array(
		'generatedToken' => $token
	));

	if (!$sql->fetch())
	{
		echo '<p>Vi kunne dessverre ikke finne reservasjonen din.</p>';
	}
	else
	{
		$reservation = $sql.fetch();
		$sql = $database->prepare("update table from room_reservation set confirmed = true where generatedToken = :token");
		$sql->execute();

		echo '<p>Reservasjonen din er bekreftet</p>';
	}
}

 include 'footer.php';