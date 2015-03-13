<?php

require 'config.php';
include 'header.php';
?>
<section id="wrapper">
<?php
	$token = isset($_GET['token']) ? $_GET['token'] : '';

	if($token == "")
	{
	// no username entered
		echo "Noe gikk galt.";
	}
	else
	{
		$sql = $database->prepare("select * from room_reservation where token = :token");
		$sql->setFetchMode(PDO::FETCH_OBJ);
		$sql->execute(array(
			'token' => $token
		));

		if (!$sql->fetch())
		{
			echo '<p>Vi kunne dessverre ikke finne reservasjonen din.</p>';
		}
		else
		{
			$reservation = $sql->fetch();
			$sql = $database->prepare("update room_reservation set confirmed = 1 where token = :token");
			$sql->execute(array(
				'token' => $token
			));


			echo '<p>Reservasjonen din er bekreftet</p>';
		}
	}
?>
</section>

<?php
 include 'footer.php';