<?php

require 'config.php';
include 'header.php';
?>
<section id="wrapper">
<?php

$token = isset($_GET['token']) ? $_GET['token'] : '';

if($token == "")
{
	echo '<p>Vi kunne ikke finne reservasjonen din.</p>';
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
		echo '<p>Vi kunne ikke finne reservasjonen din.</p>';
	}
	else
	{
		
		$reservation = $sql->fetch();
		$sql = $database->prepare("delete from room_reservation where token = :token");
		$sql->execute(array(
			'token' => $token
		));
		echo '<p>Reservasjonen din er nå kansellert.</p>';

	}
}

?>
</section>

<?php
 include 'footer.php';