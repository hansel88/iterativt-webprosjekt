<?php
require 'config.php';
// Create the table: blog
$sql = $database->prepare(
	"CREATE TABLE room (room_nr varchar(255) NOT NULL, projector boolean NOT NULL, size int NOT NULL, PRIMARY KEY (room_nr));"
);
$sql->execute();
// Create the table: comments
$sql = $database->prepare(
	"CREATE TABLE room_reservation (room_nr varchar(255) NOT NULL,user_email varchar(255) NOT NULL, fromDate date NOT NULL, toDate date NOT NULL, token varchar(255) NOT NULL, PRIMARY KEY (id, token), FOREIGN KEY (room_nr) REFERENCES room(room_nr));"
);
$sql->execute();
// Add some example content to the database


$sql = $database->prepare(
	"INSERT INTO room (room_nr, projector, size) VALUES (:room_nr, :projector, :size);"
);
$sql->execute(array(
	'room_nr' => "Rom 24",
	'projector' => true,
	'size' => 3
));

echo "Created tables";