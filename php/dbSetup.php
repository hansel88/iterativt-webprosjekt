<?php
require 'config.php';
// Create the table: blog
$sql = $database->prepare(
	"CREATE TABLE IF NOT EXISTS room (room_nr int NOT NULL, projector boolean NOT NULL, size int NOT NULL, PRIMARY KEY (room_nr));"
);
$sql->execute();
// Create the table: comments
$sql = $database->prepare(
	"CREATE TABLE IF NOT EXISTS room_reservation (id int NOT NULL AUTO_INCREMENT, room_nr int NOT NULL, user_email varchar(255) NOT NULL, fromDate datetime NOT NULL, toDate datetime NOT NULL, token varchar(255) NOT NULL, confirmed boolean, PRIMARY KEY (id), FOREIGN KEY (room_nr) REFERENCES room(room_nr));"
);
$sql->execute();

// Add some example content to the database

$sql = $database->prepare(
	"INSERT INTO room (room_nr, projector, size) VALUES (:room_nr, :projector, :size);"
);
$sql->execute(array(
	'room_nr' => 52,
	'projector' => true,
	'size' => 2
));

$sql = $database->prepare(
	"INSERT INTO room (room_nr, projector, size) VALUES (:room_nr, :projector, :size);"
);
$sql->execute(array(
	'room_nr' => 38,
	'projector' => false,
	'size' => 2
));

$sql = $database->prepare(
	"INSERT INTO room (room_nr, projector, size) VALUES (:room_nr, :projector, :size);"
);
$sql->execute(array(
	'room_nr' => 24,
	'projector' => true,
	'size' => 3
));

$sql = $database->prepare(
	"INSERT INTO room (room_nr, projector, size) VALUES (:room_nr, :projector, :size);"
);
$sql->execute(array(
	'room_nr' => 48,
	'projector' => false,
	'size' => 3
));

$sql = $database->prepare(
	"INSERT INTO room (room_nr, projector, size) VALUES (:room_nr, :projector, :size);"
);
$sql->execute(array(
	'room_nr' => 83,
	'projector' => true,
	'size' => 4
));

$sql = $database->prepare(
	"INSERT INTO room (room_nr, projector, size) VALUES (:room_nr, :projector, :size);"
);
$sql->execute(array(
	'room_nr' => 27,
	'projector' => false,
	'size' => 4
));

echo "Created tables";